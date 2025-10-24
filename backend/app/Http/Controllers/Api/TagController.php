<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Conversation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Get all tags for a department.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Tag::query();

        // Filter by department if not admin/OM
        if (!$user->isAdmin() && !$user->isOperationManager()) {
            $query->where('department_id', $user->department_id);
        }

        return response()->json([
            'tags' => $query->get(),
        ]);
    }

    /**
     * Create a new tag (Admin/Manager only).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:tags,name',
            'color' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $tag = Tag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'color' => $request->color ?? '#3B82F6',
            'department_id' => $request->department_id,
        ]);

        return response()->json([
            'message' => 'Tag created successfully',
            'tag' => $tag,
        ], 201);
    }

    /**
     * Add tag to conversation.
     */
    public function addToConversation(Request $request, $conversationId)
    {
        $request->validate([
            'tag_id' => 'required|exists:tags,id',
        ]);

        $conversation = Conversation::findOrFail($conversationId);
        $user = $request->user();

        // Attach tag if not already attached
        if (!$conversation->tags()->where('tag_id', $request->tag_id)->exists()) {
            $conversation->tags()->attach($request->tag_id, [
                'tagged_by' => $user->id,
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'add_tag',
                'entity_type' => 'Conversation',
                'entity_id' => $conversation->id,
                'description' => "Added tag ID {$request->tag_id} to conversation",
                'ip_address' => $request->ip(),
            ]);
        }

        return response()->json([
            'message' => 'Tag added to conversation',
            'conversation' => $conversation->load('tags'),
        ]);
    }

    /**
     * Remove tag from conversation.
     */
    public function removeFromConversation(Request $request, $conversationId, $tagId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        $conversation->tags()->detach($tagId);

        return response()->json([
            'message' => 'Tag removed from conversation',
        ]);
    }
}
