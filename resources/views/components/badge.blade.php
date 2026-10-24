@props(['tone' => 'gray'])
@php
    $c = [
        'green' => 'bg-green-100 text-green-700',
        'amber' => 'bg-amber-100 text-amber-700',
        'rose'  => 'bg-rose-100 text-rose-700',
        'blue'  => 'bg-indigo-50 text-indigo-700',
        'gray'  => 'bg-gray-100 text-gray-600',
    ][$tone] ?? '';
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold $c"]) }}>{{ $slot }}</span>
