<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkClickController extends Controller
{
    public function redirect(Request $request, Link $link)
    {
        if (!$link->is_active || !$link->profile?->is_published) {
            abort(404);
        }

        // Don't track if owner clicks their own link
        $isOwner = auth()->check() && auth()->user()->profile?->id === $link->profile_id;

        if (!$isOwner) {
            $link->analyticsEvents()->create([
                'profile_id' => $link->profile_id,
                'link_id' => $link->id,
                'event_type' => 'LINK_CLICK',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return redirect()->away($link->url);
    }
}
