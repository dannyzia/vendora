<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->vendor->orders()->with('items.product', 'user')->latest()->paginate(10);

        return inertia('Vendor/Orders/Index', [
            'orders' => $orders,
        ]);
    }
}
