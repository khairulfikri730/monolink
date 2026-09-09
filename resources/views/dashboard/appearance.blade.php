@extends('layouts.app')
@section('title', 'Appearance')

@section('content')
<div class="flex flex-col lg:flex-row gap-6 lg:gap-7 animate-fade-in items-start">

    {{-- LEFT: Settings Form --}}
    <div class="flex-1 min-w-0 space-y-5 w-full">

        {{-- Toast --}}
        <div id="save-toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 lg:left-auto lg:right-6 lg:translate-x-0 bg-gray-900 text-white px-5 py-3 rounded-2xl shadow-xl flex items-center gap-3 opacity-0 pointer-events-none transition-all duration-300 z-50 transform translate-y-4">
            <span class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i></span>
            <div>
                <p class="text-sm font-semibold leading-none">Tersimpan</p>
                <p class="text-xs opacity-70">Tampilan berhasil diperbarui</p>
            </div>
        </div>
        <div id="save-error" class="fixed bottom-6 left-1/2 -translate-x-1/2 lg:left-auto lg:right-6 lg:translate-x-0 bg-red-600 text-white px-5 py-3 rounded-2xl shadow-xl flex items-center gap-3 opacity-0 pointer-events-none transition-all duration-300 z-50 transform translate-y-4">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            <span class="text-sm font-medium">Gagal menyimpan, coba lagi</span>
        </div>

        {{-- Mobile Preview Toggle --}}
        <div class="lg:hidden">
            <button type="button" onclick="toggleMobilePreview()" id="mobile-preview-btn" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium shadow-sm hover:bg-gray-50 transition">
                <i data-lucide="eye" class="w-4 h-4"></i> Lihat Preview
            </button>
            <div id="mobile-preview-panel" class="hidden mt-4">
                <div class="mx-auto relative bg-white rounded-[2rem] border-[10px] border-zinc-900 shadow-2xl overflow-hidden" style="width:300px; height:560px">
                    <div class="absolute top-2 left-1/2 -translate-x-1/2 w-20 h-5 bg-zinc-900 rounded-full z-20"></div>
                    <iframe id="preview-frame-mobile" src="{{ route('public.profile', $profile->username) }}" class="w-full h-full border-0 bg-gray-50"></iframe>
                </div>
            </div>
        </div>

        <form action="{{ route('dashboard.appearance.update') }}" method="POST" enctype="multipart/form-data" id="appearance-form" class="space-y-5">
            @csrf

            {{-- ── TEMPLATES ── --}}
            <div class="bg-white rounded-[16px] shadow-sm border border-gray-200/70 p-5 sm:p-6 space-y-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-violet-50 border border-violet-100 text-violet-600 flex items-center justify-center flex-shrink-0"><i data-lucide="sparkles" class="w-4.5 h-4.5"></i></span>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-[15px] leading-none tracking-tight">Pre-made Templates</h3>
                            <p class="text-xs text-gray-500 mt-1">Pilih template profesional secara instan</p>
                        </div>
                    </div>
                    <span class="hidden sm:inline-flex text-[11px] font-medium tracking-widest uppercase text-gray-400 bg-gray-50 border border-gray-200 px-2.5 py-1 rounded-full">8 Templates</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3.5">
                    <button type="button" onclick="applyTemplate('linktree-navy')" data-template="linktree-navy" class="template-card group flex flex-col gap-2.5 p-3 rounded-2xl border-2 border-indigo-500 bg-indigo-50/50 transition-all text-left">
                        <div class="w-full h-[72px] rounded-xl flex flex-col items-center justify-center gap-1.5 shadow-sm border" style="background:#071A8C; border-color: rgba(255,255,255,0.22)">
                            <div class="w-10 h-10 rounded-full bg-white/15 border border-white/40"></div>
                            <div class="w-16 h-3.5 rounded-full border border-white bg-transparent"></div>
                            <div class="w-20 h-2 bg-white/50 rounded-full"></div>
                        </div>
                        <span class="text-xs font-semibold text-indigo-700 leading-tight">Linktree Navy ★</span>
                        <span class="text-[11px] text-gray-400">#071A8C • Outline 28px</span>
                    </button>
                    <button type="button" onclick="applyTemplate('minimalist-light')" data-template="minimalist-light" class="template-card group flex flex-col gap-2.5 p-3 rounded-2xl border-2 border-gray-100 hover:border-indigo-400 hover:bg-indigo-50/50 transition-all text-left">
                        <div class="w-full h-[72px] rounded-xl bg-white border border-gray-200 flex flex-col items-center justify-center gap-1.5 shadow-sm overflow-hidden">
                            <div class="w-10 h-10 rounded-full bg-gray-900/5 border border-gray-200"></div>
                            <div class="w-16 h-3.5 bg-gray-900 rounded-full"></div>
                            <div class="w-20 h-2 bg-gray-100 rounded-full"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-indigo-700 leading-tight">Minimalist Light</span>
                        <span class="text-[11px] text-gray-400">Clean • White</span>
                    </button>
                    <button type="button" onclick="applyTemplate('minimalist-dark')" data-template="minimalist-dark" class="template-card group flex flex-col gap-2.5 p-3 rounded-2xl border-2 border-gray-100 hover:border-indigo-400 hover:bg-indigo-50/50 transition-all text-left">
                        <div class="w-full h-[72px] rounded-xl bg-zinc-900 border border-zinc-800 flex flex-col items-center justify-center gap-1.5 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-white/10 border border-white/20"></div>
                            <div class="w-16 h-3.5 bg-white rounded-full"></div>
                            <div class="w-20 h-2 bg-zinc-700 rounded-full"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-indigo-700 leading-tight">Minimalist Dark</span>
                        <span class="text-[11px] text-gray-400">Bold • Dark</span>
                    </button>
                    <button type="button" onclick="applyTemplate('elegant-rose')" data-template="elegant-rose" class="template-card group flex flex-col gap-2.5 p-3 rounded-2xl border-2 border-gray-100 hover:border-indigo-400 hover:bg-indigo-50/50 transition-all text-left">
                        <div class="w-full h-[72px] rounded-xl bg-gradient-to-br from-rose-50 via-pink-100 to-rose-100 border border-pink-200 flex flex-col items-center justify-center gap-1.5 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-white border border-pink-200 shadow-sm"></div>
                            <div class="w-16 h-3.5 bg-rose-600 rounded-full"></div>
                            <div class="w-20 h-2 bg-rose-200 rounded-full"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-indigo-700 leading-tight">Elegant Rose</span>
                        <span class="text-[11px] text-gray-400">Soft • Feminine</span>
                    </button>
                    <button type="button" onclick="applyTemplate('neon-cyber')" data-template="neon-cyber" class="template-card group flex flex-col gap-2.5 p-3 rounded-2xl border-2 border-gray-100 hover:border-indigo-400 hover:bg-indigo-50/50 transition-all text-left">
                        <div class="w-full h-[72px] rounded-xl bg-slate-950 border border-slate-800 flex flex-col items-center justify-center gap-1.5 shadow-sm relative overflow-hidden">
                            <div class="absolute inset-0 bg-cyan-500/10"></div>
                            <div class="relative w-10 h-10 rounded-full bg-slate-800 border border-cyan-400/50" style="box-shadow: 0 0 10px #22d3ee;"></div>
                            <div class="relative w-16 h-3.5 bg-transparent border border-cyan-400 rounded-full" style="box-shadow: 0 0 8px #22d3ee;"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-indigo-700 leading-tight">Neon Cyberpunk</span>
                        <span class="text-[11px] text-gray-400">Futurist • Dark</span>
                    </button>
                    <button type="button" onclick="applyTemplate('business-slate')" data-template="business-slate" class="template-card group flex flex-col gap-2.5 p-3 rounded-2xl border-2 border-gray-100 hover:border-indigo-400 hover:bg-indigo-50/50 transition-all text-left">
                        <div class="w-full h-[72px] rounded-xl bg-slate-50 border border-slate-200 flex flex-col items-center justify-center gap-1.5 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-slate-900"></div>
                            <div class="w-16 h-3.5 bg-slate-900 rounded-full"></div>
                            <div class="w-20 h-2 bg-slate-300 rounded-full"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-indigo-700 leading-tight">Business Slate</span>
                        <span class="text-[11px] text-gray-400">Corporate • Trust</span>
                    </button>
                    <button type="button" onclick="applyTemplate('creator-gradient')" data-template="creator-gradient" class="template-card group flex flex-col gap-2.5 p-3 rounded-2xl border-2 border-gray-100 hover:border-indigo-400 hover:bg-indigo-50/50 transition-all text-left">
                        <div class="w-full h-[72px] rounded-xl bg-gradient-to-br from-violet-600 via-indigo-600 to-fuchsia-500 border border-violet-300 flex flex-col items-center justify-center gap-1.5 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-white/90 border border-white"></div>
                            <div class="w-16 h-3.5 bg-white rounded-full"></div>
                            <div class="w-20 h-2 bg-white/60 rounded-full"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-indigo-700 leading-tight">Creator Gradient</span>
                        <span class="text-[11px] text-gray-400">Vibrant • Pop</span>
                    </button>
                    <button type="button" onclick="applyTemplate('midnight-glass')" data-template="midnight-glass" class="template-card group flex flex-col gap-2.5 p-3 rounded-2xl border-2 border-gray-100 hover:border-indigo-400 hover:bg-indigo-50/50 transition-all text-left">
                        <div class="w-full h-[72px] rounded-xl bg-gradient-to-br from-slate-900 via-slate-800 to-zinc-900 border border-slate-700 flex flex-col items-center justify-center gap-1.5 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-white/15 backdrop-blur border border-white/30"></div>
                            <div class="w-16 h-3.5 bg-white/80 backdrop-blur rounded-full"></div>
                            <div class="w-20 h-2 bg-white/20 rounded-full"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-indigo-700 leading-tight">Midnight Glass</span>
                        <span class="text-[11px] text-gray-400">Premium • Glass</span>
                    </button>
                </div>
            </div>

            {{-- ── BACKGROUND ── --}}
            <div class="bg-white rounded-[16px] shadow-sm border border-gray-200/70 p-5 sm:p-6 space-y-5">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0"><i data-lucide="palette" class="w-4 h-4"></i></span>
                    <h3 class="font-semibold text-gray-900 text-[15px] tracking-tight">Background</h3>
                </div>

                <div class="flex gap-2 flex-wrap">
                    @foreach(['SOLID' => 'Solid Color', 'GRADIENT' => 'Gradient', 'IMAGE' => 'Image'] as $val => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="background_type" value="{{ $val }}"
                            {{ ($theme->background_type ?? 'SOLID') === $val ? 'checked' : '' }}
                            class="sr-only peer auto-save" onchange="switchBgType('{{ $val }}')">
                        <span class="px-4 py-2.5 rounded-xl border-2 text-sm font-medium transition-all
                            peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700
                            border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50 block">
                            {{ $label }}
                        </span>
                    </label>
                    @endforeach
                </div>

                {{-- SOLID --}}
                <div id="bg-solid" class="space-y-3">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="solid_color_picker" value="{{ ($theme->background_type ?? 'SOLID') === 'SOLID' ? ($theme->background_value ?? '#f8fafc') : '#f8fafc' }}"
                            class="w-12 h-10 cursor-pointer rounded-xl border border-gray-200 p-1 bg-white shadow-sm"
                            oninput="document.getElementById('solid_color_hex').value=this.value; document.getElementById('bg_value_solid').value=this.value; triggerAutoSave()">
                        <input type="text" id="solid_color_hex" placeholder="#f8fafc"
                            value="{{ ($theme->background_type ?? 'SOLID') === 'SOLID' ? ($theme->background_value ?? '#f8fafc') : '#f8fafc' }}"
                            class="flex-1 rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition"
                            oninput="syncColorPicker('solid_color_picker', this.value); document.getElementById('bg_value_solid').value=this.value; triggerAutoSave()">
                        <input type="hidden" name="background_value" id="bg_value_solid" value="{{ ($theme->background_type ?? 'SOLID') === 'SOLID' ? ($theme->background_value ?? '#f8fafc') : '#f8fafc' }}">
                    </div>
                    <div class="flex gap-2 flex-wrap pt-1">
                        @foreach(['#ffffff','#f8fafc','#f1f5f9','#1e1b4b','#0f172a','#faf5ff','#fff7ed','#f0fdf4','#fef2f2','#e0f2fe','#0ea5e9','#6366f1'] as $preset)
                        <button type="button" onclick="setSolidColor('{{ $preset }}')"
                            class="w-8 h-8 rounded-full border-2 border-white shadow-sm hover:scale-110 hover:shadow-md transition-all ring-1 ring-gray-200"
                            style="background:{{ $preset }}" title="{{ $preset }}" aria-label="{{ $preset }}"></button>
                        @endforeach
                    </div>
                </div>

                {{-- GRADIENT --}}
                <div id="bg-gradient" class="space-y-4 hidden">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Gradient Colors</label>
                        <button type="button" onclick="addGradientStop()" class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-full transition">
                            <i data-lucide="plus" class="w-3 h-3"></i> Add Color
                        </button>
                    </div>
                    <div id="gradient-stops" class="space-y-2.5"></div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2.5">Direction</label>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach([
                                'to right' => '→', 'to left' => '←',
                                'to bottom' => '↓', 'to top' => '↑',
                                'to bottom right' => '↘', 'to bottom left' => '↙',
                                'to top right' => '↗', 'to top left' => '↖',
                            ] as $dir => $arrow)
                            <label class="cursor-pointer text-center">
                                <input type="radio" name="gradient_direction" value="{{ $dir }}"
                                    {{ ($theme->gradient_direction ?? 'to bottom right') === $dir ? 'checked' : '' }}
                                    class="sr-only peer auto-save">
                                <span class="block py-2.5 rounded-xl border-2 text-base font-medium transition-all
                                    peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700
                                    border-gray-200 hover:border-gray-300 hover:bg-gray-50 bg-white">{{ $arrow }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div id="gradient-preview-bar" class="h-11 rounded-xl border border-gray-200 shadow-inner transition-all"></div>
                </div>

                {{-- IMAGE --}}
                <div id="bg-image" class="space-y-4 hidden">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2.5">Upload Image</label>
                        <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/50 transition-all relative overflow-hidden group bg-gray-50/50">
                            <div class="text-center relative z-10 p-4" id="upload-label-content">
                                <span class="w-10 h-10 mx-auto mb-2 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm group-hover:border-indigo-200 transition"><i data-lucide="image" class="w-5 h-5 text-gray-400"></i></span>
                                <p class="text-sm font-medium text-gray-700">Click to upload</p>
                                <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP, GIF · Max 2MB</p>
                            </div>
                            <input type="file" name="background_image_file" class="hidden" accept="image/jpeg,image/png,image/webp,image/gif" onchange="previewBgImage(this)">
                        </label>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">Or paste Image URL</label>
                        <input type="url" name="background_image_url" placeholder="https://..."
                            value="{{ ($theme->background_type ?? '') === 'IMAGE' && !($theme->background_image ?? null) ? ($theme->background_value ?? '') : '' }}"
                            class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition auto-save-input">
                    </div>
                    @if(($theme->background_type ?? '') === 'IMAGE' && ($theme->background_image ?? null))
                    <div class="relative rounded-2xl overflow-hidden h-28 border border-gray-200 shadow-sm">
                        <img src="{{ asset('storage/' . $theme->background_image) }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent flex items-end justify-center pb-3">
                            <span class="text-white text-xs font-medium bg-black/30 backdrop-blur px-3 py-1 rounded-full">Current Image</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── COLORS ── --}}
            <div class="bg-white rounded-[16px] shadow-sm border border-gray-200/70 p-5 sm:p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-pink-50 border border-pink-100 text-pink-600 flex items-center justify-center flex-shrink-0"><i data-lucide="paintbrush" class="w-4 h-4"></i></span>
                    <h3 class="font-semibold text-gray-900 text-[15px] tracking-tight">Colors</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach([
                        ['text_color', 'Text Color', $theme->text_color ?? '#1e293b'],
                        ['primary_color', 'Accent Color', $theme->primary_color ?? '#6366f1'],
                        ['button_color', 'Button Color', $theme->button_color ?? '#6366f1'],
                        ['button_text_color', 'Button Text', $theme->button_text_color ?? '#ffffff'],
                    ] as [$name, $label, $val])
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest">{{ $label }}</label>
                        <div class="flex items-center gap-2.5">
                            <input type="color" id="picker_{{ $name }}" name="{{ $name }}" value="{{ old($name, $val) }}"
                                class="w-11 h-10 rounded-xl border border-gray-200 p-1 cursor-pointer bg-white shadow-sm auto-save-input"
                                oninput="document.getElementById('hex_{{ $name }}').value=this.value; triggerAutoSave()">
                            <input type="text" id="hex_{{ $name }}" value="{{ old($name, $val) }}"
                                class="flex-1 rounded-xl border border-gray-200 px-3 py-2.5 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition auto-save-input"
                                oninput="syncColorPicker('picker_{{ $name }}', this.value); triggerAutoSave()"
                                placeholder="#000000">
                        </div>
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="secondary_color" value="{{ $theme->secondary_color ?? '#a5b4fc' }}">
            </div>

            {{-- ── TYPOGRAPHY ── --}}
            <div class="bg-white rounded-[16px] shadow-sm border border-gray-200/70 p-5 sm:p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0"><i data-lucide="type" class="w-4 h-4"></i></span>
                    <h3 class="font-semibold text-gray-900 text-[15px] tracking-tight">Typography</h3>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2.5">Font Family</label>
                    <div class="grid grid-cols-2 gap-2.5">
                        @foreach(['Inter','Poppins','Roboto','Playfair Display','Merriweather','Space Mono','Nunito','Lato'] as $font)
                        <label class="cursor-pointer">
                            <input type="radio" name="font_family" value="{{ $font }}"
                                {{ ($theme->font_family ?? 'Inter') == $font ? 'checked' : '' }}
                                class="sr-only peer auto-save">
                            <span class="block px-3 py-2.5 rounded-xl border-2 text-sm transition-all text-center bg-white
                                peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 peer-checked:font-medium
                                border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50"
                                style="font-family:'{{ $font }}',sans-serif">{{ $font }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ── BUTTONS ── --}}
            <div class="bg-white rounded-[16px] shadow-sm border border-gray-200/70 p-5 sm:p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0"><i data-lucide="rectangle-horizontal" class="w-4 h-4"></i></span>
                    <h3 class="font-semibold text-gray-900 text-[15px] tracking-tight">Button Style</h3>
                </div>
                <div class="grid grid-cols-3 gap-2.5">
                    @foreach(['ROUNDED'=>'Rounded','PILL'=>'Pill','SQUARE'=>'Square','GLASS'=>'Glass','OUTLINE'=>'Outline','SHADOW'=>'Shadow','NEON'=>'Neon'] as $val => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="button_style" value="{{ $val }}"
                            {{ ($theme->button_style ?? 'ROUNDED') == $val ? 'checked' : '' }}
                            class="sr-only peer auto-save">
                        <span class="block px-3 py-3 rounded-xl border-2 text-xs font-medium text-center transition-all bg-white
                            peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700
                            border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- ── LAYOUT ── --}}
            <div class="bg-white rounded-[16px] shadow-sm border border-gray-200/70 p-5 sm:p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0"><i data-lucide="layout-grid" class="w-4 h-4"></i></span>
                    <h3 class="font-semibold text-gray-900 text-[15px] tracking-tight">Layout</h3>
                </div>
                <div class="grid grid-cols-3 gap-2.5">
                    @foreach(['center'=>'Center','left'=>'Left','right'=>'Right'] as $val => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="layout" value="{{ $val }}"
                            {{ strtolower(trim($theme->layout ?? 'center')) == $val ? 'checked' : '' }}
                            class="sr-only peer auto-save" onchange="triggerAutoSave()">
                        <span class="block px-3 py-3 rounded-xl border-2 text-sm font-medium text-center transition-all bg-white
                            peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700
                            border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

        </form>
    </div>

    {{-- RIGHT: Live Preview — tetap di kanan, tidak ikut scroll form --}}
    <div class="hidden lg:block w-[360px] flex-shrink-0 self-start sticky top-6 max-h-[calc(100vh-88px)]">
        <div class="max-h-[calc(100vh-88px)] overflow-y-auto pr-1 -mr-1 custom-scrollbar">
            <div class="flex items-center justify-center gap-2 mb-4">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Live Preview</span>
                <span id="sync-icon" class="hidden inline-flex items-center gap-1 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-full">
                    <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Saving
                </span>
                <span id="sync-saved" class="hidden inline-flex items-center gap-1 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-full">
                    <i data-lucide="check" class="w-3 h-3"></i> Saved
                </span>
            </div>
            <div class="mx-auto relative" style="width:300px">
                {{-- Phone frame --}}
                <div class="absolute -inset-[10px] border-[10px] border-zinc-900 rounded-[2.6rem] shadow-[0_20px_60px_rgba(0,0,0,0.22)] pointer-events-none z-10"></div>
                <div class="absolute top-2 left-1/2 -translate-x-1/2 w-20 h-5 bg-zinc-900 rounded-full z-20 pointer-events-none"></div>
                <div class="absolute bottom-1.5 left-1/2 -translate-x-1/2 w-28 h-1 bg-zinc-900 rounded-full z-20 pointer-events-none opacity-60"></div>
                <iframe id="preview-frame" src="{{ route('public.profile', $profile->username) }}"
                    class="w-full rounded-[2rem] border-0 bg-gray-50 shadow-inner" style="height:600px"></iframe>
            </div>
            <div class="mt-4 flex items-center justify-center gap-2">
                <a href="{{ route('public.profile', $profile->username) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-gray-700 bg-white border border-gray-200 px-3 py-1.5 rounded-full hover:bg-gray-50 transition">
                    <i data-lucide="external-link" class="w-3 h-3"></i> Open
                </a>
                <button type="button" onclick="reloadIframe()" class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-gray-700 bg-white border border-gray-200 px-3 py-1.5 rounded-full hover:bg-gray-50 transition">
                    <i data-lucide="refresh-cw" class="w-3 h-3"></i> Reload
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ── State ──
const initialType = '{{ $theme->background_type ?? "SOLID" }}';
let savedGradientColors = @json($theme->gradient_colors ?? ['#6366f1','#a855f7']);

// ── Templates (8 pro — Linktree Navy exact) ──
const templates = {
    'linktree-navy': {
        background_type: 'SOLID', background_value: '#071A8C',
        text_color: '#ffffff', primary_color: '#ffffff',
        button_color: '#ffffff', button_text_color: '#ffffff',
        font_family: 'Inter', button_style: 'OUTLINE', layout: 'center'
    },
    'minimalist-light': {
        background_type: 'SOLID', background_value: '#ffffff',
        text_color: '#111827', primary_color: '#111827',
        button_color: '#f3f4f6', button_text_color: '#111827',
        font_family: 'Inter', button_style: 'ROUNDED', layout: 'center'
    },
    'minimalist-dark': {
        background_type: 'SOLID', background_value: '#111827',
        text_color: '#f9fafb', primary_color: '#ffffff',
        button_color: '#374151', button_text_color: '#ffffff',
        font_family: 'Space Mono', button_style: 'ROUNDED', layout: 'center'
    },
    'elegant-rose': {
        background_type: 'GRADIENT', background_value: '',
        gradient_colors: ['#fff1f2', '#ffe4e6'], gradient_direction: 'to bottom right',
        text_color: '#881337', primary_color: '#e11d48',
        button_color: '#be123c', button_text_color: '#ffffff',
        font_family: 'Playfair Display', button_style: 'PILL', layout: 'center'
    },
    'neon-cyber': {
        background_type: 'SOLID', background_value: '#020617',
        text_color: '#e2e8f0', primary_color: '#06b6d4',
        button_color: '#0ea5e9', button_text_color: '#ffffff',
        font_family: 'Roboto', button_style: 'NEON', layout: 'center'
    },
    'business-slate': {
        background_type: 'SOLID', background_value: '#f8fafc',
        text_color: '#0f172a', primary_color: '#0f172a',
        button_color: '#0f172a', button_text_color: '#ffffff',
        font_family: 'Inter', button_style: 'ROUNDED', layout: 'center'
    },
    'creator-gradient': {
        background_type: 'GRADIENT', background_value: '',
        gradient_colors: ['#7c3aed','#ec4899'], gradient_direction: 'to bottom right',
        text_color: '#ffffff', primary_color: '#ffffff',
        button_color: '#ffffff', button_text_color: '#7c3aed',
        font_family: 'Poppins', button_style: 'PILL', layout: 'center'
    },
    'midnight-glass': {
        background_type: 'SOLID', background_value: '#0f172a',
        text_color: '#f1f5f9', primary_color: '#38bdf8',
        button_color: '#e2e8f0', button_text_color: '#0f172a',
        font_family: 'Inter', button_style: 'GLASS', layout: 'center'
    }
};

function applyTemplate(id) {
    const t = templates[id];
    if(!t) return;
    document.querySelector(`input[name="background_type"][value="${t.background_type}"]`).checked = true;
    switchBgType(t.background_type);

    if(t.background_type === 'SOLID') {
        setSolidColor(t.background_value);
    } else if (t.background_type === 'GRADIENT') {
        savedGradientColors = [...t.gradient_colors];
        document.querySelector(`input[name="gradient_direction"][value="${t.gradient_direction}"]`).checked = true;
        renderGradientStops();
    }

    ['text_color', 'primary_color', 'button_color', 'button_text_color'].forEach(key => {
        const picker = document.getElementById(`picker_${key}`);
        const hex = document.getElementById(`hex_${key}`);
        if(picker && hex) {
            picker.value = t[key];
            hex.value = t[key];
        }
    });

    document.querySelector(`input[name="font_family"][value="${t.font_family}"]`).checked = true;
    document.querySelector(`input[name="button_style"][value="${t.button_style}"]`).checked = true;
    document.querySelector(`input[name="layout"][value="${t.layout}"]`).checked = true;

    // highlight selected template
    document.querySelectorAll('.template-card').forEach(c=>c.classList.remove('border-indigo-500','bg-indigo-50/50'));
    const active = document.querySelector(`[data-template="${id}"]`);
    if(active) active.classList.add('border-indigo-500','bg-indigo-50/50');

    triggerAutoSave();
}

function toggleMobilePreview() {
    const panel = document.getElementById('mobile-preview-panel');
    const btn = document.getElementById('mobile-preview-btn');
    panel.classList.toggle('hidden');
    const isOpen = !panel.classList.contains('hidden');
    btn.innerHTML = isOpen ? '<i data-lucide="eye-off" class="w-4 h-4"></i> Tutup Preview' : '<i data-lucide="eye" class="w-4 h-4"></i> Lihat Preview';
    if(window.lucide && lucide.icons) lucide.createIcons({icons: lucide.icons});
    if(isOpen) {
        const src = document.getElementById('preview-frame')?.src;
        const mob = document.getElementById('preview-frame-mobile');
        if(mob && src) mob.src = src;
    }
}

// ── Background type switch ──
function switchBgType(type) {
    document.getElementById('bg-solid').classList.toggle('hidden', type !== 'SOLID');
    document.getElementById('bg-gradient').classList.toggle('hidden', type !== 'GRADIENT');
    document.getElementById('bg-image').classList.toggle('hidden', type !== 'IMAGE');
    if (type === 'GRADIENT') renderGradientBar();
    triggerAutoSave();
}

// ── Solid color sync ──
function syncColorPicker(pickerId, hex) {
    if (/^#[0-9a-fA-F]{6}$/.test(hex)) {
        document.getElementById(pickerId).value = hex;
    }
}
function setSolidColor(hex) {
    document.getElementById('solid_color_picker').value = hex;
    document.getElementById('solid_color_hex').value = hex;
    document.getElementById('bg_value_solid').value = hex;
    triggerAutoSave();
}

// ── Gradient stops ──
function renderGradientStops() {
    const container = document.getElementById('gradient-stops');
    container.innerHTML = '';
    savedGradientColors.forEach((color, i) => {
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2.5';
        div.innerHTML = `
            <input type="color" value="${color}" 
                class="w-11 h-10 rounded-xl border border-gray-200 p-1 cursor-pointer bg-white shadow-sm"
                oninput="updateGradientStop(${i}, this.value)">
            <input type="text" value="${color}" placeholder="#ffffff"
                class="flex-1 rounded-xl border border-gray-200 px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition"
                oninput="updateGradientStopHex(${i}, this.value)">
            <input type="hidden" name="gradient_colors[]" value="${color}" id="gc_${i}">
            ${savedGradientColors.length > 2 ? `<button type="button" onclick="removeGradientStop(${i})" class="w-8 h-8 flex items-center justify-center rounded-full bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-600 transition text-lg leading-none">×</button>` : ''}
        `;
        container.appendChild(div);
    });
    renderGradientBar();
}

function updateGradientStop(i, val) {
    savedGradientColors[i] = val;
    document.getElementById(`gc_${i}`).value = val;
    document.getElementById('gradient-stops').querySelectorAll('input[type=text]')[i].value = val;
    renderGradientBar();
    triggerAutoSave();
}
function updateGradientStopHex(i, val) {
    if (/^#[0-9a-fA-F]{6}$/.test(val)) {
        savedGradientColors[i] = val;
        document.getElementById(`gc_${i}`).value = val;
        document.getElementById('gradient-stops').querySelectorAll('input[type=color]')[i].value = val;
        renderGradientBar();
        triggerAutoSave();
    }
}
function addGradientStop() {
    savedGradientColors.push('#ffffff');
    renderGradientStops();
    triggerAutoSave();
}
function removeGradientStop(i) {
    savedGradientColors.splice(i, 1);
    renderGradientStops();
    triggerAutoSave();
}
function renderGradientBar() {
    const dir = document.querySelector('input[name="gradient_direction"]:checked')?.value || 'to bottom right';
    const bar = document.getElementById('gradient-preview-bar');
    if (bar) bar.style.backgroundImage = `linear-gradient(${dir}, ${savedGradientColors.join(',')})`;
}

// ── Image preview ──
function previewBgImage(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const label = document.getElementById('upload-label-content');
    const url = URL.createObjectURL(file);
    label.innerHTML = `<img src="${url}" class="h-full w-full object-cover rounded-xl absolute inset-0 opacity-60"><p class="relative text-indigo-700 font-semibold z-20 bg-white/80 px-3 py-1 rounded-full text-xs">Siap di-upload...</p>`;
    triggerAutoSave();
}

// ── Auto Save Logic ──
let saveTimeout;
function triggerAutoSave() {
    clearTimeout(saveTimeout);
    document.getElementById('sync-icon').classList.remove('hidden');
    document.getElementById('sync-saved').classList.add('hidden');
    saveTimeout = setTimeout(() => {
        submitForm();
    }, 700);
}

function submitForm() {
    const form = document.getElementById('appearance-form');
    const formData = new FormData(form);
    // debug layout value being sent
    console.log('submit layout =', formData.get('layout'));

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(async res => {
        if(res.ok) {
            showToast();
            reloadIframe();
        } else {
            const data = await res.json().catch(()=>null);
            console.error('Save failed', data);
            // show validation reason if exists
            if (data && data.errors && data.errors.layout) {
                const el = document.getElementById('save-error');
                el.innerHTML = '<i data-lucide="alert-circle" class="w-5 h-5"></i><span class="text-sm font-medium">Layout error: ' + data.errors.layout[0] + '</span>';
                if(window.lucide && lucide.icons) lucide.createIcons({icons: lucide.icons});
            }
            showError();
        }
    })
    .catch(err => { console.error(err); showError(); })
    .finally(() => {
        document.getElementById('sync-icon').classList.add('hidden');
    });
}

function reloadIframe() {
    const frames = [document.getElementById('preview-frame'), document.getElementById('preview-frame-mobile')];
    frames.forEach(frame => {
        if(frame) {
            // cache-buster agar perubahan layout langsung terlihat (seluruh geser)
            const base = frame.src.split('?')[0];
            frame.src = base + '?v=' + Date.now();
        }
    });
}

let toastTimeout;
function showToast() {
    const toast = document.getElementById('save-toast');
    const saved = document.getElementById('sync-saved');
    toast.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
    saved.classList.remove('hidden');
    if(window.lucide && lucide.icons) lucide.createIcons({icons: lucide.icons});
    clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
        saved.classList.add('hidden');
    }, 2200);
}
function showError() {
    const el = document.getElementById('save-error');
    el.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
    setTimeout(()=> el.classList.add('opacity-0','translate-y-4','pointer-events-none'), 2500);
}

// ── Init ──
document.addEventListener('DOMContentLoaded', () => {
    switchBgType(initialType);
    renderGradientStops();

    document.querySelectorAll('.auto-save').forEach(el => {
        el.addEventListener('change', triggerAutoSave);
    });
    document.querySelectorAll('.auto-save-input').forEach(el => {
        el.addEventListener('input', triggerAutoSave);
    });

    ['text_color','primary_color','button_color','button_text_color'].forEach(name => {
        const picker = document.getElementById(`picker_${name}`);
        const hex = document.getElementById(`hex_${name}`);
        if (picker) {
            picker.setAttribute('name', name);
            hex.removeAttribute('name');
            hex.addEventListener('input', function() {
                if (/^#[0-9a-fA-F]{6}$/.test(this.value)) {
                    picker.value = this.value;
                    triggerAutoSave();
                }
            });
        }
    });
    if(window.lucide && lucide.icons) lucide.createIcons({icons: lucide.icons});
});
</script>
@endpush

