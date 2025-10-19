<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Vendor\OnboardingController;
use App\Http\Controllers\Vendor\ProductController;
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
// CART ROUTES
// ============================================
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::put('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

// ============================================
// CHECKOUT ROUTES
// ============================================
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order/{order}/success', function (App\Models\Order $order) {
    return inertia('Checkout/Success', ['order' => $order]);
})->name('order.success');

// ============================================
// PAYMENT ROUTES
// ============================================
Route::post('/pay', [PaymentController::class, 'pay'])->name('payment.pay');
Route::post('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/failure', [PaymentController::class, 'failure'])->name('payment.failure');
Route::post('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

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
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
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

    // Vendor Profile
    Route::get('/profile', [\App\Http\Controllers\Vendor\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\Vendor\ProfileController::class, 'update'])->name('profile.update');
    
    // Product Management
    Route::resource('products', ProductController::class);

    // Order Management
    Route::get('/orders', [\App\Http\Controllers\Vendor\OrderController::class, 'index'])->name('orders.index');

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