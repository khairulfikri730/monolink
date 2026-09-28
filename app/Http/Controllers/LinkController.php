<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $links = $request->user()->profile->links()->orderBy('sort_order')->get();
        return view('dashboard.links', compact('links'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url:schema,http,https|max:2048',
            'type' => 'nullable|in:CUSTOM,GOOGLE_MAPS,MAP',
        ]);

        $profile = $request->user()->profile;
        $maxOrder = $profile->links()->max('sort_order') ?? 0;

        $url = trim($request->url);
        $type = $request->filled('type') ? strtoupper($request->type) : null;
        // Auto-detect Google Maps if type not explicitly set — highly custom: tinggal paste link Maps
        if (!$type) {
            $type = $this->isGoogleMapsUrl($url) ? 'GOOGLE_MAPS' : 'CUSTOM';
        }

        // For Maps links, expand Google shorteners (maps.app.goo.gl / g.co) to the
        // long URL that carries @lat,lng so the public-page embed can render it.
        // Safe: request only ever goes to a fixed Google host, never user input.
        if ($type === 'GOOGLE_MAPS') {
            $url = $this->resolveMapsUrl($url);
        }

        $profile->links()->create([
            'title' => $request->title,
            'url' => $url,
            'type' => $type,
            'sort_order' => $maxOrder + 1,
        ]);

        $msg = $type === 'GOOGLE_MAPS' ? 'Lokasi Maps berhasil ditambahkan!' : 'Link added successfully.';
        return back()->with('success', $msg);
    }

    private function isGoogleMapsUrl(string $url): bool
    {
        $lower = strtolower($url);
        return str_contains($lower, 'google.com/maps')
            || str_contains($lower, 'maps.google.')
            || str_contains($lower, 'maps.app.goo.gl')
            || str_contains($lower, 'goo.gl/maps')
            || str_contains($lower, 'google.co.id/maps');
    }

    /**
     * Expand a Google Maps shortener to its long form (which contains @lat,lng).
     * Only ever contacts fixed Google shortener hosts, so it cannot be pointed
     * at internal/volatile endpoints. Returns the original URL on any failure.
     */
    private function resolveMapsUrl(string $url): string
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $shorteners = ['maps.app.goo.gl', 'g.co', 'www.g.co'];

        if (!in_array($host, $shorteners, true)) {
            return $url;
        }

        $headers = @get_headers($url, 1);
        if (is_array($headers)) {
            $location = $headers['Location'] ?? null;
            if (is_array($location)) {
                $location = end($location);
            }
            if ($location && filter_var($location, FILTER_VALIDATE_URL)) {
                return $location;
            }
        }

        return $url;
    }

    public function update(Request $request, Link $link)
    {
        if ($link->profile_id !== $request->user()->profile->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url:schema,http,https|max:2048',
            'type' => 'nullable|in:CUSTOM,GOOGLE_MAPS,MAP',
        ]);

        $url = trim($request->url);
        $type = $request->filled('type') ? strtoupper($request->type) : $link->type;

        if ($type === 'GOOGLE_MAPS') {
            $url = $this->resolveMapsUrl($url);
        }

        $link->update([
            'title' => $request->title,
            'url' => $url,
            'type' => $type,
        ]);

        return back()->with('success', 'Link berhasil diperbarui.');
    }

    public function toggle(Request $request, Link $link)
    {
        if ($link->profile_id !== $request->user()->profile->id) {
            abort(403);
        }

        $link->update(['is_active' => !$link->is_active]);
        return back()->with('success', 'Link status updated.');
    }

    public function destroy(Request $request, Link $link)
    {
        if ($link->profile_id !== $request->user()->profile->id) {
            abort(403);
        }

        $link->delete();
        return back()->with('success', 'Link deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ordered_ids' => 'required|string',
        ]);

        $ids = array_filter(array_map('intval', explode(',', $request->ordered_ids)));
        $profileId = $request->user()->profile->id;

        foreach (array_values($ids) as $index => $id) {
            Link::where('id', $id)->where('profile_id', $profileId)->update(['sort_order' => $index + 1]);
        }

        return back()->with('success', 'Links reordered.');
    }
}
