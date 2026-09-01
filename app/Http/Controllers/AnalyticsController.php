<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $profile = $request->user()->profile;
        
        // Get last 30 days stats
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        $views = $profile->analyticsEvents()
            ->where('event_type', 'PROFILE_VIEW')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();
            
        $clicks = $profile->analyticsEvents()
            ->where('event_type', 'LINK_CLICK')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();
            
        // Daily views for chart (last 7 days)
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $dailyViews = $profile->analyticsEvents()
            ->where('event_type', 'PROFILE_VIEW')
            ->where('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(created_at) as date, count(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();
            
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::now()->subDays($i)->format('M d');
            $chartData[] = $dailyViews[$date] ?? 0;
        }

        return view('dashboard.analytics', compact('views', 'clicks', 'chartLabels', 'chartData'));
    }
}
