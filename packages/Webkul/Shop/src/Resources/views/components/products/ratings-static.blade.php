@props([
    'average' => '0',
    'total' => 0,
    'showLabel' => true,
])

@php
    $avg = is_numeric($average) ? (float) $average : 0;
    $totalNum = (int) $total;
    $abbr = $totalNum >= 1000 ? rtrim(rtrim(number_format($totalNum / 1000, 1), '0'), '.').'k' : (string) $totalNum;
@endphp

<div {{ $attributes->merge(['class' => 'flex w-max items-center gap-2 rounded-md border border-zinc-200 px-4 py-2']) }}>
    <span class="text-sm font-medium text-black max-sm:text-xs">
        {{ number_format($avg, 1, '.', '') }}
    </span>

    <span class="icon-star-fill -mt-1 text-xl text-amber-500 max-sm:text-lg" role="presentation"></span>

    @if ($showLabel)
        <span class="border-l border-zinc-300 text-sm font-medium text-black max-sm:border-zinc-300 max-sm:text-xs ltr:pl-2 rtl:pr-2">
            {{ $abbr }}
            <span class="text-zinc-600">@lang('shop::app.components.products.ratings.title')</span>
        </span>
    @else
        <span class="border-l border-zinc-300 text-sm font-medium text-black max-sm:text-xs ltr:pl-2 rtl:pr-2">
            {{ $abbr }}
        </span>
    @endif
</div>
