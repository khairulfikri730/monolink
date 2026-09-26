<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $profile = $request->user()->profile;
        return view('dashboard.profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = $request->user()->profile;

        $validated = $request->validate([
            'username' => [
                'required', 'string', 'max:50', 'alpha_dash',
                Rule::unique('profiles')->ignore($profile->id),
                Rule::notIn([
                    'admin', 'dashboard', 'login', 'logout', 'register', 'account',
                    'l', 'storage', 'up', 'api', 'password', 'settings', 'profile',
                    'confirm-password', 'forgot-password', 'reset-password', 'verify-email',
                ]),
            ],
            'display_name' => ['required', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url:schema,http,https', 'max:255'],
            'profile_image' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,webp'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('profile_image')) {
            if ($profile->profile_image) {
                Storage::disk('public')->delete($profile->profile_image);
            }
            $path = $request->file('profile_image')->store('uploads/profiles', 'public');
            $validated['profile_image'] = $path;
        }

        // Checkbox handling (if not checked, it doesn't send in request)
        $validated['is_published'] = $request->has('is_published');

        $profile->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
