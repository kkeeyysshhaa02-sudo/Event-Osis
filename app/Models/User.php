<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'kelas',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPanitia(): bool
    {
        return $this->role === 'panitia';
    }

    public function isPeserta(): bool
    {
        return $this->role === 'peserta';
    }

    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function registeredEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'registrations')
            ->withPivot('status', 'registration_date', 'notes')
            ->withTimestamps();
    }
}
