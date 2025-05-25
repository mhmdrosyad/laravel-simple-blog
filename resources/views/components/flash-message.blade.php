@props(['type' => 'success'])

@php
    $colors = [
        'success' => 'bg-green-100 text-green-800',
        'error' => 'bg-red-100 text-red-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'info' => 'bg-blue-100 text-blue-800',
    ];

    $colorClasses = $colors[$type] ?? $colors['info'];
@endphp

@if(session()->has('success') && $type === 'success')
    <div {{ $attributes->merge(['class' => "rounded px-4 py-3 $colorClasses mb-4"]) }} role="alert">
        {{ session('success') }}
    </div>
@endif

@if(session()->has('error') && $type === 'error')
    <div {{ $attributes->merge(['class' => "rounded px-4 py-3 $colorClasses mb-4"]) }} role="alert">
        {{ session('error') }}
    </div>
@endif

@if(session()->has('warning') && $type === 'warning')
    <div {{ $attributes->merge(['class' => "rounded px-4 py-3 $colorClasses mb-4"]) }} role="alert">
        {{ session('warning') }}
    </div>
@endif

@if(session()->has('info') && $type === 'info')
    <div {{ $attributes->merge(['class' => "rounded px-4 py-3 $colorClasses mb-4"]) }} role="alert">
        {{ session('info') }}
    </div>
@endif
