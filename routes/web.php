<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Vendor\OnboardingController;
use App\Http\Controllers\Admin\VendorApplicationController;
use Illuminate\Support\Facades\Route;

// ============================================
// PUBLIC ROUTES
// ============================================

// Home Route - Show marketplace homepage or redirect based on auth status
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        } elseif ($user->isVendor()) {
            // Check vendor onboarding status
            $vendor = $user->vendor;
            if (!$vendor || $vendor->onboarding_status !== 'complete') {
                return redirect('/vendor/onboarding');
            }
            return redirect('/vendor/dashboard');
        } else {
            // Customer
            return redirect('/customer/dashboard');
        }
    }
    
    // Guest users see marketplace homepage
    return app(HomeController::class)->index();
})->name('home');

// Search Products
Route::get('/products', [HomeController::class, 'search'])->name('products.search');

// ============================================
// AUTHENTICATION ROUTES
// ============================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout (requires authentication)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ============================================
// CUSTOMER ROUTES (Protected)
// ============================================
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
    Route::get('/wishlist', [CustomerController::class, 'wishlist'])->name('wishlist');
});

// Dashboard redirect (role-based) - Legacy support
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect('/admin/dashboard');
    } elseif (auth()->user()->isVendor()) {
        return redirect('/vendor/dashboard');
    }
    return redirect('/customer/dashboard');
})->middleware('auth')->name('dashboard');

// ============================================
// VENDOR ROUTES (Protected)
// ============================================
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    
    // Vendor Dashboard (only accessible if onboarding complete)
    Route::get('/dashboard', function () {
        $vendor = auth()->user()->vendor;
        if (!$vendor || $vendor->onboarding_status !== 'complete') {
            return redirect()->route('vendor.onboarding.index');
        }
        return inertia('Vendor/Dashboard', ['vendor' => $vendor]);
    })->name('dashboard');
    
    // Onboarding Routes
    Route::prefix('onboarding')->name('onboarding.')->group(function () {
        // Main onboarding router
        Route::get('/', [OnboardingController::class, 'index'])->name('index');
        
        // Step 1: Application Form
        Route::get('/application', [OnboardingController::class, 'showStep1'])->name('step1');
        Route::post('/application', [OnboardingController::class, 'storeStep1']);
        
        // Step 2: Document Upload
        Route::get('/documents', [OnboardingController::class, 'showStep2'])->name('step2');
        Route::post('/documents', [OnboardingController::class, 'storeStep2']);
        
        // Step 3: Phone Verification (OTP)
        Route::get('/verification', [OnboardingController::class, 'showStep3'])->name('step3');
        Route::post('/send-otp', [OnboardingController::class, 'sendOtp'])->name('send-otp');
        Route::post('/verify-otp', [OnboardingController::class, 'verifyOtp'])->name('verify-otp');
        
        // Step 4: Pending Approval
        Route::get('/pending', [OnboardingController::class, 'showPending'])->name('pending');
        
        // Step 5: Complete Profile (after approval)
        Route::get('/complete', [OnboardingController::class, 'showComplete'])->name('complete');
        Route::post('/complete', [OnboardingController::class, 'storeComplete']);
    });
});

// ============================================
// ADMIN ROUTES (Protected)
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', function () {
        return inertia('Admin/Dashboard');
    })->name('dashboard');
    
    // Vendor Application Management
    Route::prefix('vendors')->name('vendors.')->group(function () {
        // List all applications
        Route::get('/applications', [VendorApplicationController::class, 'index'])->name('applications');
        
        // View specific application
        Route::get('/applications/{vendor}', [VendorApplicationController::class, 'show'])->name('show');
        
        // Approve application
        Route::post('/applications/{vendor}/approve', [VendorApplicationController::class, 'approve'])->name('approve');
        
        // Reject application
        Route::post('/applications/{vendor}/reject', [VendorApplicationController::class, 'reject'])->name('reject');
        
        // View documents
        Route::get('/applications/{vendor}/document/{type}', [VendorApplicationController::class, 'viewDocument'])->name('document');
    });
});
