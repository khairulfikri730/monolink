@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="max-w-2xl bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden animate-fade-in">
    <div class="p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="block w-full rounded-md border-gray-300 focus:border-brand-500 sm:text-sm px-3 py-2 border">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="block w-full rounded-md border-gray-300 focus:border-brand-500 sm:text-sm px-3 py-2 border">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" required class="block w-full rounded-md border-gray-300 focus:border-brand-500 sm:text-sm px-3 py-2 border" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User (Standard)</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
                @if($user->id === auth()->id())
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <p class="text-xs text-gray-500 mt-1">You cannot change your own role.</p>
                @endif
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <hr class="border-gray-200 my-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Change Password <span class="text-gray-400 font-normal">(Leave blank to keep current)</span></label>
                <input type="password" name="password" class="block w-full rounded-md border-gray-300 focus:border-brand-500 sm:text-sm px-3 py-2 border">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="block w-full rounded-md border-gray-300 focus:border-brand-500 sm:text-sm px-3 py-2 border">
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit" class="bg-brand-600 text-white px-6 py-2 rounded-md hover:bg-brand-700 font-medium transition">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
