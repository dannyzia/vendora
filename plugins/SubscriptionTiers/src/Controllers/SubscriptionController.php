<?php

namespace Plugins\SubscriptionTiers\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::all();
        return view('subscription-tiers::index', ['plans' => $plans]);
    }
}
