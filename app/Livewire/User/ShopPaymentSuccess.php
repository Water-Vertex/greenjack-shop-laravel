<?php

namespace App\Livewire\User;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail; // <-- ADD THIS
use App\Mail\OrderConfirmationMail; // <-- ADD THIS
use App\Mail\AdminOrderNotificationMail; // <-- ADD THIS

#[Layout('components.shop-layout')]
class ShopPaymentSuccess extends Component
{
    public $order;
    public $message;
    public $verificationStatus = 'verifying';
    public $tempOrderId;
    public $errorMessage = null;

    // Add this method to send emails
    private function sendOrderEmails($order)
    {
        try {
            // Send email to customer
            if (!empty($order->email)) {
                Mail::to($order->email)->send(new OrderConfirmationMail($order));
                Log::info('Customer order confirmation email sent', [
                    'order_id' => $order->id,
                    'email' => $order->email
                ]);
            }
            
            // Send email to admin
            $adminEmail = 'order@greenjackshop.com';
            Mail::to($adminEmail)->send(new AdminOrderNotificationMail($order));
            
            Log::info('Order confirmation emails sent successfully', [
                'order_id' => $order->id,
                'customer_email' => $order->email,
                'admin_email' => $adminEmail
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation emails', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Continue with order processing even if email fails
        }
    }

    public function mount($id = null)
    {
        if (!$id) {
            return redirect()->route('shop')->with('error', 'Invalid order reference.');
        }

        $this->tempOrderId = $id;

        Log::info('ShopPaymentSuccess mounted', [
            'id' => $id,
            'all_params' => request()->all(),
            'session_keys' => array_keys(Session::all()),
            'has_pending_checkout' => Session::has('pending_checkout'),
            'stripe_session_id' => Session::get('stripe_session_id'),
            'paypal_token' => Session::get('paypal_token'),
        ]);

        // Check for Stripe callback with session_id from session
        if (Session::has('stripe_session_id')) {
            $sessionId = Session::get('stripe_session_id');
            Session::forget('stripe_session_id');
            $this->verifyStripePayment($sessionId, $id);
            return;
        }

        // Check for PayPal callback with token from session
        if (Session::has('paypal_token')) {
            $token = Session::get('paypal_token');
            Session::forget('paypal_token');
            $this->verifyPayPalPayment($token, $id);
            return;
        }

        // Check if this is a direct Stripe callback with session_id in URL
        if (request()->has('session_id')) {
            $this->verifyStripePayment(request()->session_id, $id);
            return;
        }

        // Check if this is a direct PayPal callback with token in URL
        if (request()->has('token')) {
            $this->verifyPayPalPayment(request()->token, $id);
            return;
        }

        // Try to find the order
        $this->findOrder($id);
    }

    public function findOrder($id)
    {
        // Try to find by order number
        $this->order = Order::with(['items', 'shippingDetail'])
            ->where('order_number', $id)
            ->first();

        if ($this->order) {
            $this->verificationStatus = 'success';
            $this->message = 'Your order has been confirmed successfully!';
            return;
        }

        // Try to find by pending checkout
        $pendingData = Session::get('pending_checkout');
        if ($pendingData && isset($pendingData['order_number']) && $pendingData['order_number'] === $id) {
            $this->order = Order::with(['items', 'shippingDetail'])
                ->where('email', $pendingData['email'])
                ->latest()
                ->first();

            if ($this->order) {
                $this->verificationStatus = 'success';
                $this->message = 'Your order has been confirmed successfully!';
                return;
            }
        }

        // Try by email from session
        $customerEmail = Session::get('customer_email');
        if ($customerEmail) {
            $this->order = Order::with(['items', 'shippingDetail'])
                ->where('email', $customerEmail)
                ->latest()
                ->first();

            if ($this->order) {
                $this->verificationStatus = 'success';
                $this->message = 'Your order has been confirmed successfully!';
                return;
            }
        }

        // Try by ID
        $this->order = Order::with(['items', 'shippingDetail'])
            ->find($id);

        if ($this->order) {
            $this->verificationStatus = 'success';
            $this->message = 'Your order has been confirmed successfully!';
            return;
        }

        $this->verificationStatus = 'error';
        $this->message = 'Order not found. Please contact support.';
        $this->errorMessage = 'We could not find your order. Please contact our support team.';
    }

    public function verifyStripePayment($sessionId, $tempOrderNumber)
    {
        try {
            Log::info('Verifying Stripe payment', [
                'session_id' => $sessionId,
                'temp_order_number' => $tempOrderNumber
            ]);

            Stripe::setApiKey(config('services.stripe.secret'));
            $session = StripeSession::retrieve($sessionId);

            Log::info('Stripe session retrieved', [
                'payment_status' => $session->payment_status,
                'customer_email' => $session->customer_email,
                'payment_intent' => $session->payment_intent
            ]);

            if ($session->payment_status !== 'paid') {
                $this->verificationStatus = 'error';
                $this->message = 'Payment was not completed. Please try again.';
                $this->errorMessage = 'Payment verification failed. Status: ' . $session->payment_status;
                return;
            }

            $pendingData = Session::get('pending_checkout');

            if (!$pendingData || $pendingData['order_number'] !== $tempOrderNumber) {
                Log::warning('Pending checkout data mismatch or missing', [
                    'pending_data' => $pendingData,
                    'temp_order_number' => $tempOrderNumber
                ]);
                $this->findOrder($tempOrderNumber);
                return;
            }

            // Check if order already exists
            $existingOrder = Order::where('email', $pendingData['email'])
                ->where('payment_status', 'paid')
                ->latest()
                ->first();

            if ($existingOrder) {
                $this->order = $existingOrder;
                $this->verificationStatus = 'success';
                $this->message = 'Your order has been confirmed successfully!';
                Session::forget('pending_checkout');
                Session::forget('cart');
                Session::forget('session_id');
                return;
            }

            DB::beginTransaction();

            // Create the order
            $order = \App\Models\Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'customer_id' => $pendingData['customer_id'] ?? null,
                'first_name' => $pendingData['first_name'],
                'last_name' => $pendingData['last_name'],
                'email' => $pendingData['email'],
                'phone' => $pendingData['phone'],
                'payment_method' => 'stripe',
                'payment_status' => 'paid',
                'subtotal' => $pendingData['cart_total'],
                'shipping_cost' => $pendingData['shipping_cost'],
                'tax_amount' => $pendingData['tax_amount'],
                'grand_total' => $pendingData['grand_total'],
                'order_notes' => $pendingData['order_notes'] ?? '',
                'status' => 'processing',
                'stripe_session_id' => $sessionId,
                'stripe_payment_intent_id' => $session->payment_intent,
                'paid_at' => now(),
            ]);

            Log::info('Stripe order created', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_status' => $order->payment_status
            ]);

            // Create shipping details
            \App\Models\ShippingDetail::create([
                'order_id' => $order->id,
                'address' => $pendingData['address'],
                'apartment' => $pendingData['apartment'] ?? '',
                'city' => $pendingData['city'],
                'state' => $pendingData['state'],
                'zip_code' => $pendingData['zip_code'],
                'country' => $pendingData['country'],
                'different_shipping' => $pendingData['different_shipping_address'] ?? false,
                'shipping_address' => $pendingData['shipping_address'] ?? '',
                'shipping_apartment' => $pendingData['shipping_apartment'] ?? '',
                'shipping_city' => $pendingData['shipping_city'] ?? '',
                'shipping_state' => $pendingData['shipping_state'] ?? '',
                'shipping_zip_code' => $pendingData['shipping_zip_code'] ?? '',
                'shipping_country' => $pendingData['shipping_country'] ?? '',
            ]);

            // Create order items
            foreach ($pendingData['cart'] as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'] ?? null,
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            // SEND EMAILS HERE - AFTER ORDER IS CREATED
            $this->sendOrderEmails($order);

            // Clear session data
            Session::forget('pending_checkout');
            Session::forget('cart');
            Session::forget('session_id');

            $this->order = Order::with(['items', 'shippingDetail'])->find($order->id);
            $this->verificationStatus = 'success';
            $this->message = 'Your payment has been confirmed successfully! A confirmation email has been sent.';
            $this->dispatch('cart-updated');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stripe verification error: ' . $e->getMessage());
            Log::error('Stripe verification trace: ' . $e->getTraceAsString());
            $this->verificationStatus = 'error';
            $this->message = 'Failed to verify payment: ' . $e->getMessage();
            $this->errorMessage = 'There was an error verifying your payment. Please contact support.';
        }
    }

    public function verifyPayPalPayment($token, $tempOrderNumber)
    {
        try {
            Log::info('Verifying PayPal payment', [
                'token' => $token,
                'temp_order_number' => $tempOrderNumber
            ]);

            $clientId = config('services.paypal.client_id');
            $secret = config('services.paypal.secret');

            if (empty($clientId) || empty($secret)) {
                throw new \Exception('PayPal credentials are not properly configured.');
            }

            // Get PayPal access token
            $response = Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post(config('services.paypal.mode') === 'live'
                    ? 'https://api-m.paypal.com/v1/oauth2/token'
                    : 'https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get PayPal access token: ' . $response->body());
            }

            $accessToken = $response->json()['access_token'];

            // Capture the payment
            $captureResponse = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->send('POST',
                    config('services.paypal.mode') === 'live'
                        ? 'https://api-m.paypal.com/v2/checkout/orders/' . $token . '/capture'
                        : 'https://api-m.sandbox.paypal.com/v2/checkout/orders/' . $token . '/capture',
                    ['body' => '{}']
                );

            Log::info('PayPal capture response', [
                'successful' => $captureResponse->successful(),
                'status' => $captureResponse->status(),
                'body' => $captureResponse->body()
            ]);

            if (!$captureResponse->successful()) {
                throw new \Exception('Failed to capture PayPal payment: ' . $captureResponse->body());
            }

            $captureData = $captureResponse->json();

            if ($captureData['status'] !== 'COMPLETED') {
                $this->verificationStatus = 'error';
                $this->message = 'Payment was not completed. Please try again.';
                $this->errorMessage = 'PayPal payment was not completed.';
                return;
            }

            $pendingData = Session::get('pending_checkout');

            if (!$pendingData) {
                Log::warning('Pending checkout data missing for PayPal', [
                    'token' => $token,
                    'temp_order_number' => $tempOrderNumber
                ]);
                $this->findOrder($tempOrderNumber);
                return;
            }

            // Check if order already exists
            $existingOrder = Order::where('email', $pendingData['email'])
                ->where('payment_status', 'paid')
                ->latest()
                ->first();

            if ($existingOrder) {
                $this->order = $existingOrder;
                $this->verificationStatus = 'success';
                $this->message = 'Your order has been confirmed successfully!';
                Session::forget('pending_checkout');
                Session::forget('cart');
                return;
            }

            DB::beginTransaction();

            // Get capture details
            $captureId = $captureData['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;
            $payerId = $captureData['payer']['payer_id'] ?? null;
            $payerEmail = $captureData['payer']['email_address'] ?? null;

            $order = \App\Models\Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'customer_id' => $pendingData['customer_id'] ?? null,
                'first_name' => $pendingData['first_name'],
                'last_name' => $pendingData['last_name'],
                'email' => $pendingData['email'],
                'phone' => $pendingData['phone'],
                'payment_method' => 'paypal',
                'payment_status' => 'paid',
                'subtotal' => $pendingData['cart_total'],
                'shipping_cost' => $pendingData['shipping_cost'],
                'tax_amount' => $pendingData['tax_amount'],
                'grand_total' => $pendingData['grand_total'],
                'order_notes' => $pendingData['order_notes'] ?? '',
                'status' => 'processing',
                'paypal_order_id' => $captureData['id'],
                'paypal_capture_id' => $captureId,
                'paypal_payer_id' => $payerId,
                'paypal_payer_email' => $payerEmail,
                'paid_at' => now(),
            ]);

            Log::info('PayPal order created', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_status' => $order->payment_status,
                'capture_id' => $captureId
            ]);

            // Create shipping details
            \App\Models\ShippingDetail::create([
                'order_id' => $order->id,
                'address' => $pendingData['address'],
                'apartment' => $pendingData['apartment'] ?? '',
                'city' => $pendingData['city'],
                'state' => $pendingData['state'],
                'zip_code' => $pendingData['zip_code'],
                'country' => $pendingData['country'],
                'different_shipping' => $pendingData['different_shipping_address'] ?? false,
                'shipping_address' => $pendingData['shipping_address'] ?? '',
                'shipping_apartment' => $pendingData['shipping_apartment'] ?? '',
                'shipping_city' => $pendingData['shipping_city'] ?? '',
                'shipping_state' => $pendingData['shipping_state'] ?? '',
                'shipping_zip_code' => $pendingData['shipping_zip_code'] ?? '',
                'shipping_country' => $pendingData['shipping_country'] ?? '',
            ]);

            // Create order items
            foreach ($pendingData['cart'] as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'] ?? null,
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            // SEND EMAILS HERE - AFTER ORDER IS CREATED
            $this->sendOrderEmails($order);

            // Clear session data
            Session::forget('pending_checkout');
            Session::forget('cart');

            $this->order = Order::with(['items', 'shippingDetail'])->find($order->id);
            $this->verificationStatus = 'success';
            $this->message = 'Your payment has been confirmed successfully! A confirmation email has been sent.';
            $this->dispatch('cart-updated');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PayPal verification error: ' . $e->getMessage());
            Log::error('PayPal verification trace: ' . $e->getTraceAsString());
            $this->verificationStatus = 'error';
            $this->message = 'Failed to verify payment: ' . $e->getMessage();
            $this->errorMessage = 'There was an error verifying your payment. Please contact support.';
        }
    }

    public function render()
    {
        return view('livewire.user.shop-payment-success', [
            'order' => $this->order,
            'errorMessage' => $this->errorMessage
        ]);
    }
}