<div class="mt-6 rounded-md bg-white p-5 shadow-sm dark:bg-gray-900">
    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
        <?php echo app('translator')->get('superadmin::app.sales.booking.calendar.booking-details'); ?>
    </p>

    <?php if(! empty($calendarBookings) && count($calendarBookings)): ?>
        <div class="grid gap-3">
            <?php $__currentLoopData = $calendarBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="rounded border border-gray-200 p-3 dark:border-gray-800">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <p class="font-semibold text-gray-800 dark:text-white">
                            #<?php echo e($booking->order_id); ?> - <?php echo e($booking->full_name ?? 'N/A'); ?>

                        </p>

                        <span class="text-xs font-medium uppercase text-gray-600 dark:text-gray-300">
                            <?php echo e($booking->status ?? 'N/A'); ?>

                        </span>
                    </div>

                    <div class="mt-2 grid gap-1 text-sm text-gray-600 dark:text-gray-300">
                        <p>
                            <?php echo app('translator')->get('superadmin::app.sales.booking.calendar.time-slot'); ?>:
                            <?php echo e($booking->start_formatted ?? 'N/A'); ?> - <?php echo e($booking->end_formatted ?? 'N/A'); ?>

                        </p>
                        <p>
                            <?php echo app('translator')->get('superadmin::app.sales.booking.calendar.price'); ?>:
                            <?php echo e($booking->total_formatted ?? 'N/A'); ?>

                        </p>
                        <p>
                            <?php echo app('translator')->get('superadmin::app.sales.booking.calendar.booking-date'); ?>:
                            <?php echo e(\Carbon\Carbon::parse($booking->created_at)->format('d M, Y')); ?>

                        </p>
                    </div>

                    <div class="mt-3">
                        <a
                            href="<?php echo e(route('superadmin.sales.orders.view', $booking->order_id)); ?>"
                            class="text-sm font-medium text-blue-600 hover:underline"
                        >
                            <?php echo app('translator')->get('superadmin::app.sales.booking.calendar.view-details'); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <p class="text-sm text-gray-600 dark:text-gray-300">
            No bookings found for current month.
        </p>
    <?php endif; ?>
</div><?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/sales/bookings/calendar.blade.php ENDPATH**/ ?>