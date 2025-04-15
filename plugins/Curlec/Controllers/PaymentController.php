<?php

namespace Plugin\Curlec\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class PaymentController
{
    /**
     * Create a Razorpay order and return the order details.
     * This method should be called from your payment initiation route.
     */
    public function createOrder(Request $request)
    {
        Log::debug('Curlec PaymentController: createOrder called', [
            'request_all' => $request->all(),
            'headers' => $request->headers->all(),
            'is_ajax' => $request->ajax(),
            'expects_json' => $request->expectsJson(),
            'accept_header' => $request->header('Accept'),
        ]);
        if (!$request->ajax() && !$request->expectsJson()) {
            Log::warning('Curlec PaymentController: Request is NOT AJAX and does NOT expect JSON', [
                'headers' => $request->headers->all()
            ]);
        }

        // Retrieve plugin settings
        $settings = plugin_setting('curlec');
        Log::debug('Curlec PaymentController: plugin_setting(curlec)', [
            'settings' => $settings
        ]);
        $keyId = $settings['key_id'] ?? '';
        $keySecret = $settings['key_secret'] ?? '';
        $testMode = $settings['test_mode'] ?? true;

        // Initialize Razorpay API
        Log::debug('Curlec PaymentController: Initializing Razorpay API', [
            'key_id' => $keyId,
            'test_mode' => $testMode
        ]);
        $api = new Api($keyId, $keySecret);

        // Prepare order data
        $orderData = [
            'receipt'         => $request->input('receipt', uniqid('curlec_')),
            'amount'          => $request->input('amount'), // Amount in paise (e.g., 1000 = ₹10)
            'currency'        => $request->input('currency', 'MYR'),
            'payment_capture' => 1 // Auto-capture
        ];
        Log::debug('Curlec PaymentController: Prepared order data', [
            'orderData' => $orderData
        ]);

        try {
            // Create order
            Log::debug('Curlec PaymentController: Calling $api->order->create');
            $order = $api->order->create($orderData);
            // Log the full Curlec/Razorpay response
            Log::debug('Curlec PaymentController: Curlec API Order Response', [
                'order' => $order
            ]);

            // Return order details (for frontend to use in Razorpay Checkout)
            $response = [
                'order_id' => $order['id'],
                'amount'   => $order['amount'],
                'currency' => $order['currency'],
                'key_id'   => $keyId,
                'test_mode'=> $testMode
            ];
            Log::debug('Curlec PaymentController: Returning JSON response', [
                'response' => $response
            ]);
            return response()->json($response);
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Curlec PaymentController: Curlec API Order Exception', [
                'message' => $e->getMessage(),
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            $errorResponse = [
                'error' => 'Failed to create Curlec order',
                'message' => $e->getMessage(),
            ];
            Log::debug('Curlec PaymentController: Returning ERROR JSON response', [
                'response' => $errorResponse
            ]);
            return response()->json($errorResponse, 500);
        }
    }
}