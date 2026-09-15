<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'module_type',
        'settings',
        'meta_title',
        'meta_description',
        'is_published',
    ];

    protected $casts = [
        'content' => 'array',
        'settings' => 'array',
        'is_published' => 'boolean',
    ];

    public function navigations(): HasMany
    {
        return $this->hasMany(Navigation::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function gallery(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    /**
     * Cari URL halaman berdasarkan module_type.
     * Dipakai buat redirect dinamis biar gak hardcode slug.
     */
    public static function urlByModule(string $moduleType, string $fallback = '/'): string
    {
        $page = static::where('module_type', $moduleType)
            ->published()
            ->first();

        return $page ? '/' . ltrim($page->slug, '/') : $fallback;
    }
}