<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Raziul\Sslcommerz\Facades\Sslcommerz;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        $data = [
            'total_amount' => $order->total,
            'currency' => 'BDT',
            'tran_id' => uniqid(),
            'success_url' => route('payment.success'),
            'fail_url' => route('payment.failure'),
            'cancel_url' => route('payment.cancel'),
            'cus_name' => $order->name,
            'cus_email' => $order->email,
            'cus_add1' => $order->address,
            'cus_city' => $order->city,
            'cus_state' => $order->state,
            'cus_postcode' => $order->postal_code,
            'cus_country' => 'Bangladesh',
            'cus_phone' => $order->phone,
        ];

        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total,
            'currency' => 'BDT',
            'status' => 'pending',
            'transaction_id' => $data['tran_id'],
            'payment_gateway' => 'sslcommerz',
        ]);

        $sslc = Sslcommerz::makePayment($data, 'hosted');

        return $sslc;
    }

    public function success(Request $request)
    {
        $data = Sslcommerz::validate($request);

        if ($data) {
            $payment = Payment::where('transaction_id', $data['tran_id'])->first();
            $payment->update([
                'status' => 'success',
            ]);

            $order = $payment->order;
            $order->update([
                'status' => 'processing',
            ]);

            return redirect()->route('order.success', $order)->with('success', 'Payment successful.');
        } else {
            return redirect()->route('payment.failure')->with('error', 'Payment failed.');
        }
    }

    public function failure(Request $request)
    {
        return redirect()->route('home')->with('error', 'Payment failed.');
    }

    public function cancel(Request $request)
    {
        return redirect()->route('home')->with('error', 'Payment cancelled.');
    }
}
