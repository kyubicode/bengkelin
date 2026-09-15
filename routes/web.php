<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicPageController;


// Catch-all route untuk seluruh halaman CMS publik
Route::get('/{slug?}', [PublicPageController::class, 'resolve'])
    ->where('slug', '.*')
    ->name('public.page');