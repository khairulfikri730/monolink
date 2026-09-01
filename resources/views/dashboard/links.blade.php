@extends('layouts.app')
@section('title', 'Manage Links')

@section('content')
<div class="space-y-6 animate-fade-in">

    <!-- Add New Link -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Add New Link</h3>
        <form action="{{ route('dashboard.links.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" required placeholder="e.g. My Website" class="block w-full rounded-md border-gray-300 focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                    <input type="url" name="url" required placeholder="https://" class="block w-full rounded-md border-gray-300 focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border">
                </div>
            </div>
            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent bg-brand-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-brand-700 transition">
                Add Link
            </button>
        </form>
    </div>

    <!-- Links List -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-800">Your Links</h3>
            <span class="text-xs text-gray-500">Drag to reorder</span>
        </div>
        
        @if($links->isEmpty())
        <div class="p-8 text-center text-gray-500">
            <i data-lucide="link" class="w-8 h-8 mx-auto mb-2 text-gray-400"></i>
            <p>You haven't added any links yet.</p>
        </div>
        @else
        <div id="sortable-links" class="divide-y divide-gray-100">
            @foreach($links as $link)
            <div class="p-4 flex items-center gap-4 bg-white hover:bg-gray-50 transition cursor-move" data-id="{{ $link->id }}">
                <i data-lucide="grip-vertical" class="w-5 h-5 text-gray-400 flex-shrink-0 cursor-grab active:cursor-grabbing"></i>
                
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $link->title }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $link->url }}</p>
                </div>
                
                <div class="flex items-center gap-2 flex-shrink-0">
                    <!-- Toggle Status -->
                    <form action="{{ route('dashboard.links.toggle', $link) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $link->is_active ? 'bg-brand-600' : 'bg-gray-200' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $link->is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                        </button>
                    </form>
                    
                    <!-- Delete Link -->
                    <form action="{{ route('dashboard.links.destroy', $link) }}" method="POST" onsubmit="return confirm('Delete this link?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-md hover:bg-red-50 transition">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    
    <!-- Hidden form for sortable -->
    <form id="reorder-form" action="{{ route('dashboard.links.reorder') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="ordered_ids" id="ordered_ids">
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var el = document.getElementById('sortable-links');
        if(el) {
            new Sortable(el, {
                animation: 150,
                handle: '.cursor-grab',
                onEnd: function (evt) {
                    var items = el.querySelectorAll('[data-id]');
                    var ids = Array.from(items).map(item => item.getAttribute('data-id'));
                    document.getElementById('ordered_ids').value = ids.join(',');
                    document.getElementById('reorder-form').submit();
                }
            });
        }
    });
</script>
@endpush
