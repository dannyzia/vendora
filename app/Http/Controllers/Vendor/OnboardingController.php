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

            // International Address Structure
            'country' => 'required|string|max:100',
            'business_address' => 'required|string|max:500',
            'state_province_region' => 'required|string|max:100',
            'district_county' => 'nullable|string|max:100',
            'city_municipality' => 'required|string|max:100',
            'area_neighborhood' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',

            // Contact Information
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
            ->with('success', 'Application submitted! Please upload your documents.');
    }

    // ========================================
    // STEP 2: DOCUMENT UPLOAD
    // ========================================

    public function showStep2()
    {
        $vendor = auth()->user()->vendor;

        if ($vendor->onboarding_status === 'incomplete') {
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
            // NID - 2MB limit
            'nid_number' => 'required|string|max:50',
            'nid_front_image' => 'required|image|mimes:jpeg,jpg,png,pdf|max:2048',
            'nid_back_image' => 'required|image|mimes:jpeg,jpg,png,pdf|max:2048',

            // Trade License - 2MB limit
            'trade_license_number' => 'required|string|max:100',
            'trade_license_image' => 'required|image|mimes:jpeg,jpg,png,pdf|max:2048',
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
    // STEP 3: PHONE VERIFICATION
    // ========================================

    public function showStep3()
    {
        $vendor = auth()->user()->vendor;

        if (in_array($vendor->onboarding_status, ['incomplete', 'application'])) {
            return redirect()->route('vendor.onboarding.step1')
                ->with('error', 'Please complete previous steps first.');
        }

        return inertia('Vendor/Onboarding/Step3Verification', [
            'vendor' => $vendor,
        ]);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $vendor = auth()->user()->vendor;

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        $vendor->update([
            'contact_phone' => $request->phone,
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via SMS (implement your SMS provider)
        // For now, we'll just return it in response (ONLY FOR DEVELOPMENT)
        try {
            // auth()->user()->notify(new VendorOtpNotification($otp));

            // TODO: Implement actual SMS sending
            \Log::info("OTP for vendor {$vendor->id}: {$otp}");

        } catch (\Exception $e) {
            \Log::error('Failed to send OTP: ' . $e->getMessage());
        }

        return back()->with('success', 'OTP sent to your phone number!');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $vendor = auth()->user()->vendor;

        if (!$vendor->otp_code || !$vendor->otp_expires_at) {
            return back()->withErrors(['otp' => 'Please request an OTP first.']);
        }

        if ($vendor->otp_expires_at < now()) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        if ($vendor->otp_code !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        // Mark phone as verified and submit for admin review
        $vendor->update([
            'phone_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
            'onboarding_status' => 'pending', // Submit for admin review
        ]);

        return redirect()->route('vendor.onboarding.pending')
            ->with('success', 'Phone verified! Your application has been submitted for review.');
    }

    // ========================================
    // STEP 4: PENDING APPROVAL
    // ========================================

    public function showPending()
    {
        $vendor = auth()->user()->vendor;

        return inertia('Vendor/Onboarding/Pending', [
            'vendor' => $vendor,
        ]);
    }

    // ========================================
    // STEP 5: COMPLETE PROFILE (After Approval)
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
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024', // 1MB for logo
            'banner' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // 2MB for banner
            'bio' => 'nullable|string|max:1000',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:255',
            'bkash_number' => 'nullable|string|max:20',
            'nagad_number' => 'nullable|string|max:20',
        ]);

        $vendor = auth()->user()->vendor;

        // Store logo
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('vendors/logos', 'public');
            $validated['logo'] = $path;
        }

        // Store banner
        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('vendors/banners', 'public');
            $validated['banner'] = $path;
        }

        $vendor->update([
            ...$validated,
            'onboarding_status' => 'complete',
        ]);

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Welcome to Vendora! Your vendor account is now active.');
    }
}
