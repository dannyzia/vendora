<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
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
// VENDOR ROUTES (Protected) - Coming Soon
// ============================================
/*
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
    
    // Onboarding Steps
    Route::prefix('onboarding')->name('onboarding.')->group(function () {
        Route::get('/', [OnboardingController::class, 'index'])->name('index');
        Route::get('/application', [OnboardingController::class, 'showStep1'])->name('step1');
        Route::post('/application', [OnboardingController::class, 'storeStep1']);
        Route::get('/documents', [OnboardingController::class, 'showStep2'])->name('step2');
        Route::post('/documents', [OnboardingController::class, 'storeStep2']);
        Route::get('/verification', [OnboardingController::class, 'showStep3'])->name('step3');
        Route::post('/send-otp', [OnboardingController::class, 'sendOtp'])->name('send-otp');
        Route::post('/verify-otp', [OnboardingController::class, 'verifyOtp'])->name('verify-otp');
        Route::post('/submit', [OnboardingController::class, 'submitApplication'])->name('submit');
        Route::get('/pending', [OnboardingController::class, 'showPending'])->name('pending');
        Route::get('/complete', [ProfileController::class, 'showComplete'])->name('complete');
        Route::post('/complete', [ProfileController::class, 'storeComplete']);
    });
});
*/

// ============================================
// ADMIN ROUTES (Protected) - Coming Soon
// ============================================
/*
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/applications', [VendorApplicationController::class, 'index'])->name('applications');
        Route::get('/applications/{vendor}', [VendorApplicationController::class, 'show'])->name('show');
        Route::post('/applications/{vendor}/approve', [VendorApplicationController::class, 'approve'])->name('approve');
        Route::post('/applications/{vendor}/reject', [VendorApplicationController::class, 'reject'])->name('reject');
        Route::get('/applications/{vendor}/document/{type}', [VendorApplicationController::class, 'viewDocument'])->name('document');
    });
});
*/