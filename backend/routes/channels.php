<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Authenticate user for their own user channel
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Authenticate user for conversation channels they have access to
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    // Check if user has access to this conversation
    // Admin and OM can access all conversations
    if ($user->isAdmin() || $user->isOperationManager()) {
        return true;
    }

    // Managers and AMs can access all conversations in their department
    if ($user->canManageConversations()) {
        $conversation = \App\Models\Conversation::find($conversationId);
        return $conversation && $conversation->department_id === $user->department_id;
    }

    // CX agents can only access conversations assigned to them
    $conversation = \App\Models\Conversation::find($conversationId);
    return $conversation && $conversation->assigned_to === $user->id;
});

// Authenticate user for department channels
Broadcast::channel('department.{departmentId}', function ($user, $departmentId) {
    // Admin and OM can access all departments
    if ($user->isAdmin() || $user->isOperationManager()) {
        return true;
    }

    // Other users can only access their own department
    return (int) $user->department_id === (int) $departmentId;
});
