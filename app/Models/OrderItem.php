<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'vendor_id',
        'product_name',
        'product_sku',
        'product_type',
        'product_image',
        'variant_details',
        'unit_price',
        'quantity',
        'subtotal',
        'discount',
        'total',
        'download_url',
        'download_count',
        'download_limit',
        'download_expires_at',
        'license_key',
        'booking_date',
        'booking_time',
        'booking_duration_minutes',
        'booking_status',
        'booking_notes',
        'meeting_link',
        'fulfillment_status',
        'is_returnable',
        'is_returned',
        'refund_amount',
        'refunded_at',
    ];

    protected $casts = [
        'variant_details' => 'array',
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'download_count' => 'integer',
        'download_limit' => 'integer',
        'download_expires_at' => 'datetime',
        'booking_date' => 'datetime',
        'booking_duration_minutes' => 'integer',
        'is_returnable' => 'boolean',
        'is_returned' => 'boolean',
        'refund_amount' => 'decimal:2',
        'refunded_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }

    // Type checks
    public function isPhysical()
    {
        return $this->product_type === 'physical';
    }

    public function isDigital()
    {
        return $this->product_type === 'digital';
    }

    public function isService()
    {
        return $this->product_type === 'service';
    }

    // Status checks
    public function isPending()
    {
        return $this->fulfillment_status === 'pending';
    }

    public function isProcessing()
    {
        return $this->fulfillment_status === 'processing';
    }

    public function isShipped()
    {
        return $this->fulfillment_status === 'shipped';
    }

    public function isDelivered()
    {
        return $this->fulfillment_status === 'delivered';
    }

    public function isCompleted()
    {
        return $this->fulfillment_status === 'completed';
    }

    // Digital product methods
    public function canDownload()
    {
        if (!$this->isDigital()) {
            return false;
        }

        // Check download limit
        if ($this->download_limit > 0 && $this->download_count >= $this->download_limit) {
            return false;
        }

        // Check expiry
        if ($this->download_expires_at && $this->download_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    public function generateDownloadUrl()
    {
        $product = $this->product;
        if ($product && $product->isDigital()) {
            return $product->productable->generateDownloadUrl($this->id);
        }
        return null;
    }

    // Service/Booking methods
    public function createBooking()
    {
        if (!$this->isService()) {
            return null;
        }

        return Booking::create([
            'order_item_id' => $this->id,
            'service_product_id' => $this->product->productable_id,
            'customer_id' => $this->order->customer_id,
            'vendor_id' => $this->vendor_id,
            'booking_date' => $this->booking_date,
            'booking_time' => $this->booking_time,
            'duration_minutes' => $this->booking_duration_minutes,
            'status' => 'pending',
        ]);
    }
}
