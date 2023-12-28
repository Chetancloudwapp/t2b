<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Entities\Event;
use App\Models\User;
use App\Models\News;
use App\Models\Photo;

class DashboardController extends Controller
{
   // dashboard index
    public function index(Request $request)
    {
        $total_members = User::count();
        $total_events  = Event::count();
        $total_news    = News::count();
        $total_photos  = Photo::count();
        
        return view('admin::admin.dashboard')->with(compact('total_members','total_events','total_news','total_photos'));
    }
}
