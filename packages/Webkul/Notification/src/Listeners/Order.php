<?php

namespace Webkul\Notification\Listeners;

use Illuminate\Support\Facades\Schema;
use Webkul\Notification\Events\CreateOrderNotification;
use Webkul\Notification\Events\UpdateOrderNotification;
use Webkul\Notification\Repositories\NotificationRepository;

class Order
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected NotificationRepository $notificationRepository) {}

    /**
     * Create a new resource.
     *
     * @return void
     */
    public function createOrder($order)
    {
        try {
            $payload = [
                'type'     => 'order',
                'order_id' => $order->id,
            ];

            if (Schema::hasColumn('notifications', 'seller_id')) {
                $payload['seller_id'] = $order->seller_id;
            }

            $this->notificationRepository->create($payload);

            event(new CreateOrderNotification);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    /**
     * Fire an Event when the order status is updated.
     *
     * @return void
     */
    public function updateOrder($order)
    {
        event(new UpdateOrderNotification([
            'id' => $order->id,
            'status' => $order->status,
        ]));
    }
}
