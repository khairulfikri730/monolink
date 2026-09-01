@extends('layouts.app')
@section('title', 'Settings')

@section('content')
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden animate-fade-in max-w-2xl">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h3>
        <form action="{{ route('dashboard.settings.update') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <input type="password" name="current_password" required class="block w-full rounded-md border-gray-300 focus:border-brand-500 sm:text-sm px-3 py-2 border">
                @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" name="password" required class="block w-full rounded-md border-gray-300 focus:border-brand-500 sm:text-sm px-3 py-2 border">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" required class="block w-full rounded-md border-gray-300 focus:border-brand-500 sm:text-sm px-3 py-2 border">
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-brand-600 text-white px-6 py-2 rounded-md hover:bg-brand-700 font-medium transition">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
