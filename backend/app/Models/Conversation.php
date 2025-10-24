<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'whatsapp_id',
        'contact_name',
        'contact_phone',
        'department_id',
        'assigned_to',
        'status',
        'last_message_at',
        'first_response_at',
        'response_count',
        'follow_up_at',
        'is_ai_handled',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'first_response_at' => 'datetime',
        'follow_up_at' => 'datetime',
        'is_ai_handled' => 'boolean',
    ];

    /**
     * Get the department that owns the conversation.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user assigned to this conversation.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the messages for the conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the notes for the conversation.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Get the tags for the conversation.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'conversation_tags')
            ->withPivot('tagged_by')
            ->withTimestamps();
    }

    /**
     * Get the AI logs for the conversation.
     */
    public function aiLogs(): HasMany
    {
        return $this->hasMany(AiLog::class);
    }

    /**
     * Get the last message in the conversation.
     */
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Scope a query to only include open conversations.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope a query to only include pending conversations.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include closed conversations.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Scope a query to include conversations with follow-ups due.
     */
    public function scopeFollowUpDue($query)
    {
        return $query->whereNotNull('follow_up_at')
            ->where('follow_up_at', '<=', now());
    }
}
