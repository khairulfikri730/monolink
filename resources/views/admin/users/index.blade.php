@extends('layouts.app')
@section('title', 'Manage Users')

@section('content')
<div class="space-y-6 animate-fade-in">
    
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">All Users</h2>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-brand-600 text-white px-4 py-2 rounded-lg hover:bg-brand-700 transition font-medium text-sm">
            <i data-lucide="plus" class="w-4 h-4"></i> Add User
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">User</th>
                        <th scope="col" class="px-6 py-3">Username (BioLink)</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Joined</th>
                        <th scope="col" class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="bg-white border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->profile?->profile_image_url }}" class="w-8 h-8 rounded-full border border-gray-200 object-cover">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->profile)
                                <a href="{{ route('public.profile', $user->profile->username) }}" target="_blank" class="text-brand-600 hover:underline">
                                    {{ $user->profile->username }}
                                </a>
                            @else
                                <span class="text-gray-400 italic">No profile</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($user->isAdmin())
                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded border border-purple-200">Admin</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-200">User</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($user->isActive())
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200 flex items-center gap-1 w-max">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Active
                                </span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-200 flex items-center gap-1 w-max">
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-gray-500 hover:text-brand-600 transition" title="Edit User">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.status', $user) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-gray-500 hover:text-amber-600 transition" title="{{ $user->isActive() ? 'Deactivate' : 'Activate' }}">
                                            <i data-lucide="{{ $user->isActive() ? 'pause-circle' : 'play-circle' }}" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to completely delete this user and all their links? This action cannot be undone.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-600 transition" title="Delete User">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="p-4 border-t border-gray-200 bg-gray-50">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
