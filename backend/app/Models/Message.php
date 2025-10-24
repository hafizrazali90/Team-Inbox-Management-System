<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'whatsapp_message_id',
        'direction',
        'type',
        'content',
        'media_url',
        'mime_type',
        'sender_id',
        'status',
        'is_ai_generated',
        'read_at',
    ];

    protected $casts = [
        'is_ai_generated' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the conversation that owns the message.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the user who sent the message (null if customer).
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the AI log for this message if it was AI generated.
     */
    public function aiLog(): BelongsTo
    {
        return $this->belongsTo(AiLog::class, 'message_id');
    }

    /**
     * Scope a query to only include inbound messages.
     */
    public function scopeInbound($query)
    {
        return $query->where('direction', 'inbound');
    }

    /**
     * Scope a query to only include outbound messages.
     */
    public function scopeOutbound($query)
    {
        return $query->where('direction', 'outbound');
    }

    /**
     * Scope a query to only include AI generated messages.
     */
    public function scopeAiGenerated($query)
    {
        return $query->where('is_ai_generated', true);
    }
}
