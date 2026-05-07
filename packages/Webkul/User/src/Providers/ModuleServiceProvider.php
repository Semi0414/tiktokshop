<?php

namespace Webkul\User\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\User\Models\Admin;
use Webkul\User\Models\Role;
use Webkul\User\Models\SellerNote;
use Webkul\User\Models\SellerStoreProduct;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        Admin::class,
        Role::class,
        SellerNote::class,
        SellerStoreProduct::class,
    ];
}
