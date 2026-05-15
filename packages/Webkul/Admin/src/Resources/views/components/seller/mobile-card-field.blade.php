@props(['label'])

<div class="seller-mobile-card__row">
    <span class="seller-mobile-card__label">{{ $label }}</span>
    <span {{ $attributes->merge(['class' => 'seller-mobile-card__value']) }}>
        {{ $slot }}
    </span>
</div>
