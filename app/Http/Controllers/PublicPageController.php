<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Navigation;
use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    public function resolve($slug = null)
    {
        // Jika slug tidak ada (akses ke /), ambil halaman yang module_type-nya 'home' atau urutan pertama
        if (!$slug) {
            $page = Page::where('module_type', 'home')
                ->where('is_published', true)
                ->first();

            // Fallback jika tidak ada yang ber-module_type 'home', ambil halaman terpublikasi pertama
            if (!$page) {
                $page = Page::where('is_published', true)->firstOrFail();
            }
        } else {
            // Jika ada slug, cari berdasarkan slug tersebut
            $page = Page::where('slug', $slug)
                ->where('is_published', true)
                ->firstOrFail();
        }

        $globalNavigations = Navigation::with(['page', 'children' => function($query) {
            $query->where('is_active', true)->orderBy('order', 'asc');
            }, 'children.page'])
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('order', 'asc')
            ->get();

        if (in_array($page->module_type, ['home', 'hero'])) {
            return view('frontend.moduls.home', compact('page', 'globalNavigations'));
        }

        // module_type lain (about, contact, booking, booking_tracker, custom, dll)
        // semuanya dirender lewat satu view content.blade.php,
        // dan di dalam file itu sudah ada percabangan @if/@elseif
        // berdasarkan module_type untuk menyisipkan komponen Livewire yang sesuai.
        return view('frontend.moduls.content', compact('page', 'globalNavigations'));
    }
}