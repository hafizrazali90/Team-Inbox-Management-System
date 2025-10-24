<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Department;
use App\Events\MessageReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WhatsAppWebhookController extends Controller
{
    /**
     * Verify webhook (GET request from Meta).
     */
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === config('services.whatsapp.verify_token')) {
            return response($challenge, 200);
        }

        return response('Forbidden', 403);
    }

    /**
     * Receive incoming WhatsApp messages (POST request from Meta).
     */
    public function receive(Request $request)
    {
        Log::info('WhatsApp Webhook Received', $request->all());

        $data = $request->all();

        // Validate webhook structure
        if (!isset($data['entry'][0]['changes'][0]['value'])) {
            return response()->json(['status' => 'ignored'], 200);
        }

        $value = $data['entry'][0]['changes'][0]['value'];

        // Handle incoming messages
        if (isset($value['messages']) && count($value['messages']) > 0) {
            foreach ($value['messages'] as $message) {
                $this->processIncomingMessage($message, $value['contacts'][0] ?? []);
            }
        }

        // Handle message status updates (delivered, read, etc.)
        if (isset($value['statuses']) && count($value['statuses']) > 0) {
            foreach ($value['statuses'] as $status) {
                $this->processMessageStatus($status);
            }
        }

        return response()->json(['status' => 'received'], 200);
    }

    /**
     * Process incoming message from customer.
     */
    protected function processIncomingMessage(array $messageData, array $contact)
    {
        $whatsappId = $messageData['from'];
        $whatsappMessageId = $messageData['id'];
        $type = $messageData['type'];

        // Get or create conversation
        $conversation = Conversation::firstOrCreate(
            ['whatsapp_id' => $whatsappId],
            [
                'contact_name' => $contact['profile']['name'] ?? 'Unknown',
                'contact_phone' => $whatsappId,
                'department_id' => Department::where('slug', 'cx')->first()->id, // Default to CX
                'status' => 'open',
                'last_message_at' => now(),
            ]
        );

        // Extract message content based on type
        $content = null;
        $mediaUrl = null;
        $mimeType = null;

        switch ($type) {
            case 'text':
                $content = $messageData['text']['body'];
                break;
            case 'image':
            case 'video':
            case 'document':
            case 'audio':
                $mediaUrl = $messageData[$type]['url'] ?? null;
                $mimeType = $messageData[$type]['mime_type'] ?? null;
                $content = $messageData[$type]['caption'] ?? null;
                break;
        }

        // Save message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'whatsapp_message_id' => $whatsappMessageId,
            'direction' => 'inbound',
            'type' => $type,
            'content' => $content,
            'media_url' => $mediaUrl,
            'mime_type' => $mimeType,
            'status' => 'sent',
        ]);

        // Update conversation timestamp
        $conversation->update(['last_message_at' => now()]);

        // Broadcast incoming message to real-time subscribers
        event(new MessageReceived($message));

        // Trigger Sofia AI if no agent assigned (placeholder)
        if (!$conversation->assigned_to && config('services.openai.enabled', false)) {
            // dispatch(new ProcessAiResponse($conversation, $message));
        }

        Log::info('Message processed', ['conversation_id' => $conversation->id, 'message_id' => $message->id]);
    }

    /**
     * Process message status updates (delivered, read, failed).
     */
    protected function processMessageStatus(array $statusData)
    {
        $whatsappMessageId = $statusData['id'];
        $status = $statusData['status']; // sent, delivered, read, failed

        $message = Message::where('whatsapp_message_id', $whatsappMessageId)->first();

        if ($message) {
            $message->update(['status' => $status]);

            if ($status === 'read') {
                $message->update(['read_at' => now()]);
            }

            Log::info('Message status updated', ['message_id' => $message->id, 'status' => $status]);
        }
    }
}
