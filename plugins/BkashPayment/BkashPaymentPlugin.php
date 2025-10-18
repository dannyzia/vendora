<?php
// plugins/bkash-payment/src/BkashPaymentPlugin.php

namespace Plugins\BkashPayment;

use App\Core\Plugin\Plugin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class BkashPaymentPlugin extends Plugin
{
    /**
     * Boot the plugin
     */
    public function boot(): void
    {
        // Register payment method in checkout
        add_filter('checkout.payment_methods', [$this, 'registerPaymentMethod'], 10, 1);
        
        // Handle payment processing
        add_action('payment.process.bkash', [$this, 'processPayment'], 10, 2);
        
        // Handle refunds
        add_action('payment.refund.bkash', [$this, 'processRefund'], 10, 2);
        
        // Add admin menu item
        add_action('admin.menu.payments', [$this, 'addAdminMenu'], 10);
        
        // Add vendor dashboard widget
        add_action('vendor.dashboard.widgets', [$this, 'addDashboardWidget'], 10);
        
        // Register routes
        $this->registerRoutes();
        
        // Register views
        $this->registerViews();
    }
    
    /**
     * Register bKash as a payment method
     */
    public function registerPaymentMethod($methods)
    {
        $methods['bkash'] = [
            'id' => 'bkash',
            'title' => 'bKash',
            'description' => 'Pay securely using your bKash account',
            'icon' => $this->getAssetUrl('images/bkash-logo.svg'),
            'supports' => ['products', 'refunds', 'tokenization'],
            'countries' => ['BD'],
            'min_amount' => 10,
            'max_amount' => 25000,
            'fee' => '1.5%',
            'settlement_time' => 'T+1'
        ];
        
        return $methods;
    }
    
    /**
     * Process bKash payment
     */
    public function processPayment($order, $request)
    {
        try {
            $api = new BkashAPI($this->getCredentials());
            
            // Create payment
            $payment = $api->createPayment([
                'amount' => $order->total,
                'currency' => 'BDT',
                'intent' => 'sale',
                'reference' => $order->order_number,
                'merchantInvoiceNumber' => $order->order_number,
                'callbackURL' => route('bkash.callback', ['order' => $order->uuid])
            ]);
            
            if ($payment['statusCode'] == '0000') {
                // Save payment ID for verification
                $order->payment_meta = [
                    'bkash_payment_id' => $payment['paymentID'],
                    'bkash_payment_create_time' => $payment['paymentCreateTime']
                ];
                $order->save();
                
                // Log transaction
                $this->logTransaction($order, 'payment_initiated', $payment);
                
                return [
                    'success' => true,
                    'redirect_url' => $payment['bkashURL'],
                    'payment_id' => $payment['paymentID']
                ];
            } else {
                throw new \\Exception($payment['statusMessage'] ?? 'Payment creation failed');
            }
            
        } catch (\\Exception $e) {
            $this->log('Payment failed: ' . $e->getMessage(), 'error');
            
            return [
                'success' => false,
                'error' => 'Payment could not be processed. Please try again.',
                'details' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Process bKash refund
     */
    public function processRefund($order, $amount)
    {
        try {
            $api = new BkashAPI($this->getCredentials());
            
            $refund = $api->refundTransaction([
                'paymentID' => $order->payment_meta['bkash_payment_id'],
                'amount' => $amount,
                'trxID' => $order->payment_meta['bkash_trx_id'],
                'sku' => $order->order_number,
                'reason' => 'Customer refund request'
            ]);
            
            if ($refund['statusCode'] == '0000') {
                // Log refund
                $this->logTransaction($order, 'refund_completed', $refund);
                
                return [
                    'success' => true,
                    'refund_id' => $refund['refundTrxID'],
                    'message' => 'Refund processed successfully'
                ];
            } else {
                throw new \\Exception($refund['statusMessage'] ?? 'Refund failed');
            }
            
        } catch (\\Exception $e) {
            $this->log('Refund failed: ' . $e->getMessage(), 'error');
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Add admin menu item
     */
    public function addAdminMenu($menu)
    