{{-- Deprecated: product PDP uses reviews-html. Requires $productReviews from controller. --}}
@isset($productReviews)
    @include('shop::products.view.reviews-html')
@endisset
