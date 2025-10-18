<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'payout_number',
        'amount',
        'fee',
        'net_amount',
        'method',
        'method_details',
        'status',
        'transaction_reference',
        'processing_note',
        'failure_reason',
        'schedule_type',
        'scheduled_at',
        'processed_at',
        'completed_at',
        'processed_by',
        'is_on_hold',
        'hold_reason',
        'hold_until',
        'commission_count',
        'commission_ids',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'method_details' => 'array',
        'scheduled_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_on_hold' => 'boolean',
        'hold_until' => 'datetime',
        'commission_count' => 'integer',
        'commission_ids' => 'array',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($payout) {
            if (empty($payout->payout_number)) {
                $payout->payout_number = 'PO-' . date('Y') . '-' . str_pad(self::max('id') + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function commissionLedgers()
    {
        return $this->hasMany(CommissionLedger::class);
    }

    // Status checks
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isOnHold()
    {
        return $this->is_on_hold;
    }

    // Actions
    public function markAsProcessing($processedBy = null)
    {
        $this->update([
            'status' => 'processing',
            'processed_at' => now(),
            'processed_by' => $processedBy,
        ]);
    }

    public function markAsCompleted($transactionReference = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'transaction_reference' => $transactionReference,
        ]);

        // Deduct from vendor balance
        $this->vendor->decrement('available_balance', $this->amount);
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);
    }

    public function hold($reason, $until = null)
    {
        $this->update([
            'is_on_hold' => true,
            'hold_reason' => $reason,
            'hold_until' => $until,
        ]);
    }

    public function release()
    {
        $this->update([
            'is_on_hold' => false,
            'hold_reason' => null,
            'hold_until' => null,
        ]);
    }
}
