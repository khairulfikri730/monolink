@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden animate-fade-in">
    <div class="p-6">
        <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Profile Image Section -->
            <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                    <img src="{{ $profile->profile_image_url }}" alt="Profile" class="w-24 h-24 rounded-full object-cover border-4 border-gray-100">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                    <input type="file" name="profile_image" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-brand-50 file:text-brand-700
                        hover:file:bg-brand-100 transition
                    "/>
                    <p class="mt-2 text-xs text-gray-500">JPG, PNG or WEBP. Max 2MB.</p>
                    @error('profile_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-gray-200">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username (URL)</label>
                    <div class="flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            {{ request()->getHost() }}/
                        </span>
                        <input type="text" name="username" value="{{ old('username', $profile->username) }}" class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border" placeholder="username">
                    </div>
                    @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Display Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Display Name</label>
                    <input type="text" name="display_name" value="{{ old('display_name', $profile->display_name) }}" class="block w-full rounded-md border-gray-300 focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border shadow-sm">
                    @error('display_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Bio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                <textarea name="bio" rows="3" class="block w-full rounded-md border-gray-300 focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border shadow-sm" placeholder="Tell us about yourself...">{{ old('bio', $profile->bio) }}</textarea>
                @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="map-pin" class="h-4 w-4 text-gray-400"></i>
                        </div>
                        <input type="text" name="location" value="{{ old('location', $profile->location) }}" class="block w-full pl-10 rounded-md border-gray-300 focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border" placeholder="e.g. Jakarta, Indonesia">
                    </div>
                    @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Website -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Personal Website</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="globe" class="h-4 w-4 text-gray-400"></i>
                        </div>
                        <input type="url" name="website" value="{{ old('website', $profile->website) }}" class="block w-full pl-10 rounded-md border-gray-300 focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border" placeholder="https://example.com">
                    </div>
                    @error('website') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Published Toggle -->
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $profile->is_published) ? 'checked' : '' }} class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded">
                <label for="is_published" class="text-sm text-gray-700 font-medium">Make Profile Public</label>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-brand-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
