<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'registration_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'registration_date' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->whereHas('user', function ($u) use ($search) {
                $u->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            });
        })->when($filters['event_id'] ?? null, function ($q, $eventId) {
            $q->where('event_id', $eventId);
        })->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        });
    }
}
