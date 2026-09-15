<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Navigation extends Model
{
    protected $fillable = [
        'label',
        'parent_id',
        'page_id',
        'url',
        'target',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order', 'asc');
    }

    // Accessor cerdas: Memutuskan URL dari Halaman CMS atau Link Eksternal
    public function getComputedUrlAttribute(): string
    {
        if ($this->page_id && $this->page) {
            return url('/' . ltrim($this->page->slug, '/'));
        }

        return $this->url ?? '#';
    } // <-- Pastikan kurung kurawal ini ada!
}