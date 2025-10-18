<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'order_number',
        'customer_id',
        'vendor_id',
        'subtotal',
        'tax',
        'shipping_cost',
        'discount',
        'cod_fee',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'payment_gateway',
        'transaction_id',
        'paid_at',
        'shipping_address',
        'shipping_method',
        'shipping_zone',
        'courier_name',
        'tracking_number',
        'tracking_url',
        'courier_response',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_note',
        'vendor_note',
        'admin_note',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
        'is_cod',
        'cod_verified',
        'cod_verified_at',
        'ip_address',
        'user_agent',
        'risk_score',
        'requires_verification',
        'flagged_for_review',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'cod_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipping_address' => 'array',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'is_cod' => 'boolean',
        'cod_verified' => 'boolean',
        'cod_verified_at' => 'datetime',
        'risk_score' => 'integer',
        'requires_verification' => 'boolean',
        'flagged_for_review' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->uuid)) {
                $order->uuid = (string) Str::uuid();
            }
            if (empty($order->order_number)) {
                $order->order_number = 'VBD-' . date('Y') . '-' . str_pad(self::max('id') + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function commissionLedger()
    {
        return $this->hasOne(CommissionLedger::class);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
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

    public function isShipped()
    {
        return $this->status === 'shipped';
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isUnpaid()
    {
        return $this->payment_status === 'unpaid';
    }

    // Actions
    public function markAsPaid($transactionId = null, $gateway = null)
    {
        $this->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'transaction_id' => $transactionId,
            'payment_gateway' => $gateway,
        ]);
    }

    public function confirm()
    {
        $this->update([
            'status' => 'processing',
            'confirmed_at' => now(),
        ]);
    }

    public function ship($courierName, $trackingNumber, $trackingUrl = null)
    {
        $this->update([
            'status' => 'shipped',
            'courier_name' => $courierName,
            'tracking_number' => $trackingNumber,
            'tracking_url' => $trackingUrl,
            'shipped_at' => now(),
        ]);
    }

    public function deliver()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);
    }

    // Helpers
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'payment_pending', 'processing']);
    }

    public function canBeRefunded()
    {
        return $this->isPaid() && !in_array($this->status, ['refunded', 'cancelled']);
    }
}
