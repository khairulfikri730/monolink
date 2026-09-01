@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6 animate-fade-in">
    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
                <i data-lucide="users" class="w-4 h-4 text-blue-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Users</p>
        </div>
        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center mb-3">
                <i data-lucide="link" class="w-4 h-4 text-purple-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalLinks) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Links</p>
        </div>
        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center mb-3">
                <i data-lucide="eye" class="w-4 h-4 text-green-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalViews) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Profile Views</p>
        </div>
        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center mb-3">
                <i data-lucide="mouse-pointer-click" class="w-4 h-4 text-orange-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalClicks) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Link Clicks</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Administration</h3>
        <div class="grid sm:grid-cols-2 gap-4">
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-4 p-4 rounded-lg border border-gray-200 hover:border-brand-500 hover:bg-brand-50 transition group">
                <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center group-hover:bg-brand-200 transition">
                    <i data-lucide="users" class="w-5 h-5 text-brand-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900 group-hover:text-brand-700">Manage Users</p>
                    <p class="text-xs text-gray-500 mt-0.5">View, edit, or disable user accounts</p>
                </div>
            </a>
            
            <a href="{{ route('admin.users.create') }}" class="flex items-center gap-4 p-4 rounded-lg border border-gray-200 hover:border-brand-500 hover:bg-brand-50 transition group">
                <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center group-hover:bg-brand-200 transition">
                    <i data-lucide="user-plus" class="w-5 h-5 text-brand-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900 group-hover:text-brand-700">Add New User</p>
                    <p class="text-xs text-gray-500 mt-0.5">Manually create a new account</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-800">Recently Registered</h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs text-brand-600 font-medium hover:underline">View All</a>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($recentUsers as $user)
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ $user->profile?->profile_image_url }}" class="w-8 h-8 rounded-full border border-gray-200">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="text-xs text-gray-400">
                    {{ $user->created_at->diffForHumans() }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
