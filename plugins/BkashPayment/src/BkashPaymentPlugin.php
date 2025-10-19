<?php

namespace Plugins\BkashPayment;

use App\Core\Plugin\Plugin;

class BkashPaymentPlugin extends Plugin
{
    public function boot(): void
    {
        $this->addFilter('checkout.payment_methods', [$this, 'addBkashPaymentMethod']);
    }

    public function addBkashPaymentMethod($methods)
    {
        $methods['bkash'] = [
            'id' => 'bkash',
            'title' => 'bKash',
            'description' => 'Pay with bKash mobile banking',
            'icon' => $this->getAssetUrl('images/bkash-logo.png'),
        ];

        return $methods;
    }
}
