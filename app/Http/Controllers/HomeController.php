<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(20);
        return inertia('Home', [
            'products' => $products,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $products = Product::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->latest()
            ->paginate(20);

        return inertia('Products/Index', [
            'products' => $products,
        ]);
    }
}
