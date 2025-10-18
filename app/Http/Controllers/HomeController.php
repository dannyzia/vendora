<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the public marketplace homepage
     */
    public function index()
    {
        // Get featured products
        $featuredProducts = Product::where('status', 'active')
            ->where('is_featured', true)
            ->with('vendor')
            ->limit(8)
            ->get();

        // Get hot/trending products (most viewed or best selling)
        $hotProducts = Product::where('status', 'active')
            ->orderBy('views_count', 'desc')
            ->with('vendor')
            ->limit(8)
            ->get();

        // Get new arrivals
        $newArrivals = Product::where('status', 'active')
            ->latest()
            ->with('vendor')
            ->limit(8)
            ->get();

        // Get product categories (you can customize this based on your categories table)
        $categories = [
            ['name' => 'Electronics', 'icon' => '📱', 'slug' => 'electronics'],
            ['name' => 'Clothing', 'icon' => '👕', 'slug' => 'clothing'],
            ['name' => 'Home & Kitchen', 'icon' => '🏠', 'slug' => 'home-kitchen'],
            ['name' => 'Books', 'icon' => '📚', 'slug' => 'books'],
            ['name' => 'Sports', 'icon' => '⚽', 'slug' => 'sports'],
            ['name' => 'Beauty', 'icon' => '💄', 'slug' => 'beauty'],
            ['name' => 'Toys', 'icon' => '🧸', 'slug' => 'toys'],
            ['name' => 'Automotive', 'icon' => '🚗', 'slug' => 'automotive'],
        ];

        // Get marketplace stats
        $stats = [
            'total_products' => Product::where('status', 'active')->count(),
            'total_vendors' => Vendor::where('status', 'active')->count(),
            'total_categories' => 50, // Update based on your actual categories
        ];

        return inertia('Home', [
            'featuredProducts' => $featuredProducts,
            'hotProducts' => $hotProducts,
            'newArrivals' => $newArrivals,
            'categories' => $categories,
            'stats' => $stats,
        ]);
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $category = $request->input('category');

        $products = Product::where('status', 'active')
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->when($category, function ($q) use ($category) {
                $q->where('category', $category);
            })
            ->with('vendor')
            ->paginate(24);

        return inertia('Products/Index', [
            'products' => $products,
            'query' => $query,
            'category' => $category,
        ]);
    }
}
