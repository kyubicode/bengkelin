<?php 

namespace App\Modules;

use Illuminate\Http\Request;
use App\Models\Page;

interface ModuleInterface
{
    // Mengembalikan response view atau data
    public function render(Page $page, Request $request);
}