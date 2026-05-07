@props([
    'isActive' => true,
])

<div {{ $attributes->merge(['class' => 'box-shadow rounded bg-white dark:bg-gray-900']) }}>
    @isset($header)
        <div {{ $header->attributes->merge(['class' => 'flex items-center justify-between p-1.5']) }}>
            {{ $header }}
        </div>
    @endisset

    @isset($content)
        <div {{ $content->attributes->merge(['class' => 'px-4 pb-4']) }}>
            {{ $content }}
        </div>
    @endisset
</div>
