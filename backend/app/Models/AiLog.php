<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'message_id',
        'prompt',
        'response',
        'model',
        'tokens_used',
        'was_sent',
        'required_handoff',
        'handoff_reason',
    ];

    protected $casts = [
        'was_sent' => 'boolean',
        'required_handoff' => 'boolean',
    ];

    /**
     * Get the conversation that owns the AI log.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the message that was generated (if sent).
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}
