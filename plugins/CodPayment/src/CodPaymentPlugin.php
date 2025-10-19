<?php

namespace Plugins\CodPayment;

use App\Core\Plugin\Plugin;

class CodPaymentPlugin extends Plugin
{
    public function boot(): void
    {
        $this->addFilter('checkout.payment_methods', [$this, 'addCodPaymentMethod']);
    }

    public function addCodPaymentMethod($methods)
    {
        $methods['cod'] = [
            'id' => 'cod',
            'title' => 'Cash on Delivery',
            'description' => 'Pay with cash upon delivery',
        ];

        return $methods;
    }
}
