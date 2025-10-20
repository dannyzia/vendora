<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with(['vendor', 'category'])
            ->where('status', 'active')
            ->latest()
            ->take(12)
            ->get();

        return inertia('Home', [
            'products' => $products,
            'auth' => [
                'user' => auth()->user(),
            ],
        ]);
    }

    public function search(Request $request)
    {
        // Search logic here
        $products = Product::query()
            ->with(['vendor', 'category'])
            ->where('status', 'active')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate(20);

        return inertia('Products/Index', [
            'products' => $products,
        ]);
    }
}
