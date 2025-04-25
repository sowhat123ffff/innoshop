<?php

namespace Plugin\Curlec\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Illuminate\Http\JsonResponse;

class PaymentController
{
    /**
     * Create a Razorpay order and return the order details (JSON response).
     * Called via POST /payment/curlec/order
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createOrder(Request $request): JsonResponse
    {
        $locale = $request->route('locale') ?? app()->getLocale();
        Log::info('[Curlec] PaymentController::createOrder - Called', [
            'request' => $request->all(),
            'headers' => $request->headers->all(),
            'locale' => $locale,
            'route_params' => $request->route() ? $request->route()->parameters() : null,
            'full_url' => $request->fullUrl(),
        ]);

        // Validate input
        $validated = $request->validate([
            'receipt'  => 'required|string|max:64',
            'amount'   => 'required|integer|min:100', // Razorpay min 100 paise (RM1)
            'currency' => 'sometimes|string|size:3',
        ]);
        $receipt  = $validated['receipt'];
        $amount   = $validated['amount'];
        $currency = $validated['currency'] ?? 'MYR';

        // Retrieve plugin settings
        $settings = plugin_setting('curlec');
        $keyId     = $settings['key_id'] ?? null;
        $keySecret = $settings['key_secret'] ?? null;
        $testMode  = $settings['test_mode'] ?? true;

        if (!$keyId || !$keySecret) {
            Log::error('[Curlec] Missing API credentials', ['key_id' => $keyId, 'key_secret' => $keySecret]);
            return response()->json([
                'error' => 'Curlec plugin misconfigured: missing API credentials.'
            ], 500);
        }

        // Initialize Razorpay API
        try {
            $api = new Api($keyId, $keySecret);
        } catch (\Throwable $e) {
            Log::error('[Curlec] Failed to initialize Razorpay API', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Payment gateway unavailable.'], 500);
        }

        // Prepare order data
        $orderData = [
            'receipt'         => $receipt,
            'amount'          => $amount,
            'currency'        => $currency,
            'payment_capture' => 1,
        ];
        Log::debug('[Curlec] Order data', $orderData);

        // Create order
        try {
            $order = $api->order->create($orderData);
            Log::info('[Curlec] Order created', [
                'order_id' => $order['id'],
                'amount'   => $order['amount'],
                'currency' => $order['currency']
            ]);
            return response()->json([
                'order_id' => $order['id'],
                'amount'   => $order['amount'],
                'currency' => $order['currency'],
                'key_id'   => $keyId,
                'test_mode'=> $testMode
            ]);
        } catch (\Throwable $e) {
            Log::error('[Curlec] Order creation failed', ['error' => $e->getMessage(), 'orderData' => $orderData]);
            $routeName = $locale . '.front.curlec.payment.order';
            return response()->json([
                'error' => 'Failed to create payment order. Please try again later.'
            ], 500);
        }
    }
}