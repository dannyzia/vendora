<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'customer_id',
        'product_id',
        'ip_address',
        'user_agent',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function record($orderItemId, $customerId, $productId, $ipAddress, $userAgent)
    {
        return self::create([
            'order_item_id' => $orderItemId,
            'customer_id' => $customerId,
            'product_id' => $productId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    public static function countForOrderItem($orderItemId)
    {
        return self::where('order_item_id', $orderItemId)->count();
    }
}
