<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['vendor', 'category'])
            ->active();

        // Search
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        match($sortBy) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderBy('sales_count', 'desc'),
            'rating' => $query->orderBy('rating_avg', 'desc'),
            default => $query->orderBy('created_at', $sortOrder),
        };

        $products = $query->paginate(24);

        return inertia('Products/Index', [
            'products' => $products,
            'categories' => Category::active()->get(),
            'filters' => $request->only(['search', 'category', 'type', 'min_price', 'max_price', 'sort']),
        ]);
    }

    public function show($slug)
    {
        $product = Product::with([
            'vendor',
            'category',
            'productable',
            'reviews' => fn($q) => $q->approved()->latest()->limit(10),
            'variants',
        ])
        ->where('slug', $slug)
        ->active()
        ->firstOrFail();

        // Increment view count
        $product->incrementViews();

        return inertia('Products/Show', [
            'product' => $product,
            'relatedProducts' => Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->active()
                ->limit(4)
                ->get(),
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->active()
            ->limit(10)
            ->get(['id', 'title', 'slug', 'price', 'thumbnail']);

        return response()->json($products);
    }
}
