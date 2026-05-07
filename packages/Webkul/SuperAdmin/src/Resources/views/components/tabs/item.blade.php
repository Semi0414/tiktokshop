@props([
    'title'      => '',
    'isSelected' => false,
])

<div {{ $attributes->merge(['class' => 'p-5 max-1180:px-5']) }}>
    {{ $slot }}
</div>
