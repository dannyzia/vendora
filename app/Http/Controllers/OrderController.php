<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with('items.product')->latest()->paginate(10);

        return inertia('Customer/Orders/Index', [
            'orders' => $orders,
        ]);
    }
}