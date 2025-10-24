<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\ActivityLog;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    /**
     * Send a message via WhatsApp Cloud API.
     */
    public function send(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'type' => 'required|in:text,image,video,document,audio',
            'content' => 'required_if:type,text|string',
            'media_file' => 'required_unless:type,text|file',
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);
        $user = $request->user();

        // Prepare WhatsApp API payload
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $conversation->contact_phone,
        ];

        $mediaUrl = null;

        if ($request->type === 'text') {
            $payload['type'] = 'text';
            $payload['text'] = ['body' => $request->content];
        } else {
            // Handle media upload
            if ($request->hasFile('media_file')) {
                $file = $request->file('media_file');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = Storage::disk('s3')->put("media/{$conversation->id}", $file);
                $mediaUrl = Storage::disk('s3')->url($path);
            }

            $payload['type'] = $request->type;
            $payload[$request->type] = ['link' => $mediaUrl];
        }

        // Send to WhatsApp Cloud API
        try {
            $response = Http::withToken(config('services.whatsapp.token'))
                ->post(config('services.whatsapp.api_url') . '/' . config('services.whatsapp.phone_id') . '/messages', $payload);

            if ($response->failed()) {
                return response()->json([
                    'message' => 'Failed to send message',
                    'error' => $response->json(),
                ], 500);
            }

            $whatsappMessageId = $response->json('messages.0.id');

            // Save message to database
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'whatsapp_message_id' => $whatsappMessageId,
                'direction' => 'outbound',
                'type' => $request->type,
                'content' => $request->content,
                'media_url' => $mediaUrl,
                'sender_id' => $user->id,
                'status' => 'sent',
            ]);

            // Update conversation
            $conversation->update([
                'last_message_at' => now(),
                'response_count' => $conversation->response_count + 1,
            ]);

            // Set first response time if not set
            if (!$conversation->first_response_at) {
                $conversation->update(['first_response_at' => now()]);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'send_message',
                'entity_type' => 'Message',
                'entity_id' => $message->id,
                'description' => 'Sent message via WhatsApp',
                'ip_address' => $request->ip(),
            ]);

            // Broadcast message to real-time subscribers
            event(new MessageSent($message));

            return response()->json([
                'message' => 'Message sent successfully',
                'data' => $message->load('sender'),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send message',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
