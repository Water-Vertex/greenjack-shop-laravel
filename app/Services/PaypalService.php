<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected $clientId;
    protected $clientSecret;
    protected $apiUrl;
    protected $accessToken;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->apiUrl = config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    /**
     * Get PayPal Access Token
     */
    public function getAccessToken()
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
            ->asForm()
            ->post($this->apiUrl . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->successful()) {
            $this->accessToken = $response->json()['access_token'];
            return $this->accessToken;
        }

        Log::error('PayPal Access Token Error: ' . $response->body());
        throw new \Exception('Failed to get PayPal access token');
    }

    /**
     * Create PayPal Order
     */
    public function createOrder($amount, $currency, $returnUrl, $cancelUrl, $orderNumber)
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl . '/v2/checkout/orders', [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [
                        [
                            'reference_id' => $orderNumber,
                            'description' => 'Order #' . $orderNumber,
                            'amount' => [
                                'currency_code' => $currency,
                                'value' => number_format($amount, 2, '.', ''),
                            ],
                        ],
                    ],
                    'application_context' => [
                        'return_url' => $returnUrl,
                        'cancel_url' => $cancelUrl,
                        'brand_name' => 'The Green Jack',
                        'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                        'user_action' => 'PAY_NOW',
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $approvalUrl = null;

                foreach ($data['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approvalUrl = $link['href'];
                        break;
                    }
                }

                return [
                    'success' => true,
                    'order_id' => $data['id'],
                    'approval_url' => $approvalUrl,
                ];
            }

            Log::error('PayPal Create Order Error: ' . $response->body());
            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Failed to create order',
            ];

        } catch (\Exception $e) {
            Log::error('PayPal Create Order Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Capture PayPal Order
     */
    public function captureOrder($orderId)
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl . '/v2/checkout/orders/' . $orderId . '/capture');

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data' => $data,
                ];
            }

            Log::error('PayPal Capture Order Error: ' . $response->body());
            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Failed to capture order',
            ];

        } catch (\Exception $e) {
            Log::error('PayPal Capture Order Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
