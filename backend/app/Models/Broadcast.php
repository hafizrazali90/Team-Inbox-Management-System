<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Broadcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'created_by',
        'name',
        'message_content',
        'template_name',
        'recipient_type',
        'recipient_filter',
        'status',
        'total_recipients',
        'sent_count',
        'delivered_count',
        'failed_count',
        'scheduled_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the department that owns the broadcast.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user who created the broadcast.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the broadcast contacts for this broadcast.
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(BroadcastContact::class);
    }

    /**
     * Scope a query to only include scheduled broadcasts.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope a query to only include completed broadcasts.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
