<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;

class StripeService
{
    protected $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret');
        Stripe::setApiKey($this->secretKey);
    }

    public function createCheckoutSession($order, $successUrl, $cancelUrl, $lineItems = [], $metadata = [])
    {
        try {
            // Make sure URLs are absolute
            $successUrl = url($successUrl);
            $cancelUrl = url($cancelUrl);

            Log::info('Creating Stripe session with URLs', [
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'amount' => $order['grand_total'] ?? 0,
                'line_items_count' => count($lineItems)
            ]);

            // Prepare session parameters
            $sessionParams = [
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}&order_number=' . $order['order_number'],
                'cancel_url' => $cancelUrl . '?order_number=' . $order['order_number'],
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'customer_email' => $metadata['user_email'] ?? null,
            ];

            // Add metadata if not empty (only string values)
            if (!empty($metadata)) {
                // Ensure all metadata values are strings
                $stringMetadata = [];
                foreach ($metadata as $key => $value) {
                    if (is_string($value) || is_numeric($value)) {
                        $stringMetadata[$key] = (string) $value;
                    }
                }
                $sessionParams['metadata'] = $stringMetadata;
            }

            // Add payment intent data
            $sessionParams['payment_intent_data'] = [
                'metadata' => [
                    'order_number' => $order['order_number'],
                    'cart_total' => (string) ($order['grand_total'] ?? 0),
                ]
            ];

            $session = Session::create($sessionParams);

            return [
                'success' => true,
                'session_id' => $session->id,
                'session_url' => $session->url,
            ];

        } catch (\Exception $e) {
            Log::error('Stripe Checkout Session Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function retrieveCheckoutSession($sessionId)
    {
        try {
            $session = Session::retrieve($sessionId);
            return [
                'success' => true,
                'session' => $session,
            ];
        } catch (\Exception $e) {
            Log::error('Stripe Retrieve Session Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function verifyPayment($sessionId)
    {
        try {
            $session = Session::retrieve($sessionId);

            return [
                'success' => true,
                'payment_status' => $session->payment_status,
                'status' => $session->status,
                'customer_email' => $session->customer_email,
                'amount_total' => $session->amount_total / 100,
                'currency' => $session->currency,
                'metadata' => $session->metadata,
            ];
        } catch (\Exception $e) {
            Log::error('Stripe Verify Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
