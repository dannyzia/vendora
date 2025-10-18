<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.onboarding');
        }

        // Sales summary
        $todaySales = Order::where('vendor_id', $vendor->id)
            ->whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total');

        $weekSales = Order::where('vendor_id', $vendor->id)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('payment_status', 'paid')
            ->sum('total');

        $monthSales = Order::where('vendor_id', $vendor->id)
            ->whereMonth('created_at', now()->month)
            ->where('payment_status', 'paid')
            ->sum('total');

        // Recent orders
        $recentOrders = Order::where('vendor_id', $vendor->id)
            ->with('customer')
            ->latest()
            ->limit(5)
            ->get();

        // Products stats
        $totalProducts = Product::where('vendor_id', $vendor->id)->count();
        $activeProducts = Product::where('vendor_id', $vendor->id)->active()->count();
        $pendingProducts = Product::where('vendor_id', $vendor->id)->pending()->count();

        // Low stock alerts
        $lowStockProducts = Product::where('vendor_id', $vendor->id)
            ->whereHas('productable', function($q) {
                $q->where('track_inventory', true)
                  ->whereRaw('stock_quantity <= low_stock_threshold');
            })
            ->limit(5)
            ->get();

        return inertia('Vendor/Dashboard', [
            'vendor' => $vendor,
            'stats' => [
                'today_sales' => $todaySales,
                'week_sales' => $weekSales,
                'month_sales' => $monthSales,
                'total_sales' => $vendor->total_sales,
                'available_balance' => $vendor->available_balance,
                'pending_balance' => $vendor->pending_balance,
                'total_products' => $totalProducts,
                'active_products' => $activeProducts,
                'pending_products' => $pendingProducts,
                'trust_score' => $vendor->trust_score,
            ],
            'recent_orders' => $recentOrders,
            'low_stock_products' => $lowStockProducts,
        ]);
    }
}
