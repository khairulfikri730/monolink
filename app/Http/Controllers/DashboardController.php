<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $profile = $request->user()->profile()->withCount(['links', 'analyticsEvents'])->first();
        
        if (!$profile) {
            // Failsafe in case profile wasn't created
            $profile = $request->user()->profile()->create([
                'username' => \App\Models\Profile::generateUniqueUsername($request->user()->name),
                'display_name' => $request->user()->name,
            ]);
            $profile->theme()->create(\App\Models\Theme::defaults());

            return redirect()->route('dashboard');
        }

        $profileViews = $profile->analyticsEvents()->where('event_type', 'PROFILE_VIEW')->count();
        $linkClicks = $profile->analyticsEvents()->where('event_type', 'LINK_CLICK')->count();
        
        $ctr = $profileViews > 0 ? number_format(($linkClicks / $profileViews) * 100, 1) : "0.0";
        
        $isProfileComplete = !empty($profile->bio) && !empty($profile->profile_image) && $profile->links_count > 0;
        
        $activeLinks = $profile->links()->where('is_active', true)->orderBy('sort_order')->take(3)->get();

        return view('dashboard.index', compact(
            'profile', 'profileViews', 'linkClicks', 'ctr', 'isProfileComplete', 'activeLinks'
        ));
    }
}
