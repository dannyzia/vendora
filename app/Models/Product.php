<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Plugins\ReviewRating\Models\Review;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'title',
        'title_bn',
        'slug',
        'sku',
        'type',
        'description',
        'description_bn',
        'short_description',
        'price',
        'compare_at_price',
        'cost_price',
        'discount_amount',
        'discount_type',
        'discount_start_at',
        'discount_end_at',
        'images',
        'thumbnail',
        'status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'meta_title',
        'meta_description',
        'focus_keywords',
        'view_count',
        'sales_count',
        'rating_avg',
        'rating_count',
        'productable_type',
        'productable_id',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_start_at' => 'datetime',
        'discount_end_at' => 'datetime',
        'images' => 'array',
        'approved_at' => 'datetime',
        'view_count' => 'integer',
        'sales_count' => 'integer',
        'rating_avg' => 'decimal:2',
        'rating_count' => 'integer',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title);
            }
            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(Str::random(8));
            }
        });
    }

    // Polymorphic relationship
    public function productable()
    {
        return $this->morphTo();
    }

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePhysical($query)
    {
        return $query->where('type', 'physical');
    }

    public function scopeDigital($query)
    {
        return $query->where('type', 'digital');
    }

    public function scopeService($query)
    {
        return $query->where('type', 'service');
    }

    // Type checks
    public function isPhysical()
    {
        return $this->type === 'physical';
    }

    public function isDigital()
    {
        return $this->type === 'digital';
    }

    public function isService()
    {
        return $this->type === 'service';
    }

    // Status checks
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // Price calculations
    public function getFinalPriceAttribute()
    {
        if ($this->hasActiveDiscount()) {
            if ($this->discount_type === 'percentage') {
                return $this->price - ($this->price * $this->discount_amount / 100);
            }
            return $this->price - $this->discount_amount;
        }
        return $this->price;
    }

    public function hasActiveDiscount()
    {
        if (!$this->discount_amount) {
            return false;
        }

        $now = now();
        $startsValid = !$this->discount_start_at || $this->discount_start_at->isPast();
        $endsValid = !$this->discount_end_at || $this->discount_end_at->isFuture();

        return $startsValid && $endsValid;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_amount;
        }
        if ($this->discount_type === 'fixed' && $this->price > 0) {
            return ($this->discount_amount / $this->price) * 100;
        }
        return 0;
    }

    // Stock management (for physical products)
    public function hasStock()
    {
        if ($this->isPhysical()) {
            return $this->productable && $this->productable->stock_quantity > 0;
        }
        return true;
    }

    public function incrementViews()
    {
        $this->increment('view_count');
    }

    public function incrementSales($quantity = 1)
    {
        $this->increment('sales_count', $quantity);
    }
}
