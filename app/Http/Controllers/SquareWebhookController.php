<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Mail\AdminOrderNotificationMail;

class SquareWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $rawBody = $request->getContent();

        $signature = $request->header(
            'x-square-hmacsha256-signature'
        );

        /*
        |--------------------------------------------------------------------------
        | Verify Square signature
        |--------------------------------------------------------------------------
        */

        $signatureKey = config(
            'services.square.webhook_signature_key'
        );

        $notificationUrl = route('square.webhook');

        $expectedSignature = base64_encode(
            hash_hmac(
                'sha256',
                $notificationUrl . $rawBody,
                $signatureKey,
                true
            )
        );

        if (
            empty($signature) ||
            !hash_equals(
                $expectedSignature,
                $signature
            )
        ) {
            Log::warning('Invalid Square webhook signature');

            return response()->json([
                'message' => 'Invalid signature',
            ], 403);
        }

        $event = json_decode($rawBody, true);

        if (!$event) {
            return response()->json([
                'message' => 'Invalid payload',
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate event processing
        |--------------------------------------------------------------------------
        */

        $eventId = $event['event_id'] ?? null;

        if (!$eventId) {
            return response()->json([
                'message' => 'Missing event ID',
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | Process payment.updated
        |--------------------------------------------------------------------------
        */

        if (($event['type'] ?? null) === 'payment.updated') {

            $payment =
                $event['data']['object']['payment']
                ?? null;

            if (!$payment) {
                return response()->json([
                    'message' => 'Payment object missing',
                ], 200);
            }

            $paymentId = $payment['id'] ?? null;
            $paymentStatus = $payment['status'] ?? null;
            $squareOrderId = $payment['order_id'] ?? null;

            Log::info('Square payment.updated received', [
                'event_id' => $eventId,
                'payment_id' => $paymentId,
                'payment_status' => $paymentStatus,
                'square_order_id' => $squareOrderId,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Only process completed payments
            |--------------------------------------------------------------------------
            */

            if ($paymentStatus !== 'COMPLETED') {
                return response()->json([
                    'message' => 'Event received',
                ], 200);
            }

            /*
            |--------------------------------------------------------------------------
            | Find local order
            |--------------------------------------------------------------------------
            */

            $order = Order::where(
                'square_order_id',
                $squareOrderId
            )
                ->orWhere(
                    'square_payment_id',
                    $paymentId
                )
                ->first();

            if (!$order) {

                Log::warning(
                    'Square payment received but local order not found',
                    [
                        'payment_id' => $paymentId,
                        'square_order_id' => $squareOrderId,
                    ]
                );

                return response()->json([
                    'message' => 'Order not found',
                ], 200);
            }

            /*
            |--------------------------------------------------------------------------
            | Idempotency
            |--------------------------------------------------------------------------
            */

            if ($order->payment_status === 'paid') {
                return response()->json([
                    'message' => 'Already processed',
                ], 200);
            }

            /*
            |--------------------------------------------------------------------------
            | Mark payment as paid
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use (
                $order,
                $paymentId
            ) {

                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'square_payment_id' => $paymentId,
                    'paid_at' => now(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Send emails
                |--------------------------------------------------------------------------
                */

                try {

                    if (!empty($order->email)) {
                        Mail::to($order->email)
                            ->send(
                                new OrderConfirmationMail($order)
                            );
                    }

                    $adminEmail = config(
                        'mail.admin_email',
                        'order@greenjackshop.com'
                    );

                    Mail::to($adminEmail)
                        ->send(
                            new AdminOrderNotificationMail($order)
                        );

                } catch (\Throwable $e) {

                    Log::error(
                        'Square order email failed',
                        [
                            'order_id' => $order->id,
                            'error' => $e->getMessage(),
                        ]
                    );
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Always acknowledge Square
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' => 'Webhook received',
        ], 200);
    }
}
