<?php

namespace Webkul\SuperAdmin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\SuperAdmin\Listeners\Admin;
use Webkul\SuperAdmin\Listeners\Customer;
use Webkul\SuperAdmin\Listeners\GDPR;
use Webkul\SuperAdmin\Listeners\Invoice;
use Webkul\SuperAdmin\Listeners\Order;
use Webkul\SuperAdmin\Listeners\Refund;
use Webkul\SuperAdmin\Listeners\Shipment;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'customer.create.after' => [
            [Customer::class, 'afterCreated'],
        ],

        'customer.gdpr-request.create.after' => [
            [GDPR::class, 'afterGdprRequestCreated'],
        ],

        'customer.gdpr-request.update.after' => [
            [GDPR::class, 'afterGdprRequestUpdated'],
        ],

        'admin.password.update.after' => [
            [Admin::class, 'afterPasswordUpdated'],
        ],

        'checkout.order.save.after' => [
            [Order::class, 'afterCreated'],
        ],

        'sales.order.cancel.after' => [
            [Order::class, 'afterCanceled'],
        ],

        'sales.invoice.save.after' => [
            [Invoice::class, 'afterCreated'],
        ],

        'sales.shipment.save.after' => [
            [Shipment::class, 'afterCreated'],
        ],

        'sales.refund.save.after' => [
            [Refund::class, 'afterCreated'],
        ],
    ];
}
