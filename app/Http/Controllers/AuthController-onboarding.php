<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return inertia('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Role-based redirect
            $user = Auth::user();
            
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->isVendor()) {
                // Check vendor onboarding status
                $vendor = $user->vendor;
                if (!$vendor || $vendor->onboarding_status !== 'complete') {
                    return redirect()->intended('/vendor/onboarding');
                }
                return redirect()->intended('/vendor/dashboard');
            } else {
                // Customer
                return redirect()->intended('/customer/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return inertia('Auth/Register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:customer,vendor',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        Auth::login($user);

        // Role-based redirect after registration
        if ($user->isVendor()) {
            // Create vendor record
            Vendor::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'onboarding_status' => 'incomplete',
            ]);
            
            return redirect()->route('vendor.onboarding.index')
                ->with('success', 'Welcome! Please complete your vendor onboarding to start selling.');
        }

        // Customer
        return redirect()->route('customer.dashboard')
            ->with('success', 'Welcome to Vendora! Start shopping now.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
