<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show the vendor profile.
     */
    public function show()
    {
        $vendor = auth()->user()->vendor;
        return inertia('Vendor/Profile/Show', [
            'vendor' => $vendor,
        ]);
    }

    /**
     * Update the vendor profile.
     */
    public function update(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $validated = $request->validate([
            'shop_name' => 'required|string|max:255|unique:vendors,shop_name,' . $vendor->id,
            'shop_description' => 'required|string|min:50|max:1000',
            'business_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'shop_logo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'shop_banner' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        if ($request->hasFile('shop_logo')) {
            if ($vendor->shop_logo) {
                Storage::disk('public')->delete($vendor->shop_logo);
            }
            $validated['shop_logo'] = $request->file('shop_logo')->store('vendor-shops/logos', 'public');
        }

        if ($request->hasFile('shop_banner')) {
            if ($vendor->shop_banner) {
                Storage::disk('public')->delete($vendor->shop_banner);
            }
            $validated['shop_banner'] = $request->file('shop_banner')->store('vendor-shops/banners', 'public');
        }

        $vendor->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
