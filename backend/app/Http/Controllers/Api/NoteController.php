<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Conversation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Add a note to a conversation.
     */
    public function store(Request $request, $conversationId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $conversation = Conversation::findOrFail($conversationId);
        $user = $request->user();

        $note = Note::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'content' => $request->content,
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'add_note',
            'entity_type' => 'Note',
            'entity_id' => $note->id,
            'description' => 'Added note to conversation',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Note added successfully',
            'note' => $note->load('user'),
        ], 201);
    }

    /**
     * Get all notes for a conversation.
     */
    public function index($conversationId)
    {
        $notes = Note::where('conversation_id', $conversationId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'notes' => $notes,
        ]);
    }

    /**
     * Delete a note.
     */
    public function destroy(Request $request, $noteId)
    {
        $note = Note::findOrFail($noteId);
        $user = $request->user();

        // Only allow deletion by note creator or admin/manager
        if ($note->user_id !== $user->id && !$user->canManageConversations()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $note->delete();

        return response()->json([
            'message' => 'Note deleted successfully',
        ]);
    }
}
