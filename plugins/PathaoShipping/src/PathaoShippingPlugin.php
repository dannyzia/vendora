<?php

namespace Plugins\PathaoShipping;

use App\Core\Plugin\Plugin;

class PathaoShippingPlugin extends Plugin
{
    public function boot(): void
    {
        $this->addFilter('checkout.shipping_methods', [$this, 'addPathaoShippingMethod']);
    }

    public function addPathaoShippingMethod($methods)
    {
        $methods['pathao'] = [
            'id' => 'pathao',
            'title' => 'Pathao Courier',
            'description' => 'Home delivery by Pathao',
            'cost' => 60,
        ];

        return $methods;
    }
}
