@extends('layouts.app')
@section('title', 'Analytics')

@section('content')
<div class="space-y-6 animate-fade-in">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white border border-gray-200 rounded-xl p-6 flex items-center gap-4 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center">
                <i data-lucide="eye" class="text-blue-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Profile Views (30 Days)</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($views) }}</p>
            </div>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-xl p-6 flex items-center gap-4 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center">
                <i data-lucide="mouse-pointer-click" class="text-purple-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Link Clicks (30 Days)</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($clicks) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Profile Views (Last 7 Days)</h3>
        
        <div style="position: relative; height:300px; width:100%">
            <canvas id="viewsChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('viewsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!},
                datasets: [{
                    label: 'Profile Views',
                    data: {!! json_encode($chartData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    });
</script>
@endpush
