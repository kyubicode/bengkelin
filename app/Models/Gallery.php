<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'image_path',
        'category',
        'order',
        'is_active',
    ];

    protected $casts = [
        'image_path' => 'array',
        'is_active' => 'boolean',
    ];

        // tambahin ini
    public function getImageUrlAttribute()
    {
        if (empty($this->image_path)) {
            return null;
        }

        // image_path array, ambil gambar pertama
        $path = is_array($this->image_path) ? $this->image_path[0] : $this->image_path;

        return asset('storage/' . $path);
    }
}