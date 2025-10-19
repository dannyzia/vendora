<?php

namespace Plugins\AnalyticsDashboard;

use App\Core\Plugin\Plugin;

class AnalyticsDashboardPlugin extends Plugin
{
    public function boot(): void
    {
        $this->addFilter('vendor.dashboard.menu', [$this, 'addAnalyticsMenu']);
    }

    public function addAnalyticsMenu($menu)
    {
        $menu['analytics'] = [
            'label' => 'Analytics',
            'route' => 'vendor.analytics.index',
            'icon' => 'chart-bar',
        ];

        return $menu;
    }
}
