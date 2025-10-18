<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'service_product_id',
        'customer_id',
        'vendor_id',
        'booking_date',
        'booking_time',
        'duration_minutes',
        'status',
        'customer_notes',
        'vendor_notes',
        'meeting_link',
        'reminded_24h_at',
        'reminded_1h_at',
        'confirmed_at',
        'completed_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'duration_minutes' => 'integer',
        'reminded_24h_at' => 'datetime',
        'reminded_1h_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function serviceProduct()
    {
        return $this->belongsTo(ServiceProduct::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', now())
            ->whereIn('status', ['pending', 'confirmed']);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function confirm($meetingLink = null)
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'meeting_link' => $meetingLink,
        ]);
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }

    public function markAs24hReminded()
    {
        $this->update(['reminded_24h_at' => now()]);
    }

    public function markAs1hReminded()
    {
        $this->update(['reminded_1h_at' => now()]);
    }
}
