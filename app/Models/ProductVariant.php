<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'attributes',
        'price',
        'stock_quantity',
        'image',
        'is_active',
    ];

    protected $casts = [
        'attributes' => 'array',
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function hasStock()
    {
        return $this->stock_quantity > 0;
    }

    public function decrementStock($quantity = 1)
    {
        $this->decrement('stock_quantity', $quantity);
    }

    public function incrementStock($quantity = 1)
    {
        $this->increment('stock_quantity', $quantity);
    }

    public function getAttributeStringAttribute()
    {
        if (!$this->attributes) {
            return '';
        }

        return collect($this->attributes)
            ->map(fn($value, $key) => ucfirst($key) . ': ' . $value)
            ->implode(', ');
    }

    public function getFinalPriceAttribute()
    {
        return $this->price ?? $this->product->price;
    }
}
