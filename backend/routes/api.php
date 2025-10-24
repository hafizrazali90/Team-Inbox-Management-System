<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\WhatsAppWebhookController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\BroadcastController;
use App\Http\Controllers\Api\AnalyticsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// WhatsApp Webhook Routes (public, no auth required)
Route::get('/webhook/whatsapp', [WhatsAppWebhookController::class, 'verify']);
Route::post('/webhook/whatsapp', [WhatsAppWebhookController::class, 'receive']);

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/register', [AuthController::class, 'register']); // Admin only
    });
});

// Protected API Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Conversation Routes
    Route::prefix('conversations')->group(function () {
        Route::get('/', [ConversationController::class, 'index']);
        Route::get('/{id}', [ConversationController::class, 'show']);
        Route::post('/{id}/assign', [ConversationController::class, 'assign']);
        Route::patch('/{id}/status', [ConversationController::class, 'updateStatus']);
        Route::post('/{id}/follow-up', [ConversationController::class, 'setFollowUp']);
    });

    // Message Routes
    Route::prefix('messages')->group(function () {
        Route::post('/send', [MessageController::class, 'send']);
    });

    // Note Routes
    Route::prefix('conversations/{conversation_id}/notes')->group(function () {
        Route::get('/', [NoteController::class, 'index']);
        Route::post('/', [NoteController::class, 'store']);
    });
    Route::delete('/notes/{note_id}', [NoteController::class, 'destroy']);

    // Tag Routes
    Route::prefix('tags')->group(function () {
        Route::get('/', [TagController::class, 'index']);
        Route::post('/', [TagController::class, 'store']);
    });
    Route::post('/conversations/{conversation_id}/tags', [TagController::class, 'addToConversation']);
    Route::delete('/conversations/{conversation_id}/tags/{tag_id}', [TagController::class, 'removeFromConversation']);

    // Broadcast Routes
    Route::prefix('broadcasts')->group(function () {
        Route::get('/', [BroadcastController::class, 'index']);
        Route::post('/', [BroadcastController::class, 'store']);
        Route::get('/{id}', [BroadcastController::class, 'show']);
        Route::post('/{id}/send', [BroadcastController::class, 'send']);
    });

    // Analytics Routes
    Route::prefix('analytics')->group(function () {
        Route::get('/summary', [AnalyticsController::class, 'summary']);
        Route::get('/agent-performance', [AnalyticsController::class, 'agentPerformance']);
    });
});
