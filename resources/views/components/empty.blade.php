@props(['title', 'text' => null])
<div class="px-6 py-14 text-center">
    <p class="font-display text-lg font-bold">{{ $title }}</p>
    @if ($text)<p class="mx-auto mt-1 max-w-sm text-sm text-mute">{{ $text }}</p>@endif
    <div class="mt-4">{{ $slot }}</div>
</div>
