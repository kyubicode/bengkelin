<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug', 'permissions'])]
class Role extends Model
{
    use HasFactory;

    // Slug tetap, dipakai untuk pengecekan logic di kode (bukan hanya label tampilan)
    public const ADMIN   = 'admin';
    public const TEKNISI = 'teknisi';

    /**
     * BARU: cast kolom 'permissions' dari JSON di database menjadi array PHP
     * secara otomatis. Tanpa ini, $role->permissions akan berupa string JSON
     * mentah, bukan array yang bisa langsung dipakai in_array().
     */
    protected function casts(): array
    {
        return [
            'permissions' => 'array',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * BARU: Cek apakah role ini punya izin akses ke menu/resource tertentu.
     *
     * $key harus cocok dengan salah satu key yang didefinisikan di
     * App\Support\NavigationPermissions::all(), misalnya 'bookings',
     * 'services', 'users', dst.
     *
     * Dipanggil dari trait HasNavigationPermission di setiap Filament
     * Resource untuk menentukan apakah menu itu muncul di sidebar atau tidak.
     *
     * Contoh pemakaian:
     *   $role->hasPermission('bookings'); // true / false
     */
    public function hasPermission(string $key): bool
    {
        return in_array($key, $this->permissions ?? []);
    }
}