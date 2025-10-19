<?php

namespace Plugins\BkashPayment\Controllers;

use App\Http\Controllers\Controller;

class BkashController extends Controller
{
    public function settings()
    {
        return view('bkash-payment::settings');
    }
}
