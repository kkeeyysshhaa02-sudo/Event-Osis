<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'created_by',
        'name',
        'slug',
        'description',
        'event_date',
        'location',
        'capacity',
        'status',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'capacity' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $baseSlug = Str::slug($event->name);
                $event->slug = $baseSlug.'-'.Str::random(5);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function activeRegistrations(): HasMany
    {
        return $this->hasMany(Registration::class)->whereIn('status', ['pending', 'approved', 'attended']);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'registrations')
            ->withPivot('status', 'registration_date', 'notes')
            ->withTimestamps();
    }

    public function availableSeats(): int
    {
        $registeredCount = array_key_exists('active_registrations_count', $this->getAttributes()) || isset($this->active_registrations_count)
            ? (int) $this->active_registrations_count
            : $this->registrations()->whereIn('status', ['approved', 'attended', 'pending'])->count();

        return max(0, $this->capacity - $registeredCount);
    }

    public function isFull(): bool
    {
        return $this->availableSeats() <= 0;
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        })->when($filters['category'] ?? null, function ($q, $category) {
            $q->where('category_id', $category);
        })->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        });
    }
}
