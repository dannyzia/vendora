<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'vendor_id',
        'quantity',
        'variant_details',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'variant_details' => 'array',
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function incrementQuantity($amount = 1)
    {
        $this->increment('quantity', $amount);
    }

    public function decrementQuantity($amount = 1)
    {
        if ($this->quantity > $amount) {
            $this->decrement('quantity', $amount);
        } else {
            $this->delete();
        }
    }

    public function updateQuantity($quantity)
    {
        if ($quantity <= 0) {
            $this->delete();
        } else {
            $this->update(['quantity' => $quantity]);
        }
    }
}
