<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Notifications\VendorApprovedNotification;
use App\Notifications\VendorRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorApplicationController extends Controller
{
    /**
     * Display a list of vendor applications
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');

        $applications = Vendor::with('user')
            ->when($status === 'pending', function ($query) {
                $query->where('onboarding_status', 'pending');
            })
            ->when($status === 'approved', function ($query) {
                $query->where('onboarding_status', 'approved');
            })
            ->when($status === 'rejected', function ($query) {
                $query->where('onboarding_status', 'rejected');
            })
            ->when($status === 'all', function ($query) {
                $query->whereIn('onboarding_status', ['pending', 'approved', 'rejected']);
            })
            ->latest()
            ->paginate(20);

        $stats = [
            'pending' => Vendor::where('onboarding_status', 'pending')->count(),
            'approved' => Vendor::where('onboarding_status', 'approved')->count(),
            'rejected' => Vendor::where('onboarding_status', 'rejected')->count(),
        ];

        return inertia('Admin/Vendors/Applications', [
            'applications' => $applications,
            'stats' => $stats,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Show detailed view of a vendor application
     */
    public function show(Vendor $vendor)
    {
        $vendor->load('user');

        return inertia('Admin/Vendors/ApplicationDetail', [
            'vendor' => $vendor,
            'nidFrontUrl' => $vendor->nid_front_image ? Storage::url($vendor->nid_front_image) : null,
            'nidBackUrl' => $vendor->nid_back_image ? Storage::url($vendor->nid_back_image) : null,
            'tradeLicenseUrl' => $vendor->trade_license_image ? Storage::url($vendor->trade_license_image) : null,
        ]);
    }

    /**
     * Approve a vendor application
     */
    public function approve(Request $request, Vendor $vendor)
    {
        if ($vendor->onboarding_status !== 'pending') {
            return back()->with('error', 'Only pending applications can be approved.');
        }

        $vendor->update([
            'onboarding_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'rejection_reason' => null,
        ]);

        // Send notification to vendor
        try {
            $vendor->user->notify(new VendorApprovedNotification($vendor));
        } catch (\Exception $e) {
            // Log error but don't fail the approval
            \Log::error('Failed to send vendor approval notification: ' . $e->getMessage());
        }

        return redirect()->route('admin.vendors.applications')
            ->with('success', 'Vendor application approved! The vendor can now complete their profile.');
    }

    /**
     * Reject a vendor application
     */
    public function reject(Request $request, Vendor $vendor)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);

        if ($vendor->onboarding_status !== 'pending') {
            return back()->with('error', 'Only pending applications can be rejected.');
        }

        $vendor->update([
            'onboarding_status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'rejection_reason' => $request->reason,
        ]);

        // Send notification to vendor
        try {
            $vendor->user->notify(new VendorRejectedNotification($vendor, $request->reason));
        } catch (\Exception $e) {
            \Log::error('Failed to send vendor rejection notification: ' . $e->getMessage());
        }

        return redirect()->route('admin.vendors.applications')
            ->with('success', 'Vendor application rejected. The vendor has been notified.');
    }

    /**
     * View a specific document (NID, Trade License)
     */
    public function viewDocument(Vendor $vendor, $type)
    {
        $allowedTypes = ['nid_front', 'nid_back', 'trade_license'];
        
        if (!in_array($type, $allowedTypes)) {
            abort(404);
        }

        $field = $type . '_image';
        $filePath = $vendor->{$field};

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        return Storage::disk('public')->response($filePath);
    }
}
