@extends('layouts.app')
@section('title', 'Manage Links')

@section('content')
<div class="space-y-6 animate-fade-in max-w-3xl mx-auto">

    <!-- Add New — Tabs Linktree Style -->
    <div class="bg-white border border-gray-200/70 rounded-[16px] shadow-sm overflow-hidden" x-data="{ tab: 'link' }">
        <div class="flex border-b border-gray-100">
            <button type="button" @click="tab='link'" :class="tab==='link' ? 'text-gray-900 border-gray-900 bg-gray-50' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50'" class="flex-1 flex items-center justify-center gap-2 px-4 py-3.5 text-sm font-semibold border-b-2 transition">
                <i data-lucide="link-2" class="w-4 h-4"></i> Link Biasa
            </button>
            <button type="button" @click="tab='maps'" :class="tab==='maps' ? 'text-gray-900 border-gray-900 bg-gray-50' : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50'" class="flex-1 flex items-center justify-center gap-2 px-4 py-3.5 text-sm font-semibold border-b-2 transition">
                <i data-lucide="map-pin" class="w-4 h-4"></i> Lokasi Maps
            </button>
        </div>

        <div class="p-5 sm:p-6">
            {{-- Tab Link --}}
            <div x-show="tab==='link'" x-transition>
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center"><i data-lucide="plus" class="w-4 h-4"></i></span>
                    <div>
                        <h3 class="text-[15px] font-semibold text-gray-900 tracking-tight leading-none">Tambah Link Baru</h3>
                        <p class="text-xs text-gray-500 mt-1">Instagram, TikTok, Website, Marketplace, dll</p>
                    </div>
                </div>
                <form action="{{ route('dashboard.links.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="CUSTOM">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Judul Link</label>
                            <input type="text" name="title" required placeholder="e.g. Toko Shopee Saya" class="block w-full rounded-xl border-gray-200 focus:border-brand-500 focus:ring-brand-500 text-sm px-3.5 py-2.5 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1.5">URL</label>
                            <input type="url" name="url" required placeholder="https://" class="block w-full rounded-xl border-gray-200 focus:border-brand-500 focus:ring-brand-500 text-sm px-3.5 py-2.5 border shadow-sm">
                        </div>
                    </div>
                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl border border-transparent bg-gray-900 py-3 px-4 text-sm font-semibold text-white shadow-sm hover:bg-black transition">
                        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Link
                    </button>
                </form>
            </div>

            {{-- Tab Maps --}}
            <div x-show="tab==='maps'" x-transition x-cloak>
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center"><i data-lucide="map-pin" class="w-4 h-4"></i></span>
                    <div>
                        <h3 class="text-[15px] font-semibold text-gray-900 tracking-tight leading-none">Tambah Lokasi Maps</h3>
                        <p class="text-xs text-gray-500 mt-1">Copy link dari Google Maps → paste di sini → otomatis jadi peta</p>
                    </div>
                </div>
                <form action="{{ route('dashboard.links.store') }}" method="POST" class="space-y-4" id="maps-form">
                    @csrf
                    <input type="hidden" name="type" value="GOOGLE_MAPS">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Judul Lokasi</label>
                        <input type="text" name="title" required placeholder="e.g. Toko Monolink - Padang" class="block w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-3.5 py-2.5 border shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Link Google Maps <span class="normal-case font-normal tracking-normal text-gray-400">— tinggal paste</span></label>
                        <input type="url" name="url" id="maps-url" required placeholder="https://maps.app.goo.gl/…  atau  https://www.google.com/maps/place/…" class="block w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-3.5 py-2.5 border shadow-sm">
                        <div id="maps-detect" class="hidden mt-2 inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1.5 rounded-full">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Link Maps terdeteksi — akan tampil sebagai peta
                        </div>
                        <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                            Buka <b>Google Maps</b> → Cari lokasi → <b>Share / Bagikan</b> → <b>Copy link</b> → paste di atas. Mendukung <code class="bg-gray-100 px-1 py-0.5 rounded text-gray-600">maps.app.goo.gl</code>, <code class="bg-gray-100 px-1 py-0.5 rounded">goo.gl/maps</code>, <code class="bg-gray-100 px-1 py-0.5 rounded">google.com/maps</code>.
                        </p>
                    </div>
                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl border border-transparent bg-emerald-600 py-3 px-4 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition">
                        <i data-lucide="map-pin" class="w-4 h-4"></i> Tambah Peta
                    </button>
                </form>
                <div class="mt-4 p-3 rounded-xl bg-amber-50 border border-amber-200 flex gap-2.5">
                    <i data-lucide="lightbulb" class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-amber-800 leading-relaxed"><b>Tips:</b> Link pendek <code>maps.app.goo.gl</code> tetap bisa dipaste — otomatis jadi embed peta profesional di profile kamu.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Links List — Linktree style -->
    <div class="bg-white border border-gray-200/70 rounded-[16px] shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-gray-100 bg-gray-50/70 flex justify-between items-center gap-3">
            <div class="flex items-center gap-2">
                <h3 class="text-sm font-semibold text-gray-900">Koleksi Link Kamu</h3>
                <span class="text-xs bg-white border border-gray-200 px-2 py-1 rounded-full font-medium">{{ $links->count() }} item</span>
            </div>
            <span class="text-xs text-gray-500 hidden sm:inline-flex items-center gap-1"><i data-lucide="grip-vertical" class="w-3 h-3"></i> Drag untuk urutkan</span>
        </div>
        
        @if($links->isEmpty())
        <div class="p-10 text-center">
            <span class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-gray-100 flex items-center justify-center"><i data-lucide="link-2" class="w-6 h-6 text-gray-400"></i></span>
            <p class="text-sm font-medium text-gray-700">Belum ada link</p>
            <p class="text-xs text-gray-400 mt-1">Tambah link atau lokasi Maps di atas</p>
        </div>
        @else
        <div id="sortable-links" class="divide-y divide-gray-100">
            @foreach($links as $link)
            @php $isMaps = in_array(strtoupper($link->type ?? ''), ['GOOGLE_MAPS','MAP']); @endphp
            <div x-data="{ edit: false }" class="bg-white hover:bg-gray-50/70 transition" data-id="{{ $link->id }}">
                {{-- Baris tampil --}}
                <div x-show="!edit" class="p-4 flex items-center gap-3 sm:gap-4">
                    <i data-lucide="grip-vertical" class="w-4 h-4 text-gray-300 flex-shrink-0 cursor-grab active:cursor-grabbing group-hover:text-gray-400"></i>

                    <span class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 border shadow-sm {{ $isMaps ? 'bg-emerald-50 border-emerald-200 text-emerald-600' : 'bg-gray-50 border-gray-200 text-gray-500' }}">
                        @if($isMaps)
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                        @else
                            <i data-lucide="link-2" class="w-4 h-4"></i>
                        @endif
                    </span>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $link->title }}</p>
                            @if($isMaps)
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold tracking-widest uppercase text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">MAP</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 truncate">{{ $link->url }}</p>
                    </div>

                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <button type="button" @click="edit = true" class="p-2 text-gray-400 hover:text-indigo-600 rounded-xl hover:bg-indigo-50 transition" title="Edit link">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </button>
                        <form action="{{ route('dashboard.links.toggle', $link) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="relative inline-flex h-6 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $link->is_active ? 'bg-gray-900' : 'bg-gray-200' }}" title="{{ $link->is_active ? 'Aktif' : 'Nonaktif' }}">
                                <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $link->is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                            </button>
                        </form>

                        <form action="{{ route('dashboard.links.destroy', $link) }}" method="POST" onsubmit="return confirm('Hapus {{ $isMaps ? 'lokasi' : 'link' }} ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 rounded-xl hover:bg-red-50 transition">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Form edit inline --}}
                <form x-show="edit" x-cloak x-transition.action.opacity.duration.200ms
                      action="{{ route('dashboard.links.update', $link) }}" method="POST"
                      class="p-4 space-y-3 border-l-4 border-indigo-500 bg-indigo-50/40">
                    @csrf @method('PATCH')
                    <input type="hidden" name="type" value="{{ $link->type }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Judul</label>
                            <input type="text" name="title" value="{{ $link->title }}" required
                                   class="block w-full rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3.5 py-2.5 shadow-sm bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1.5">URL</label>
                            <input type="url" name="url" value="{{ $link->url }}" required
                                   class="block w-full rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3.5 py-2.5 shadow-sm bg-white">
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-500">
                        {{ $isMaps ? 'Link Maps pendek (maps.app.goo.gl) akan otomatis diperpanjang saat disimpan.' : '' }}
                    </p>
                    <div class="flex items-center gap-2 justify-end">
                        <button type="button" @click="edit = false" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                            <i data-lucide="check" class="w-4 h-4"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    
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
                animation: 180,
                handle: '.cursor-grab',
                ghostClass: 'opacity-40',
                onEnd: function () {
                    var items = el.querySelectorAll('[data-id]');
                    var ids = Array.from(items).map(item => item.getAttribute('data-id'));
                    document.getElementById('ordered_ids').value = ids.join(',');
                    document.getElementById('reorder-form').submit();
                }
            });
        }
        var mapsInput = document.getElementById('maps-url');
        var detect = document.getElementById('maps-detect');
        if (mapsInput && detect) {
            mapsInput.addEventListener('input', function() {
                var v = this.value.toLowerCase();
                var isMaps = v.includes('google.com/maps') || v.includes('maps.google.') || v.includes('maps.app.goo.gl') || v.includes('goo.gl/maps');
                detect.classList.toggle('hidden', !isMaps);
                if (isMaps && window.lucide && lucide.icons) lucide.createIcons({icons: lucide.icons});
            });
        }
    });
</script>
@endpush
