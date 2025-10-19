<?php

namespace Plugins\DisputeResolution\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'vendor_id',
        'dispute_type',
        'status',
        'customer_description',
        'customer_evidence',
        'customer_resolution',
        'customer_requested_amount',
        'vendor_response',
        'vendor_evidence',
        'vendor_resolution',
        'vendor_offered_amount',
        'vendor_responded_at',
        'admin_decision',
        'resolution_type',
        'refund_amount',
        'resolved_by',
        'resolved_at',
        'customer_rating',
        'vendor_rating',
        'vendor_response_deadline',
        'admin_decision_deadline',
        'auto_escalated',
        'escalated_at',
        'messages_count',
    ];

    protected $casts = [
        'customer_evidence' => 'array',
        'customer_requested_amount' => 'decimal:2',
        'vendor_evidence' => 'array',
        'vendor_offered_amount' => 'decimal:2',
        'vendor_responded_at' => 'datetime',
        'refund_amount' => 'decimal:2',
        'resolved_at' => 'datetime',
        'customer_rating' => 'integer',
        'vendor_rating' => 'integer',
        'vendor_response_deadline' => 'datetime',
        'admin_decision_deadline' => 'datetime',
        'auto_escalated' => 'boolean',
        'escalated_at' => 'datetime',
        'messages_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($dispute) {
            // Set deadlines based on config
            $dispute->vendor_response_deadline = now()->addHours(config('vendora.disputes.vendor_response_deadline_hours', 48));
            $dispute->admin_decision_deadline = now()->addHours(config('vendora.disputes.admin_decision_deadline_hours', 72));
        });
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Status checks
    public function isOpen()
    {
        return $this->status === 'open';
    }

    public function isVendorResponded()
    {
        return $this->status === 'vendor_responded';
    }

    public function isUnderAdminReview()
    {
        return $this->status === 'under_admin_review';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    public function isClosed()
    {
        return $this->status === 'closed';
    }

    // Actions
    public function vendorRespond($response, $evidence = null, $resolution = 'dispute', $offeredAmount = null)
    {
        $this->update([
            'vendor_response' => $response,
            'vendor_evidence' => $evidence,
            'vendor_resolution' => $resolution,
            'vendor_offered_amount' => $offeredAmount,
            'vendor_responded_at' => now(),
            'status' => 'vendor_responded',
        ]);
    }

    public function escalateToAdmin()
    {
        $this->update([
            'status' => 'under_admin_review',
            'auto_escalated' => !$this->vendor_responded_at,
            'escalated_at' => now(),
        ]);
    }

    public function resolve($resolutionType, $refundAmount = null, $adminDecision = null, $resolvedBy = null)
    {
        $this->update([
            'status' => 'resolved',
            'resolution_type' => $resolutionType,
            'refund_amount' => $refundAmount,
            'admin_decision' => $adminDecision,
            'resolved_by' => $resolvedBy,
            'resolved_at' => now(),
        ]);

        // Update vendor trust score
        $this->vendor->decrement('trust_score', config('vendora.disputes.trust_score_impact', 10));
    }

    public function close()
    {
        $this->update([
            'status' => 'closed',
        ]);
    }

    // Deadlines
    public function isVendorResponseOverdue()
    {
        return !$this->vendor_responded_at && $this->vendor_response_deadline->isPast();
    }

    public function isAdminDecisionOverdue()
    {
        return $this->isUnderAdminReview() && $this->admin_decision_deadline->isPast();
    }
}
