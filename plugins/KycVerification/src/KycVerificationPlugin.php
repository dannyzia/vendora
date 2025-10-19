<?php

namespace Plugins\KycVerification;

use App\Core\Plugin\Plugin;

class KycVerificationPlugin extends Plugin
{
    public function boot(): void
    {
        $this->addFilter('vendor.dashboard.menu', [$this, 'addKycMenu']);
    }

    public function addKycMenu($menu)
    {
        $menu['kyc'] = [
            'label' => 'KYC Verification',
            'route' => 'vendor.kyc.index',
            'icon' => 'identification',
        ];

        return $menu;
    }
}
