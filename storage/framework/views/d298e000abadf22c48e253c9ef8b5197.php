<?php if($paginator->hasPages()): ?>
    <?php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $start = max(1, $current - 2);
        $end = min($last, $current + 2);
        if ($end - $start < 4 && $last > 4) {
            if ($start === 1) {
                $end = min($last, $start + 4);
            } else {
                $start = max(1, $end - 4);
            }
        }
    ?>
    <nav class="pagination" aria-label="Pagination">
        <?php if($current > 1): ?>
            <a class="page-pill" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">‹</a>
        <?php else: ?>
            <span class="page-pill muted">‹</span>
        <?php endif; ?>

        <?php for($p = $start; $p <= $end; $p++): ?>
            <?php if($p === $current): ?>
                <span class="page-pill active"><?php echo e($p); ?></span>
            <?php else: ?>
                <a class="page-pill" href="<?php echo e($paginator->url($p)); ?>"><?php echo e($p); ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if($current < $last): ?>
            <a class="page-pill" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">›</a>
        <?php else: ?>
            <span class="page-pill muted">›</span>
        <?php endif; ?>
    </nav>
<?php endif; ?>
<?php /**PATH C:\Users\Woosols\Downloads\tiktokshop-main\packages\Webkul\Shop\src/resources/views/tik-store/partials/pagination.blade.php ENDPATH**/ ?>