<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    /**
     * Get list of conversations with filters.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Conversation::with(['assignedUser', 'department', 'tags', 'lastMessage'])
            ->orderBy('last_message_at', 'desc');

        // Filter by department if not admin/OM
        if (!$user->isAdmin() && !$user->isOperationManager()) {
            $query->where('department_id', $user->department_id);
        }

        // Filter by assignment if CX agent
        if ($user->role->slug === 'cx') {
            $query->where('assigned_to', $user->id);
        }

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Tag filter
        if ($request->has('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }

        // Search by contact name or phone
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('contact_name', 'like', "%{$search}%")
                  ->orWhere('contact_phone', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'conversations' => $query->paginate(20),
        ]);
    }

    /**
     * Get a single conversation with messages.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $conversation = Conversation::with([
            'messages.sender',
            'notes.user',
            'tags',
            'assignedUser',
            'department'
        ])->findOrFail($id);

        // Check access permissions
        if (!$user->isAdmin() && !$user->isOperationManager()) {
            if ($conversation->department_id !== $user->department_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            if ($user->role->slug === 'cx' && $conversation->assigned_to !== $user->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        return response()->json([
            'conversation' => $conversation,
        ]);
    }

    /**
     * Assign conversation to a user.
     */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = $request->user();
        if (!$user->canManageConversations()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $conversation = Conversation::findOrFail($id);
        $conversation->update([
            'assigned_to' => $request->user_id,
            'status' => 'open',
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'assign_conversation',
            'entity_type' => 'Conversation',
            'entity_id' => $conversation->id,
            'description' => "Assigned conversation to user ID {$request->user_id}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Conversation assigned successfully',
            'conversation' => $conversation->load('assignedUser'),
        ]);
    }

    /**
     * Update conversation status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,pending,closed',
        ]);

        $conversation = Conversation::findOrFail($id);
        $conversation->update(['status' => $request->status]);

        // Log activity
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'update_conversation_status',
            'entity_type' => 'Conversation',
            'entity_id' => $conversation->id,
            'description' => "Updated conversation status to {$request->status}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Conversation status updated',
            'conversation' => $conversation,
        ]);
    }

    /**
     * Set follow-up date for conversation.
     */
    public function setFollowUp(Request $request, $id)
    {
        $request->validate([
            'follow_up_at' => 'required|date|after:now',
        ]);

        $conversation = Conversation::findOrFail($id);
        $conversation->update([
            'follow_up_at' => $request->follow_up_at,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Follow-up scheduled successfully',
            'conversation' => $conversation,
        ]);
    }
}
