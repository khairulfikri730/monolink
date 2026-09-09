<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show(Request $request, $username)
    {
        $profile = Profile::where('username', $username)
            ->where('is_published', true)
            ->with(['theme', 'links' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }, 'socialLinks' => function($q) {
                $q->orderBy('sort_order');
            }])
            ->firstOrFail();

        // Track view
        // Prevent counting views from the owner themselves
        $isOwner = auth()->check() && auth()->id() === $profile->user_id;
        
        if (!$isOwner) {
            $profile->analyticsEvents()->create([
                'event_type' => 'PROFILE_VIEW',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        $response = response()->view('profile.show', compact('profile'));
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        return $response;
    }
}
