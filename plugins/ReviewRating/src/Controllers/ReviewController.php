<?php

namespace Plugins\ReviewRating\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string',
        ]);

        $product->reviews()->create([
            'customer_id' => auth()->id(),
            'vendor_id' => $product->vendor_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review submitted successfully.');
    }
}
