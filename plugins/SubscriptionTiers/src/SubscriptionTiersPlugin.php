<?php

namespace Plugins\SubscriptionTiers;

use App\Core\Plugin\Plugin;

class SubscriptionTiersPlugin extends Plugin
{
    public function boot(): void
    {
        $this->addFilter('admin.dashboard.menu', [$this, 'addSubscriptionMenu']);
    }

    public function addSubscriptionMenu($menu)
    {
        $menu['subscriptions'] = [
            'label' => 'Subscription Tiers',
            'route' => 'admin.subscriptions.index',
            'icon' => 'credit-card',
        ];

        return $menu;
    }
}
