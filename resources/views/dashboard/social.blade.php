@extends('layouts.app')
@section('title', 'Social Links')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Add New Social Link -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Social Link</h3>
        <form action="{{ route('dashboard.social.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Platform <span class="text-xs text-gray-400 font-normal">— icon monokrom thin</span></label>
                    <select name="platform" required id="platform-select" class="block w-full rounded-md border-gray-300 focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border">
                        <option value="">Select Platform</option>
                        @foreach($platforms as $platform)
                            @php $ic = (new \App\Models\SocialLink(['platform'=>$platform]))->lucide_icon; @endphp
                            <option value="{{ $platform }}" data-icon="{{ $ic }}">{{ ucfirst(strtolower(str_replace('_', ' ', $platform))) }}</option>
                        @endforeach
                    </select>
                    <div id="platform-preview" class="hidden mt-2 inline-flex items-center gap-2 text-xs font-medium text-gray-600 bg-gray-50 border border-gray-200 px-2.5 py-1.5 rounded-full">
                        <i id="platform-preview-icon" data-lucide="link-2" class="w-3.5 h-3.5"></i>
                        <span id="platform-preview-label">—</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile URL</label>
                    <input type="url" name="url" required placeholder="https://" class="block w-full rounded-md border-gray-300 focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border">
                </div>
            </div>
            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent bg-brand-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-brand-700 transition">
                Add Social Link
            </button>
        </form>
    </div>

    <!-- Active Social Links -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-sm font-semibold text-gray-800">Your Social Icons</h3>
        </div>
        
        @if($socialLinks->isEmpty())
        <div class="p-8 text-center text-gray-500">
            <i data-lucide="share-2" class="w-8 h-8 mx-auto mb-2 text-gray-400"></i>
            <p>You haven't added any social links yet.</p>
        </div>
        @else
        <div class="divide-y divide-gray-100">
            @foreach($socialLinks as $social)
            <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                <div class="flex items-center gap-4">
                    <span class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm text-gray-700">
                        <i data-lucide="{{ $social->lucide_icon }}" class="w-4 h-4" style="stroke-width:1.75"></i>
                    </span>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ ucfirst(strtolower(str_replace('_', ' ', $social->platform))) }}</p>
                        <p class="text-xs text-gray-500">{{ $social->url }}</p>
                    </div>
                </div>
                
                <form action="{{ route('dashboard.social.destroy', $social) }}" method="POST" onsubmit="return confirm('Remove this social link?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 rounded-md hover:bg-red-50 transition">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  var sel = document.getElementById('platform-select');
  var prev = document.getElementById('platform-preview');
  var prevIcon = document.getElementById('platform-preview-icon');
  var prevLabel = document.getElementById('platform-preview-label');
  function refreshIcons(){
    if(window.lucide && lucide.icons) lucide.createIcons({icons: lucide.icons, attrs:{'stroke-width':1.75}});
  }
  if(sel){
    sel.addEventListener('change', function(){
      var opt = sel.options[sel.selectedIndex];
      var icon = opt.getAttribute('data-icon') || 'link-2';
      var label = opt.text;
      if(sel.value){
        prev.classList.remove('hidden');
        // recreate <i> karena lucide mengganti <i> jadi <svg> setelah createIcons
        var newIcon = document.createElement('i');
        newIcon.id = 'platform-preview-icon';
        newIcon.setAttribute('data-lucide', icon);
        newIcon.className = 'w-3.5 h-3.5';
        prevIcon.replaceWith(newIcon);
        prevIcon = newIcon;
        prevLabel.textContent = label + ' — ' + icon;
        refreshIcons();
      } else {
        prev.classList.add('hidden');
      }
    });
  }
  refreshIcons();
});
</script>
@endpush
@endsection
