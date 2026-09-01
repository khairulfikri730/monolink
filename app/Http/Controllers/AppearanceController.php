<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $request->validate([
            'background_type' => 'required|in:SOLID,GRADIENT,IMAGE',
            'background_value' => 'required|string',
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'text_color' => 'required|string',
            'button_color' => 'required|string',
            'button_text_color' => 'required|string',
            'button_style' => 'required|in:ROUNDED,PILL,SQUARE,GLASS,OUTLINE',
            'font_family' => 'required|string',
            'layout' => 'required|in:center,left,right',
        ]);

        $profile = $request->user()->profile;
        
        // Update or Create theme
        if ($profile->theme) {
            $profile->theme->update($request->all());
        } else {
            $profile->theme()->create($request->all());
        }

        return back()->with('success', 'Appearance updated successfully.');
    }
}
