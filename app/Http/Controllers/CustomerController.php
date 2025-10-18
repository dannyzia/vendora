<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Show the customer dashboard/home page
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Get customer's recent orders (if any)
        $recentOrders = Order::where('customer_id', $user->id)
            ->with(['vendor', 'items'])
            ->latest()
            ->limit(5)
            ->get();

        // Get featured/popular products (you can customize this logic)
        $featuredProducts = Product::where('status', 'active')
            ->where('is_featured', true)
            ->limit(8)
            ->get();

        // Get new arrivals
        $newArrivals = Product::where('status', 'active')
            ->latest()
            ->limit(8)
            ->get();

        // Statistics
        $stats = [
            'total_orders' => Order::where('customer_id', $user->id)->count(),
            'pending_orders' => Order::where('customer_id', $user->id)
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'wishlist_count' => $user->wishlists()->count(),
        ];

        return inertia('Customer/Dashboard', [
            'user' => $user,
            'recentOrders' => $recentOrders,
            'featuredProducts' => $featuredProducts,
            'newArrivals' => $newArrivals,
            'stats' => $stats,
        ]);
    }

    /**
     * Show customer profile
     */
    public function profile()
    {
        return inertia('Customer/Profile', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Show customer orders
     */
    public function orders()
    {
        $orders = Order::where('customer_id', auth()->id())
            ->with(['vendor', 'items'])
            ->latest()
            ->paginate(10);

        return inertia('Customer/Orders', [
            'orders' => $orders,
        ]);
    }

    /**
     * Show customer wishlist
     */
    public function wishlist()
    {
        $wishlist = auth()->user()->wishlists()
            ->with('product')
            ->latest()
            ->get();

        return inertia('Customer/Wishlist', [
            'wishlist' => $wishlist,
        ]);
    }
}
