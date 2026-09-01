@extends('layouts.app')
@section('title', 'Overview')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Welcome -->
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Welcome back 👋
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Your profile at 
                <a href="{{ route('public.profile', $profile->username) }}" target="_blank" class="text-brand-600 hover:underline font-medium">
                    {{ request()->getHost() }}/{{ $profile->username }}
                </a>
            </p>
        </div>
        <a href="{{ route('public.profile', $profile->username) }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50 bg-white shadow-sm transition">
            <i data-lucide="share-2" class="w-4 h-4"></i> View Profile
        </a>
    </div>

    <!-- Profile incomplete banner -->
    @if(!$isProfileComplete)
    <div class="bg-brand-50 border border-brand-200 rounded-xl p-4 flex items-center justify-between gap-3 flex-wrap">
        <div>
            <p class="text-brand-800 font-medium text-sm">
                Complete your profile to get started
            </p>
            <p class="text-brand-600 text-xs mt-0.5">
                Add a bio, profile photo, and your first link.
            </p>
        </div>
        <a href="{{ route('dashboard.profile') }}" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 text-sm font-medium bg-brand-600 text-white rounded-lg hover:bg-brand-700 shadow-sm transition">
            Set up Profile
        </a>
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
                <i data-lucide="eye" class="w-4 h-4 text-blue-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($profileViews) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Profile Views</p>
        </div>
        
        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition">
            <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center mb-3">
                <i data-lucide="mouse-pointer-click" class="w-4 h-4 text-purple-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($linkClicks) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Link Clicks</p>
        </div>
        
        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition">
            <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center mb-3">
                <i data-lucide="trending-up" class="w-4 h-4 text-green-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $ctr }}%</p>
            <p class="text-xs text-gray-500 mt-0.5">Click-Through Rate</p>
        </div>
        
        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition">
            <div class="w-9 h-9 rounded-xl bg-brand-50 flex items-center justify-center mb-3">
                <i data-lucide="link-2" class="w-4 h-4 text-brand-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $profile->links_count }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Links</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid sm:grid-cols-3 gap-4">
        <a href="{{ route('dashboard.profile') }}" class="block p-5 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition hover:-translate-y-0.5 group">
            <span class="text-2xl mb-2 block">👤</span>
            <div class="flex items-center justify-between">
                <p class="font-semibold text-gray-800 text-sm">Edit Profile</p>
                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 group-hover:text-brand-600 transition"></i>
            </div>
            <p class="text-xs text-gray-400 mt-1">Update your info and photo</p>
        </a>
        
        <a href="{{ route('dashboard.links') }}" class="block p-5 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition hover:-translate-y-0.5 group">
            <span class="text-2xl mb-2 block">🔗</span>
            <div class="flex items-center justify-between">
                <p class="font-semibold text-gray-800 text-sm">Manage Links</p>
                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 group-hover:text-brand-600 transition"></i>
            </div>
            <p class="text-xs text-gray-400 mt-1">Add or reorder your links</p>
        </a>
        
        <a href="{{ route('dashboard.appearance') }}" class="block p-5 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition hover:-translate-y-0.5 group">
            <span class="text-2xl mb-2 block">🎨</span>
            <div class="flex items-center justify-between">
                <p class="font-semibold text-gray-800 text-sm">Customize Look</p>
                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 group-hover:text-brand-600 transition"></i>
            </div>
            <p class="text-xs text-gray-400 mt-1">Change colors and style</p>
        </a>
    </div>

    <!-- Recent Links -->
    @if($activeLinks->count() > 0)
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-gray-800 text-base">Active Links</h2>
            <a href="{{ route('dashboard.links') }}" class="text-xs text-brand-600 hover:text-brand-700 font-medium">
                Manage all →
            </a>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            @foreach($activeLinks as $index => $link)
            <div class="flex items-center gap-3 px-4 py-3 {{ $index < $activeLinks->count() - 1 ? 'border-b border-gray-100' : '' }}">
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="link-2" class="w-3.5 h-3.5 text-gray-500"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $link->title }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $link->url }}</p>
                </div>
                <span class="px-2 py-1 bg-green-50 text-green-700 border border-green-200 rounded-md text-xs font-medium flex-shrink-0">
                    Active
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
