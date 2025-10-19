<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Notifications\VendorOtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    /**
     * Show onboarding status/index
     */
    public function index()
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            // Create vendor record if doesn't exist
            $vendor = Vendor::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'onboarding_status' => 'incomplete',
            ]);
        }

        // Redirect based on onboarding status
        return match($vendor->onboarding_status) {
            'incomplete', 'application' => redirect()->route('vendor.onboarding.step1'),
            'documents' => redirect()->route('vendor.onboarding.step2'),
            'verification' => redirect()->route('vendor.onboarding.step3'),
            'pending' => redirect()->route('vendor.onboarding.pending'),
            'rejected' => redirect()->route('vendor.onboarding.step1'),
            'approved' => redirect()->route('vendor.onboarding.complete'),
            'complete' => redirect()->route('vendor.dashboard'),
            default => redirect()->route('vendor.onboarding.step1'),
        };
    }

    // ========================================
    // STEP 1: APPLICATION FORM
    // ========================================

    public function showStep1()
    {
        $vendor = auth()->user()->vendor;
        
        return inertia('Vendor/Onboarding/Step1Application', [
            'vendor' => $vendor,
        ]);
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255|unique:vendors,shop_name,' . auth()->user()->vendor->id,
            'shop_description' => 'required|string|min:50|max:1000',
            'business_type' => 'required|in:individual,company,partnership',
            'business_name' => 'required_if:business_type,company,partnership|nullable|string|max:255',
            'business_registration_number' => 'required_if:business_type,company,partnership|nullable|string|max:100',
            'business_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
        ]);

        $vendor = auth()->user()->vendor;
        $vendor->update([
            ...$validated,
            'onboarding_status' => 'documents',
        ]);

        return redirect()->route('vendor.onboarding.step2')
            ->with('success', 'Application submitted! Please upload required documents.');
    }

    // ========================================
    // STEP 2: DOCUMENT UPLOAD
    // ========================================

    public function showStep2()
    {
        $vendor = auth()->user()->vendor;
        
        // Redirect if step 1 not completed
        if (in_array($vendor->onboarding_status, ['incomplete', 'application'])) {
            return redirect()->route('vendor.onboarding.step1')
                ->with('error', 'Please complete the application form first.');
        }

        return inertia('Vendor/Onboarding/Step2Documents', [
            'vendor' => $vendor,
        ]);
    }

    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            // NID
            'nid_number' => 'required|string|max:50',
            'nid_front_image' => 'required|image|mimes:jpeg,jpg,png,pdf|max:5120', // 5MB
            'nid_back_image' => 'required|image|mimes:jpeg,jpg,png,pdf|max:5120',
            
            // Trade License
            'trade_license_number' => 'required|string|max:100',
            'trade_license_image' => 'required|image|mimes:jpeg,jpg,png,pdf|max:5120',
            'trade_license_expiry' => 'required|date|after:today',
            
            // Bank Details
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:255',
            'bank_branch' => 'required|string|max:255',
            'bank_routing_number' => 'nullable|string|max:50',
        ]);

        $vendor = auth()->user()->vendor;

        // Upload NID images
        if ($request->hasFile('nid_front_image')) {
            $validated['nid_front_image'] = $request->file('nid_front_image')
                ->store('vendor-documents/nid', 'public');
        }

        if ($request->hasFile('nid_back_image')) {
            $validated['nid_back_image'] = $request->file('nid_back_image')
                ->store('vendor-documents/nid', 'public');
        }

        // Upload Trade License
        if ($request->hasFile('trade_license_image')) {
            $validated['trade_license_image'] = $request->file('trade_license_image')
                ->store('vendor-documents/trade-license', 'public');
        }

        $vendor->update([
            ...$validated,
            'onboarding_status' => 'verification',
        ]);

        return redirect()->route('vendor.onboarding.step3')
            ->with('success', 'Documents uploaded! Please verify your phone number.');
    }

    // ========================================
    // STEP 3: PHONE VERIFICATION (OTP)
    // ========================================

    public function showStep3()
    {
        $vendor = auth()->user()->vendor;
        
        // Redirect if previous steps not completed
        if (in_array($vendor->onboarding_status, ['incomplete', 'application', 'documents'])) {
            return redirect()->route('vendor.onboarding.index')
                ->with('error', 'Please complete previous steps first.');
        }

        return inertia('Vendor/Onboarding/Step3Verification', [
            'vendor' => $vendor,
            'phone' => $vendor->contact_phone,
        ]);
    }

    public function sendOtp(Request $request)
    {
        $vendor = auth()->user()->vendor;

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Save OTP (expires in 10 minutes)
        $vendor->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via email (in production, use SMS gateway)
        try {
            auth()->user()->notify(new VendorOtpNotification($otp));
            
            return back()->with('success', 'OTP sent to your email!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $vendor = auth()->user()->vendor;

        // Check if OTP is correct and not expired
        if ($vendor->otp_code != $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP code.']);
        }

        if (now()->isAfter($vendor->otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        // Mark phone as verified and submit application
        $vendor->update([
            'phone_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
            'onboarding_status' => 'pending',
        ]);

        return redirect()->route('vendor.onboarding.pending')
            ->with('success', 'Phone verified! Your application is now pending admin approval.');
    }

    // ========================================
    // STEP 4: PENDING APPROVAL
    // ========================================

    public function showPending()
    {
        $vendor = auth()->user()->vendor;

        if ($vendor->onboarding_status === 'rejected') {
            return inertia('Vendor/Onboarding/Rejected', [
                'vendor' => $vendor,
                'reason' => $vendor->rejection_reason,
            ]);
        }

        if ($vendor->onboarding_status === 'approved') {
            return redirect()->route('vendor.onboarding.complete');
        }

        return inertia('Vendor/Onboarding/Pending', [
            'vendor' => $vendor,
        ]);
    }

    // ========================================
    // STEP 5: COMPLETE PROFILE
    // ========================================

    public function showComplete()
    {
        $vendor = auth()->user()->vendor;

        if ($vendor->onboarding_status !== 'approved') {
            return redirect()->route('vendor.onboarding.index');
        }

        return inertia('Vendor/Onboarding/CompleteProfile', [
            'vendor' => $vendor,
        ]);
    }

    public function storeComplete(Request $request)
    {
        $validated = $request->validate([
            'shop_logo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'shop_banner' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'business_hours' => 'nullable|array',
        ]);

        $vendor = auth()->user()->vendor;

        // Upload shop logo
        if ($request->hasFile('shop_logo')) {
            // Delete old logo if exists
            if ($vendor->shop_logo) {
                Storage::disk('public')->delete($vendor->shop_logo);
            }
            $validated['shop_logo'] = $request->file('shop_logo')
                ->store('vendor-shops/logos', 'public');
        }

        // Upload shop banner
        if ($request->hasFile('shop_banner')) {
            // Delete old banner if exists
            if ($vendor->shop_banner) {
                Storage::disk('public')->delete($vendor->shop_banner);
            }
            $validated['shop_banner'] = $request->file('shop_banner')
                ->store('vendor-shops/banners', 'public');
        }

        $vendor->update([
            ...$validated,
            'onboarding_status' => 'complete',
            'status' => 'active',
        ]);

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Congratulations! Your shop is now ready. Start adding products!');
    }
}
