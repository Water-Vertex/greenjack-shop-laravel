<?php

namespace App\Livewire\User;

use App\Models\RepairRequest;
use App\Mail\RepairRequestConfirmation;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

#[Layout('components.user-layout')]
class PaymentSuccess extends Component
{
    public $repairRequest;
    public $message;
    public $verificationStatus = 'verifying';
    public $paymentMethod = '';

    public function mount($id = null)
    {
        Log::info('PaymentSuccess mounted', [
            'id' => $id,
            'all_params' => request()->all(),
        ]);

        if (!$id) {
            return redirect()->route('home');
        }

        // Find the repair request with ALL relationships
        $this->repairRequest = RepairRequest::with([
            'category',
            'brand',
            'model',
        ])->find($id);

        if (!$this->repairRequest) {
            Log::warning('PaymentSuccess: Repair request not found', ['id' => $id]);
            return redirect()->route('home')->with('error', 'Repair request not found.');
        }

        Log::info('PaymentSuccess: Repair request found', [
            'id' => $this->repairRequest->id,
            'category' => $this->repairRequest->category?->name,
            'brand' => $this->repairRequest->brand?->name,
            'model' => $this->repairRequest->model?->name,
            'payment_method' => $this->repairRequest->payment_method,
            'payment_status' => $this->repairRequest->payment_status,
        ]);

        $this->paymentMethod = $this->repairRequest->payment_method;

        // Check if this is a Stripe return
        if (request()->has('session_id')) {
            $this->verifyStripePayment();
            return;
        }

        // Check if this is a PayPal return
        if (request()->has('token') && request()->has('PayerID')) {
            $this->verifyPayPalPayment();
            return;
        }

        // If no payment parameters, check if payment is already completed
        if ($this->repairRequest->payment_status === 'completed') {
            $this->verificationStatus = 'success';
            $this->message = 'Your payment has been confirmed successfully!';
        } else {
            $this->verificationStatus = 'error';
            $this->message = 'Payment verification failed. Please contact support.';
        }
    }

    public function verifyStripePayment()
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = StripeSession::retrieve(request()->session_id);

            if ($session->payment_status === 'paid') {
                $this->repairRequest->update([
                    'payment_status' => 'completed',
                    'paid_at' => now(),
                    'status' => 'confirmed',
                    'stripe_payment_intent_id' => $session->payment_intent,
                ]);

                // Refresh the model to get updated data
                $this->repairRequest->refresh();

                // Reload relationships
                $this->repairRequest->load(['category', 'brand', 'model']);

                // Send confirmation email
                $this->sendConfirmationEmail();

                $this->verificationStatus = 'success';
                $this->message = 'Your payment has been confirmed successfully! A confirmation email has been sent.';
            } else {
                $this->repairRequest->update([
                    'status' => 'cancelled',
                    'payment_status' => 'failed'
                ]);
                $this->verificationStatus = 'error';
                $this->message = 'Payment was not completed. Please try again.';
            }
        } catch (\Exception $e) {
            Log::error('Stripe verification error: ' . $e->getMessage());
            $this->verificationStatus = 'error';
            $this->message = 'Payment verification failed. Please contact support.';
        }
    }

    public function verifyPayPalPayment()
    {
        try {
            $clientId = config('services.paypal.client_id');
            $secret = config('services.paypal.secret');

            if (empty($clientId) || empty($secret)) {
                throw new \Exception('PayPal credentials are not properly configured.');
            }

            // Get PayPal access token
            $tokenUrl = config('services.paypal.mode') === 'live'
                ? 'https://api-m.paypal.com/v1/oauth2/token'
                : 'https://api-m.sandbox.paypal.com/v1/oauth2/token';

            $response = Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post($tokenUrl, [
                    'grant_type' => 'client_credentials'
                ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get PayPal access token: ' . $response->body());
            }

            $accessToken = $response->json()['access_token'];
            $orderId = request()->token;

            $captureUrl = config('services.paypal.mode') === 'live'
                ? 'https://api-m.paypal.com/v2/checkout/orders/' . $orderId . '/capture'
                : 'https://api-m.sandbox.paypal.com/v2/checkout/orders/' . $orderId . '/capture';

            $captureResponse = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->send('POST', $captureUrl, [
                    'body' => '{}'
                ]);

            if (!$captureResponse->successful()) {
                throw new \Exception('Failed to capture PayPal payment: ' . $captureResponse->body());
            }

            $captureData = $captureResponse->json();

            if ($captureData['status'] === 'COMPLETED') {
                $captureId = $captureData['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;

                $this->repairRequest->update([
                    'payment_status' => 'completed',
                    'paid_at' => now(),
                    'status' => 'confirmed',
                    'paypal_capture_id' => $captureId,
                ]);

                // Refresh the model to get updated data
                $this->repairRequest->refresh();

                // Reload relationships
                $this->repairRequest->load(['category', 'brand', 'model']);

                // Send confirmation email
                $this->sendConfirmationEmail();

                $this->message = 'Your payment has been confirmed successfully! A confirmation email has been sent.';
            } else {
                $this->repairRequest->update([
                    'status' => 'cancelled',
                    'payment_status' => 'failed'
                ]);
                $this->message = 'Payment was not completed. Please try again.';
            }
        } catch (\Exception $e) {
            Log::error('PayPal verification error: ' . $e->getMessage());

            $this->repairRequest->update([
                'status' => 'failed',
                'payment_status' => 'failed'
            ]);

            $this->message = 'Payment verification failed: ' . $e->getMessage();
        }
    }

    protected function sendConfirmationEmail()
{
    try {
        // Ensure all relationships are loaded
        $this->repairRequest->load([
            'category',
            'brand',
            'model'
        ]);

        // Force SMTP configuration
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => env('MAIL_HOST', 'thegreenjack.com'),
            'mail.mailers.smtp.port' => env('MAIL_PORT', 465),
            'mail.mailers.smtp.encryption' => env('MAIL_ENCRYPTION', 'ssl'),
            'mail.mailers.smtp.username' => env('MAIL_USERNAME'),
            'mail.mailers.smtp.password' => env('MAIL_PASSWORD'),
            'mail.from.address' => env('MAIL_FROM_ADDRESS'),
            'mail.from.name' => env('MAIL_FROM_NAME'),
        ]);

        Log::info('Sending confirmation email with NEW mailer', [
            'repair_id' => $this->repairRequest->id,
            'email' => $this->repairRequest->email,
            'mail_config' => [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => config('mail.mailers.smtp.encryption'),
            ]
        ]);

        // Use the new simple mail class
        Mail::to($this->repairRequest->email)
            ->send(new \App\Mail\SimpleRepairConfirmation($this->repairRequest));

        Log::info('Confirmation email sent successfully with new mailer', [
            'repair_request_id' => $this->repairRequest->id,
            'email' => $this->repairRequest->email,
        ]);

        return true;

    } catch (\Exception $e) {
        Log::error('Failed to send confirmation email with new mailer: ' . $e->getMessage(), [
            'repair_request_id' => $this->repairRequest->id,
            'email' => $this->repairRequest->email,
            'trace' => $e->getTraceAsString(),
        ]);

        // Try with raw mail as last resort
        try {
            $subject = "✓ Repair Request Confirmed - #{$this->repairRequest->id}";
            $message = "Hello {$this->repairRequest->first_name},\n\n";
            $message .= "Your repair request has been confirmed.\n\n";
            $message .= "Request #: {$this->repairRequest->id}\n";
            $message .= "Device: {$this->repairRequest->category?->name} {$this->repairRequest->brand?->name} {$this->repairRequest->model?->name}\n";
            $message .= "Total: $" . number_format($this->repairRequest->total_price ?? 0, 2) . "\n";
            $message .= "Date: {$this->repairRequest->preferred_date}\n";
            $message .= "Time: {$this->repairRequest->preferred_time}\n\n";
            $message .= "Thank you for choosing our service!\n\n";
            $message .= "Regards,\n" . config('app.name');

            mail($this->repairRequest->email, $subject, $message, "From: " . config('mail.from.address'));

            Log::info('Fallback raw email sent', [
                'repair_request_id' => $this->repairRequest->id,
                'email' => $this->repairRequest->email,
            ]);
        } catch (\Exception $e2) {
            Log::error('Fallback email also failed: ' . $e2->getMessage());
        }

        return false;
    }
}

    public function render()
    {
        return view('livewire.user.payment-success', [
            'repairRequest' => $this->repairRequest
        ]);
    }
}
