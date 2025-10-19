<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get();

        $cartItems = $products->map(function ($product) use ($cart) {
            return [
                'product' => $product,
                'quantity' => $cart[$product->id]['quantity'],
            ];
        });

        return inertia('Checkout/Index', [
            'cartItems' => $cartItems,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $cart = session()->get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get();

        $total = $products->sum(function ($product) use ($cart) {
            return $product->price * $cart[$product->id]['quantity'];
        });

        $order = Order::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'total' => $total,
            'status' => 'pending',
        ]);

        foreach ($products as $product) {
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $cart[$product->id]['quantity'],
                'price' => $product->price,
            ]);
        }

        session()->forget('cart');

        return redirect()->route('payment.pay', ['order_id' => $order->id]);
    }
}
