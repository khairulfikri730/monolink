<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppearanceController extends Controller
{
    public function edit(Request $request)
    {
        $profile = $request->user()->profile;
        $theme = $profile->theme;

        return view('dashboard.appearance', compact('theme', 'profile'));
    }

    public function update(Request $request)
    {
        // Normalize layout case-insensitive before validation
        if ($request->has('layout')) {
            $request->merge(['layout' => strtolower(trim($request->input('layout')))]);
        }
        $request->validate([
            'background_type'      => 'required|in:SOLID,GRADIENT,IMAGE',
            'background_value'     => 'nullable|string|max:500',
            'gradient_direction'   => 'nullable|string|in:to right,to left,to bottom,to top,to bottom right,to bottom left,to top right,to top left',
            'gradient_colors'      => 'nullable|array|max:5',
            'gradient_colors.*'    => ['nullable','regex:/^#[0-9a-fA-F]{6}$/'],
            'background_image_url' => 'nullable|url|max:2048',
            'background_image_file'=> 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'primary_color'        => ['required','regex:/^#[0-9a-fA-F]{6}$/'],
            'secondary_color'      => ['required','regex:/^#[0-9a-fA-F]{6}$/'],
            'text_color'           => ['required','regex:/^#[0-9a-fA-F]{6}$/'],
            'button_color'         => ['required','regex:/^#[0-9a-fA-F]{6}$/'],
            'button_text_color'    => ['required','regex:/^#[0-9a-fA-F]{6}$/'],
            'button_style'         => 'required|in:ROUNDED,PILL,SQUARE,GLASS,OUTLINE,SHADOW,NEON',
            'font_family'          => 'required|string|in:Inter,Poppins,Roboto,Playfair Display,Merriweather,Space Mono,Nunito,Lato',
            'layout'               => 'required|in:center,left,right',

        ]);

        $profile = $request->user()->profile;

        $data = $request->except(['background_image_file', 'background_image_url', '_token']);

        // Handle background_image for IMAGE type
        if ($request->background_type === 'IMAGE') {
            if ($request->hasFile('background_image_file')) {
                // Delete old uploaded image if exists
                if ($profile->theme && $profile->theme->background_image) {
                    Storage::disk('public')->delete($profile->theme->background_image);
                }
                $path = $request->file('background_image_file')->store('backgrounds', 'public');
                $data['background_image'] = $path;
                $data['background_value'] = asset('storage/' . $path);
            } elseif ($request->filled('background_image_url')) {
                $data['background_value'] = $request->background_image_url;
                $data['background_image'] = null;
            }
        } else {
            $data['background_image'] = null;
        }

        // Build gradient background_value + ensure valid hex
        if ($request->background_type === 'GRADIENT') {
            $colors = array_values(array_filter($request->gradient_colors ?? [], fn($c) => preg_match('/^#[0-9a-fA-F]{6}$/', $c)));
            $direction = $request->gradient_direction ?? 'to bottom right';
            if (count($colors) >= 2) {
                $data['background_value'] = implode(',', $colors);
                $data['gradient_colors'] = $colors;
                $data['gradient_direction'] = $direction;
            } elseif (count($colors) === 1) {
                // fallback to solid if only one color
                $data['background_type'] = 'SOLID';
                $data['background_value'] = $colors[0];
                $data['gradient_colors'] = null;
            }
        }

        // Normalize empty gradient data for SOLID/IMAGE
        if ($request->background_type !== 'GRADIENT') {
            $data['gradient_colors'] = $data['gradient_colors'] ?? null;
        }

        // Update or Create theme
        if ($profile->theme) {
            $profile->theme->update($data);
        } else {
            $profile->theme()->create($data);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Tampilan berhasil diperbarui!']);
        }

        return back()->with('success', 'Tampilan berhasil diperbarui!');
    }
}
