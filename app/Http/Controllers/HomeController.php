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
        // SELLER DISPLAYS
        // Featured Sellers (using latest vendors for now)
        $featuredSellers = Vendor::where('status', 'active')
            ->withCount('products')
            ->latest()
            ->limit(10)
            ->get();

        // Top Sellers (using latest vendors for now, can add sales tracking later)
        $topSellers = Vendor::where('status', 'active')
            ->withCount('products')
            ->latest()
            ->limit(5)
            ->get();

        // PRODUCT DISPLAYS (2 rows x 5 cols = 10 products visible, more scroll)
        
        // Product Display 1: Featured Products (using latest for now)
        $featuredProducts = Product::where('status', 'active')
            ->with('vendor')
            ->latest()
            ->limit(20)
            ->get();

        // Product Display 2: On Sale (empty for now, will work when price fields added)
        $onSaleProducts = collect([]); // Empty collection for now

        // Product Display 3: Hot Products (using latest)
        $hotProducts = Product::where('status', 'active')
            ->with('vendor')
            ->latest()
            ->limit(20)
            ->get();

        // Product Display 4: Deal of the Day (using random for now)
        $dealOfTheDay = Product::where('status', 'active')
            ->with('vendor')
            ->inRandomOrder()
            ->limit(20)
            ->get();

        // Categories for browse menu
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

        return inertia('Home', [
            'featuredSellers' => $featuredSellers,
            'topSellers' => $topSellers,
            'featuredProducts' => $featuredProducts,
            'onSaleProducts' => $onSaleProducts,
            'hotProducts' => $hotProducts,
            'dealOfTheDay' => $dealOfTheDay,
            'categories' => $categories,
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