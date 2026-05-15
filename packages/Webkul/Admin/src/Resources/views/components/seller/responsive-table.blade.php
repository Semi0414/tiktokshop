@props([
    'emptyMessage' => null,
])

<div {{ $attributes->merge(['class' => 'seller-responsive-table overflow-hidden']) }}>
    <div class="seller-rt-table-wrap">
        {{ $table }}
    </div>

    <div class="seller-rt-cards">
        @if (isset($cards) && trim((string) $cards) !== '')
            {{ $cards }}
        @elseif ($emptyMessage)
            <p class="seller-mobile-card seller-mobile-card--empty text-center text-sm text-gray-500 dark:text-gray-400">
                {{ $emptyMessage }}
            </p>
        @endif
    </div>

    @if (isset($footer))
        <div class="seller-rt-footer border-t border-gray-100 dark:border-gray-800">
            {{ $footer }}
        </div>
    @endif
</div>
