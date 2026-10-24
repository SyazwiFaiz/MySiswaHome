@props(['user'])
@if ($user->avatar_url)
    <img src="{{ $user->avatar_url }}" alt="" {{ $attributes->merge(['class' => 'shrink-0 rounded-full object-cover']) }}>
@else
    <span {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-800']) }}>{{ $user->initials }}</span>
@endif
