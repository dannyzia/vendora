<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CommissionLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('customer_id', auth()->id())
            ->with(['vendor', 'items'])
            ->latest()
            ->paginate(10);

        return inertia('Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function show($uuid)
    {
        $order = Order::where('uuid', $uuid)
            ->where('customer_id', auth()->id())
            ->with(['vendor', 'items.product'])
            ->firstOrFail();

        return inertia('Orders/Show', [
            'order' => $order,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|array',
            'shipping_address.name' => 'required|string',
            'shipping_address.phone' => 'required|string',
            'shipping_address.address_line_1' => 'required|string',
            'shipping_address.landmark' => 'required|string',
            'shipping_address.district' => 'required|string',
            'shipping_address.division' => 'required|string',
            'payment_method' => 'required|in:bkash,sslcommerz,cod',
            'customer_note' => 'nullable|string',
        ]);

        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors(['cart' => 'Cart is empty']);
        }

        // Group cart items by vendor
        $itemsByVendor = $cartItems->groupBy('vendor_id');

        DB::beginTransaction();
        try {
            $orders = [];

            foreach ($itemsByVendor as $vendorId => $items) {
                $subtotal = $items->sum('subtotal');
                $shippingCost = $this->calculateShipping($validated['shipping_address'], $items);
                $codFee = $validated['payment_method'] === 'cod' ? 50 : 0;
                $total = $subtotal + $shippingCost + $codFee;

                // Create order
                $order = Order::create([
                    'customer_id' => auth()->id(),
                    'vendor_id' => $vendorId,
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'cod_fee' => $codFee,
                    'total' => $total,
                    'payment_method' => $validated['payment_method'],
                    'shipping_address' => $validated['shipping_address'],
                    'customer_name' => $validated['shipping_address']['name'],
                    'customer_email' => auth()->user()->email,
                    'customer_phone' => $validated['shipping_address']['phone'],
                    'customer_note' => $validated['customer_note'],
                    'is_cod' => $validated['payment_method'] === 'cod',
                    'status' => $validated['payment_method'] === 'cod' ? 'pending' : 'payment_pending',
                ]);

                // Create order items
                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'vendor_id' => $vendorId,
                        'product_name' => $item->product->title,
                        'product_sku' => $item->product->sku,
                        'product_type' => $item->product->type,
                        'product_image' => $item->product->thumbnail,
                        'unit_price' => $item->price,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->subtotal,
                        'total' => $item->subtotal,
                    ]);

                    // Deduct stock for physical products
                    if ($item->product->isPhysical()) {
                        $item->product->productable->decrementStock($item->quantity);
                    }
                }

                $orders[] = $order;
            }

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            // Redirect to payment or order confirmation
            if ($validated['payment_method'] === 'cod') {
                return redirect()->route('orders.success', ['orders' => collect($orders)->pluck('uuid')]);
            }

            return redirect()->route('payment.process', ['order' => $orders[0]->uuid]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Order creation failed. Please try again.']);
        }
    }

    public function track(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string',
            'phone' => 'required|string',
        ]);

        $order = Order::where('order_number', $validated['order_number'])
            ->where('customer_phone', $validated['phone'])
            ->with(['vendor', 'items'])
            ->first();

        if (!$order) {
            return back()->withErrors(['order_number' => 'Order not found']);
        }

        return inertia('Orders/Track', [
            'order' => $order,
        ]);
    }

    protected function calculateShipping($address, $items)
    {
        // Simplified shipping calculation based on zone
        $zone = $this->determineShippingZone($address['district']);
        $baseRate = config("vendora.shipping.zones.{$zone}.base_rate", 100);

        // Calculate total weight for physical products
        $totalWeight = $items->sum(function($item) {
            if ($item->product->isPhysical()) {
                return $item->product->productable->weight * $item->quantity;
            }
            return 0;
        });

        // Add weight-based pricing
        $weightSurcharge = 0;
        if ($totalWeight > 1) {
            $weightSurcharge = min(floor($totalWeight), 5) * 20;
        }

        return $baseRate + $weightSurcharge;
    }

    protected function determineShippingZone($district)
    {
        $zones = [
            'dhaka_metro' => ['Dhaka'],
            'dhaka_suburbs' => ['Gazipur', 'Narayanganj', 'Savar'],
            'divisional_cities' => ['Chittagong', 'Sylhet', 'Rajshahi', 'Khulna', 'Barisal', 'Rangpur', 'Mymensingh'],
        ];

        foreach ($zones as $zone => $districts) {
            if (in_array($district, $districts)) {
                return $zone;
            }
        }

        return 'district_towns';
    }
}
