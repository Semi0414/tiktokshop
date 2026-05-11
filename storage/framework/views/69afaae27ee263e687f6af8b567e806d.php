
<?php if (isset($component)) { $__componentOriginalb5169dfa68798862ebc86d44e6d10498 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb5169dfa68798862ebc86d44e6d10498 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'shop::components.layouts.tawk-chat','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop::layouts.tawk-chat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb5169dfa68798862ebc86d44e6d10498)): ?>
<?php $attributes = $__attributesOriginalb5169dfa68798862ebc86d44e6d10498; ?>
<?php unset($__attributesOriginalb5169dfa68798862ebc86d44e6d10498); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb5169dfa68798862ebc86d44e6d10498)): ?>
<?php $component = $__componentOriginalb5169dfa68798862ebc86d44e6d10498; ?>
<?php unset($__componentOriginalb5169dfa68798862ebc86d44e6d10498); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/components/layouts/storefront-chat-widgets.blade.php ENDPATH**/ ?>