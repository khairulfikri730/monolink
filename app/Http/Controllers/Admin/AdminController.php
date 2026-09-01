<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Link;
use App\Models\AnalyticsEvent;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalLinks = Link::count();
        $totalViews = AnalyticsEvent::where('event_type', 'PROFILE_VIEW')->count();
        $totalClicks = AnalyticsEvent::where('event_type', 'LINK_CLICK')->count();
        
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.index', compact('totalUsers', 'totalLinks', 'totalViews', 'totalClicks', 'recentUsers'));
    }
}
