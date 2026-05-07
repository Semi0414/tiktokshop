<?php

namespace Webkul\Shop\Tests;

use Tests\TestCase;
use Webkul\Core\Tests\Concerns\CoreAssertions;
use Webkul\Shop\Tests\Concerns\ShopTestBench;

class ShopTestCase extends TestCase
{
    use CoreAssertions, ShopTestBench;

    protected function setUp(): void
    {
        parent::setUp();

        config(['seller_preview.restrict_home_to_seller_preview' => false]);
    }
}
