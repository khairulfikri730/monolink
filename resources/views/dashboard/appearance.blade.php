@extends('layouts.app')
@section('title', 'Appearance')

@section('content')
<div class="flex flex-col lg:flex-row gap-8 animate-fade-in">
    
    <!-- Settings Form -->
    <div class="flex-1 space-y-6">
        <form action="{{ route('dashboard.appearance.update') }}" method="POST" id="appearance-form" class="space-y-6 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            @csrf
            
            <!-- Font Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Font Family</label>
                <select name="font_family" class="w-full rounded-md border-gray-300 focus:border-brand-500 sm:text-sm px-3 py-2 border">
                    @foreach(['Inter', 'Roboto', 'Poppins', 'Playfair Display', 'Merriweather', 'Space Mono'] as $font)
                        <option value="{{ $font }}" {{ ($theme->font_family ?? 'Inter') == $font ? 'selected' : '' }}>{{ $font }}</option>
                    @endforeach
                </select>
            </div>

            <hr class="border-gray-100">

            <!-- Background Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Background Type</label>
                <div class="flex items-center gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="background_type" value="SOLID" {{ ($theme->background_type ?? 'SOLID') === 'SOLID' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                        <span class="ml-2 text-sm text-gray-700">Solid Color</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="background_type" value="GRADIENT" {{ ($theme->background_type ?? '') === 'GRADIENT' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                        <span class="ml-2 text-sm text-gray-700">Gradient</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="background_type" value="IMAGE" {{ ($theme->background_type ?? '') === 'IMAGE' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                        <span class="ml-2 text-sm text-gray-700">Image URL</span>
                    </label>
                </div>
            </div>

            <!-- Background Value -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Background Value</label>
                <p class="text-xs text-gray-400 mb-2">For Solid: HEX (e.g. #ffffff). For Gradient: HEX,HEX (e.g. #ff0000,#00ff00). For Image: URL</p>
                <input type="text" name="background_value" value="{{ old('background_value', $theme->background_value ?? '#ffffff') }}" class="w-full rounded-md border-gray-300 focus:border-brand-500 sm:text-sm px-3 py-2 border">
            </div>

            <hr class="border-gray-100">

            <!-- Colors -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Text Color</label>
                    <input type="color" name="text_color" value="{{ old('text_color', $theme->text_color ?? '#000000') }}" class="w-full h-10 p-1 border-0 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Primary Color (Accent)</label>
                    <input type="color" name="primary_color" value="{{ old('primary_color', $theme->primary_color ?? '#000000') }}" class="w-full h-10 p-1 border-0 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Button Color</label>
                    <input type="color" name="button_color" value="{{ old('button_color', $theme->button_color ?? '#000000') }}" class="w-full h-10 p-1 border-0 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                    <input type="color" name="button_text_color" value="{{ old('button_text_color', $theme->button_text_color ?? '#ffffff') }}" class="w-full h-10 p-1 border-0 rounded-md shadow-sm">
                </div>
            </div>
            
            <input type="hidden" name="secondary_color" value="{{ $theme->secondary_color ?? '#cccccc' }}">

            <hr class="border-gray-100">

            <!-- Button Style -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Button Shape</label>
                <select name="button_style" class="w-full rounded-md border-gray-300 focus:border-brand-500 sm:text-sm px-3 py-2 border">
                    @foreach(['ROUNDED', 'PILL', 'SQUARE', 'GLASS', 'OUTLINE'] as $style)
                        <option value="{{ $style }}" {{ ($theme->button_style ?? 'ROUNDED') == $style ? 'selected' : '' }}>{{ ucfirst(strtolower($style)) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Layout -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Layout Alignment</label>
                <select name="layout" class="w-full rounded-md border-gray-300 focus:border-brand-500 sm:text-sm px-3 py-2 border">
                    <option value="center" {{ ($theme->layout ?? 'center') == 'center' ? 'selected' : '' }}>Center</option>
                    <option value="left" {{ ($theme->layout ?? '') == 'left' ? 'selected' : '' }}>Left Align</option>
                </select>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-brand-600 text-white px-6 py-2 rounded-md hover:bg-brand-700 font-medium transition">
                    Save Appearance
                </button>
            </div>
        </form>
    </div>
    
    <!-- Live Preview Simulator -->
    <div class="hidden lg:block w-80 flex-shrink-0">
        <div class="sticky top-6">
            <p class="text-sm font-semibold text-gray-500 mb-3 text-center uppercase tracking-wider">Live Preview</p>
            <div class="border-[8px] border-gray-900 rounded-[2.5rem] w-[320px] h-[650px] overflow-hidden shadow-2xl relative bg-gray-50 bg-cover bg-center">
                <iframe src="{{ route('public.profile', $profile->username) }}" class="w-full h-full border-0"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection
