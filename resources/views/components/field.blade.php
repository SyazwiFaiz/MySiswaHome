@props(['name', 'label', 'hint' => null])
<div {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="label">{{ $label }}</label>
    {{ $slot }}
    @if ($hint)<p class="mt-1 text-xs text-mute">{{ $hint }}</p>@endif
    @error($name)<p class="mt-1 text-xs text-rose-700">{{ $message }}</p>@enderror
</div>
