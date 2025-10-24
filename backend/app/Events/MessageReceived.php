<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcast to the conversation channel
        return [
            new Channel('conversation.' . $this->message->conversation_id),
            new Channel('department.' . $this->message->conversation->department_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.received';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'direction' => $this->message->direction,
            'type' => $this->message->type,
            'content' => $this->message->content,
            'media_url' => $this->message->media_url,
            'status' => $this->message->status,
            'created_at' => $this->message->created_at->toISOString(),
            'conversation' => [
                'id' => $this->message->conversation->id,
                'contact_name' => $this->message->conversation->contact_name,
                'contact_phone' => $this->message->conversation->contact_phone,
                'status' => $this->message->conversation->status,
            ],
        ];
    }
}
