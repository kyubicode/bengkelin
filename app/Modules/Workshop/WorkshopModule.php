<?php
namespace App\Modules\Workshop;

use App\Models\Page;
use App\Modules\ModuleInterface;
use Illuminate\Http\Request;

class WorkshopModule implements ModuleInterface
{
    public function render(Page $page, Request $request)
    {
        return view('frontend.page', compact('page'));
    }
}