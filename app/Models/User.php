<?php

namespace App\Models;

use Filament\Models\FilamentUser;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function assignedBookings()
    {
        return $this->hasMany(Booking::class, 'technician_id');
    }

    public function isTeknisi(): bool
    {
        return $this->role?->slug === Role::TEKNISI;
    }

    public function isAdmin(): bool
    {
        return $this->role?->slug === Role::ADMIN;
    }

    // ⬇️ TAMBAHAN — WAJIB ADA untuk fitur ketersediaan teknisi
    public function scopeTechnicians($query)
    {
        return $query->whereHas('role', fn ($q) => $q->where('slug', Role::TEKNISI));
    }
}