<?php

namespace App\Http\Controllers;

use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index(Request $request)
    {
        $socialLinks = $request->user()->profile->socialLinks;
        $platforms = ['INSTAGRAM', 'TIKTOK', 'YOUTUBE', 'FACEBOOK', 'LINKEDIN', 'X_TWITTER'];
        return view('dashboard.social', compact('socialLinks', 'platforms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'platform' => 'required|string',
            'url' => 'required|url|max:255',
        ]);

        $profile = $request->user()->profile;

        // Prevent duplicate platform
        if ($profile->socialLinks()->where('platform', $request->platform)->exists()) {
            return back()->with('error', 'Platform already added.');
        }

        $maxOrder = $profile->socialLinks()->max('sort_order') ?? 0;

        $profile->socialLinks()->create([
            'platform' => $request->platform,
            'url' => $request->url,
            'sort_order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Social link added successfully.');
    }

    public function destroy(Request $request, SocialLink $socialLink)
    {
        if ($socialLink->profile_id !== $request->user()->profile->id) {
            abort(403);
        }

        $socialLink->delete();
        return back()->with('success', 'Social link deleted.');
    }
}
