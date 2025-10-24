<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'archive_type',
        'original_id',
        'data',
        'archived_at',
    ];

    protected $casts = [
        'data' => 'array',
        'archived_at' => 'datetime',
    ];

    /**
     * Scope a query to filter by archive type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('archive_type', $type);
    }
}
