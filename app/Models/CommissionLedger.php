<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'vendor_id',
        'order_total',
        'commission_rate',
        'commission_type',
        'commission_amount',
        'vendor_earnings',
        'platform_fee',
        'payment_gateway_fee',
        'status',
        'escrow_released_at',
        'calculated_at',
        'confirmed_at',
        'is_adjustment',
        'original_ledger_id',
        'adjustment_reason',
        'payout_id',
        'is_paid_out',
        'paid_out_at',
    ];

    protected $casts = [
        'order_total' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'vendor_earnings' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'payment_gateway_fee' => 'decimal:2',
        'escrow_released_at' => 'datetime',
        'calculated_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'is_adjustment' => 'boolean',
        'is_paid_out' => 'boolean',
        'paid_out_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function payout()
    {
        return $this->belongsTo(Payout::class);
    }

    public function originalLedger()
    {
        return $this->belongsTo(CommissionLedger::class, 'original_ledger_id');
    }

    public function adjustments()
    {
        return $this->hasMany(CommissionLedger::class, 'original_ledger_id');
    }

    // Status checks
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isReversed()
    {
        return $this->status === 'reversed';
    }

    // Actions
    public function confirm()
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'escrow_released_at' => now(),
        ]);

        // Update vendor balance
        $this->vendor->increment('available_balance', $this->vendor_earnings);
        $this->vendor->decrement('pending_balance', $this->vendor_earnings);
    }

    public function reverse($reason = null)
    {
        $this->update([
            'status' => 'reversed',
        ]);

        // Create adjustment entry
        self::create([
            'order_id' => $this->order_id,
            'vendor_id' => $this->vendor_id,
            'order_total' => $this->order_total,
            'commission_rate' => $this->commission_rate,
            'commission_type' => $this->commission_type,
            'commission_amount' => -$this->commission_amount,
            'vendor_earnings' => -$this->vendor_earnings,
            'status' => 'confirmed',
            'is_adjustment' => true,
            'original_ledger_id' => $this->id,
            'adjustment_reason' => $reason,
            'calculated_at' => now(),
            'confirmed_at' => now(),
        ]);

        // Update vendor balance
        $this->vendor->decrement('available_balance', $this->vendor_earnings);
    }

    public function markAsPaidOut($payoutId)
    {
        $this->update([
            'payout_id' => $payoutId,
            'is_paid_out' => true,
            'paid_out_at' => now(),
        ]);
    }

    // Calculation helpers
    public static function calculateCommission($orderTotal, $commissionRate, $commissionType = 'percentage')
    {
        switch ($commissionType) {
            case 'percentage':
                return ($orderTotal * $commissionRate) / 100;
            case 'flat':
                return $commissionRate;
            case 'hybrid':
                // Assumes format: percentage + flat (e.g., 10% + 20 BDT)
                return ($orderTotal * $commissionRate) / 100;
            default:
                return ($orderTotal * $commissionRate) / 100;
        }
    }
}
