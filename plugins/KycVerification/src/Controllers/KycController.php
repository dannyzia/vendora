<?php

namespace Plugins\KycVerification\Controllers;

use App\Http\Controllers\Controller;

class KycController extends Controller
{
    public function index()
    {
        return view('kyc-verification::index');
    }
}
