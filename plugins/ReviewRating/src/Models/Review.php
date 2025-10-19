<?php

namespace Plugins\ReviewRating\Models;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_id',
        'vendor_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'images',
        'is_verified_purchase',
        'status',
        'rejection_reason',
        'moderated_by',
        'moderated_at',
        'vendor_response',
        'vendor_responded_at',
        'helpful_count',
        'not_helpful_count',
        'is_featured',
        'is_reported',
        'report_count',
    ];

    protected $casts = [
        'rating' => 'integer',
        'images' => 'array',
        'is_verified_purchase' => 'boolean',
        'moderated_at' => 'datetime',
        'vendor_responded_at' => 'datetime',
        'helpful_count' => 'integer',
        'not_helpful_count' => 'integer',
        'is_featured' => 'boolean',
        'is_reported' => 'boolean',
        'report_count' => 'integer',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function moderatedBy()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Status checks
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isFlagged()
    {
        return $this->status === 'flagged';
    }

    // Actions
    public function approve($moderatedBy = null)
    {
        $this->update([
            'status' => 'approved',
            'moderated_by' => $moderatedBy,
            'moderated_at' => now(),
        ]);

        // Update product rating
        $this->updateProductRating();
    }

    public function reject($reason = null, $moderatedBy = null)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'moderated_by' => $moderatedBy,
            'moderated_at' => now(),
        ]);
    }

    public function flag()
    {
        $this->update([
            'status' => 'flagged',
        ]);
    }

    public function vendorRespond($response)
    {
        $this->update([
            'vendor_response' => $response,
            'vendor_responded_at' => now(),
        ]);
    }

    public function markAsHelpful()
    {
        $this->increment('helpful_count');
    }

    public function markAsNotHelpful()
    {
        $this->increment('not_helpful_count');
    }

    public function report()
    {
        $this->increment('report_count');
        $this->update([
            'is_reported' => true,
        ]);
    }

    // Update product rating
    protected function updateProductRating()
    {
        $product = $this->product;
        
        $avgRating = $product->reviews()
            ->where('status', 'approved')
            ->avg('rating');
        
        $ratingCount = $product->reviews()
            ->where('status', 'approved')
            ->count();

        $product->update([
            'rating_avg' => round($avgRating, 2),
            'rating_count' => $ratingCount,
        ]);
    }
}
