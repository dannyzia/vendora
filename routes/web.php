<?php

use Illuminate\Support\Facades\Route;

// Home Route
Route::get('/', function () {
    return view('welcome');
});

// Note: Vendor Onboarding and Admin routes will be added as we build the controllers
// For now, keeping the file minimal to avoid errors

// You can uncomment these routes once the controllers are created:
/*
// Vendor Onboarding Routes (Protected by: auth, vendor role)
Route::middleware(['auth', 'vendor'])->prefix('vendor')->group(function () {
    
    // Onboarding Steps
    Route::prefix('onboarding')->name('vendor.onboarding.')->group(function () {
        Route::get('/', [OnboardingController::class, 'index'])->name('index');
        
        // Step 1: Application
        Route::get('/application', [OnboardingController::class, 'showStep1'])->name('step1');
        Route::post('/application', [OnboardingController::class, 'storeStep1']);
        
        // Step 2: Documents
        Route::get('/documents', [OnboardingController::class, 'showStep2'])->name('step2');
        Route::post('/documents', [OnboardingController::class, 'storeStep2']);
        
        // Step 3: Verification
        Route::get('/verification', [OnboardingController::class, 'showStep3'])->name('step3');
        Route::post('/send-otp', [OnboardingController::class, 'sendOtp'])->name('send-otp');
        Route::post('/verify-otp', [OnboardingController::class, 'verifyOtp'])->name('verify-otp');
        
        // Submit Application
        Route::post('/submit', [OnboardingController::class, 'submitApplication'])->name('submit');
        
        // Pending Status
        Route::get('/pending', [OnboardingController::class, 'showPending'])->name('pending');
        
        // Step 4: Profile Completion (After approval)
        Route::get('/complete', [ProfileController::class, 'showComplete'])->name('complete');
        Route::post('/complete', [ProfileController::class, 'storeComplete']);
    });
});

// Admin Routes (Protected by: auth, admin role)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    Route::prefix('vendors')->name('admin.vendors.')->group(function () {
        Route::get('/applications', [VendorApplicationController::class, 'index'])->name('applications');
        Route::get('/applications/{vendor}', [VendorApplicationController::class, 'show'])->name('show');
        Route::post('/applications/{vendor}/approve', [VendorApplicationController::class, 'approve'])->name('approve');
        Route::post('/applications/{vendor}/reject', [VendorApplicationController::class, 'reject'])->name('reject');
        Route::get('/applications/{vendor}/document/{type}', [VendorApplicationController::class, 'viewDocument'])->name('document');
    });
});
*/