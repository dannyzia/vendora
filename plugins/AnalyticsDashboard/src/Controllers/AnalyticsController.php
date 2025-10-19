<?php

namespace Plugins\AnalyticsDashboard\Controllers;

use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('analytics-dashboard::index');
    }
}
