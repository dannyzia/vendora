<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_quantity',
        'low_stock_threshold',
        'track_inventory',
        'allow_backorders',
        'sold_individually',
        'weight',
        'length',
        'width',
        'height',
        'shipping_class',
        'color',
        'size',
        'material',
        'brand',
        'manufacturer',
        'country_of_origin',
        'has_warranty',
        'warranty_period_months',
        'warranty_details',
        'returnable',
        'return_period_days',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'track_inventory' => 'boolean',
        'allow_backorders' => 'boolean',
        'sold_individually' => 'boolean',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'has_warranty' => 'boolean',
        'warranty_period_months' => 'integer',
        'returnable' => 'boolean',
        'return_period_days' => 'integer',
    ];

    // Polymorphic relationship
    public function product()
    {
        return $this->morphOne(Product::class, 'productable');
    }

    // Stock management
    public function hasStock()
    {
        return $this->stock_quantity > 0;
    }

    public function isLowStock()
    {
        return $this->track_inventory && $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function canOrder($quantity = 1)
    {
        if (!$this->track_inventory) {
            return true;
        }

        if ($this->allow_backorders) {
            return true;
        }

        return $this->stock_quantity >= $quantity;
    }

    public function decrementStock($quantity = 1)
    {
        if ($this->track_inventory) {
            $this->decrement('stock_quantity', $quantity);
        }
    }

    public function incrementStock($quantity = 1)
    {
        if ($this->track_inventory) {
            $this->increment('stock_quantity', $quantity);
        }
    }

    // Shipping
    public function getVolumetricWeightAttribute()
    {
        // Standard formula: (L × W × H) / 5000
        if ($this->length && $this->width && $this->height) {
            return ($this->length * $this->width * $this->height) / 5000;
        }
        return $this->weight;
    }

    public function getBillableWeightAttribute()
    {
        return max($this->weight ?? 0, $this->volumetric_weight ?? 0);
    }
}
