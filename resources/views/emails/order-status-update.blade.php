<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f4f6f8;
        }
        .email-wrapper {
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 28px 30px 22px;
            text-align: center;
            border-bottom: 4px solid #3498db;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .header .order-number {
            font-size: 16px;
            opacity: 0.85;
            margin-top: 6px;
            font-weight: 300;
        }
        .content {
            padding: 30px 30px 25px;
            background: #ffffff;
        }
        .greeting {
            font-size: 15px;
            color: #2c3e50;
            margin: 0 0 6px 0;
        }
        .greeting strong {
            color: #2c3e50;
        }
        .status-flow {
            text-align: center;
            padding: 20px 0 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 6px 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 22px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 0.3px;
            background: #e2e8f0;
            color: #4a5568;
        }
        .status-old {
            background: #edf2f7;
            color: #4a5568;
        }
        .status-new {
            background: #27ae60;
            color: white;
        }
        .status-new.cancelled {
            background: #e74c3c;
        }
        .status-new.shipped {
            background: #3498db;
        }
        .status-new.delivered {
            background: #27ae60;
        }
        .status-new.refunded {
            background: #9b59b6;
        }
        .status-arrow {
            font-size: 20px;
            color: #95a5a6;
            font-weight: 300;
        }
        .order-details {
            background: #f8fafc;
            padding: 22px 24px;
            border-radius: 6px;
            margin: 18px 0 16px;
            border: 1px solid #e9edf2;
        }
        .order-details h3 {
            margin-top: 0;
            font-size: 17px;
            color: #2c3e50;
            border-bottom: 2px solid #e9edf2;
            padding-bottom: 12px;
            margin-bottom: 16px;
            font-weight: 600;
        }
        .order-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 20px;
            background: #ffffff;
            padding: 14px 16px;
            border-radius: 6px;
            margin: 0 0 18px 0;
            border: 1px solid #e9edf2;
        }
        .order-meta p {
            margin: 4px 0;
            font-size: 14px;
            color: #2c3e50;
        }
        .order-meta strong {
            color: #7f8c8d;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: block;
        }
        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin: 14px 0 12px;
            font-size: 14px;
        }
        .order-items th {
            background: #eef2f7;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .order-items td {
            padding: 10px 12px;
            border-bottom: 1px solid #e9edf2;
            color: #2c3e50;
        }
        .order-items tr:last-child td {
            border-bottom: none;
        }
        .shipping-block {
            background: #ffffff;
            padding: 14px 18px;
            border-radius: 6px;
            margin: 16px 0 12px;
            border-left: 4px solid #3498db;
            border: 1px solid #e9edf2;
            border-left-width: 4px;
        }
        .shipping-block p {
            margin: 4px 0;
            font-size: 14px;
            color: #4a5a6a;
        }
        .tracking-block {
            background: #ebf5fb;
            padding: 12px 18px;
            border-radius: 6px;
            margin: 14px 0 12px;
            border-left: 4px solid #3498db;
        }
        .tracking-block p {
            margin: 4px 0;
            font-size: 14px;
            color: #2c3e50;
        }
        .delivery-block {
            background: #eafaf1;
            padding: 12px 18px;
            border-radius: 6px;
            margin: 14px 0 12px;
            border-left: 4px solid #27ae60;
        }
        .delivery-block p {
            margin: 4px 0;
            font-size: 14px;
            color: #2c3e50;
        }
        .cancelled-block {
            background: #fdedec;
            padding: 12px 18px;
            border-radius: 6px;
            margin: 14px 0 12px;
            border-left: 4px solid #e74c3c;
        }
        .cancelled-block p {
            margin: 4px 0;
            font-size: 14px;
            color: #2c3e50;
        }
        .info-block {
            background: #f8fafc;
            padding: 12px 18px;
            border-radius: 6px;
            margin: 14px 0 12px;
            border-left: 4px solid #95a5a6;
        }
        .info-block p {
            margin: 4px 0;
            font-size: 14px;
            color: #2c3e50;
        }
        .btn {
            display: inline-block;
            padding: 12px 32px;
            background: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 15px;
            transition: background 0.2s;
            border: none;
        }
        .btn:hover {
            background: #1a2a3a;
        }
        .btn-container {
            text-align: center;
            margin: 24px 0 8px;
        }
        .footer {
            margin-top: 28px;
            padding-top: 18px;
            border-top: 1px solid #e9edf2;
            text-align: center;
            color: #7f8c8d;
            font-size: 13px;
        }
        .footer p {
            margin: 4px 0;
        }
        .footer .app-name {
            font-weight: 600;
            color: #2c3e50;
        }
        @media (max-width: 480px) {
            body { padding: 12px; }
            .header { padding: 20px 18px; }
            .header h1 { font-size: 19px; }
            .content { padding: 20px 18px; }
            .order-meta { grid-template-columns: 1fr; gap: 4px; }
            .order-items td, .order-items th { padding: 8px 8px; font-size: 13px; }
            .order-details { padding: 16px 16px; }
            .status-flow { gap: 6px 8px; }
            .status-badge { font-size: 14px; padding: 6px 14px; }
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <!-- Header -->
    <div class="header">
        <h1>Order Status Update</h1>
        <div class="order-number">Order #{{ $order->order_number ?? 'N/A' }}</div>
    </div>

    <!-- Content -->
    <div class="content">
        <p class="greeting">
            Dear <strong>{{ $order->first_name ?? 'Valued' }} {{ $order->last_name ?? 'Customer' }}</strong>,
        </p>
        <p style="margin-top: 4px;">
            We wanted to let you know that the status of your order <strong>#{{ $order->order_number ?? 'N/A' }}</strong> has been updated.
        </p>

        <!-- Status Flow -->
        <div class="status-flow">
            <span class="status-badge status-old">{{ $oldStatus ?? 'pending' }}</span>
            <span class="status-arrow">➜</span>
            <span class="status-badge status-new 
                @if($newStatus == 'cancelled') cancelled
                @elseif($newStatus == 'shipped') shipped
                @elseif($newStatus == 'delivered') delivered
                @elseif($newStatus == 'refunded') refunded
                @endif">
                {{ $newStatus ?? 'processing' }}
            </span>
        </div>

        <div class="order-details">
            <h3>Order Summary</h3>

            <div class="order-meta">
                <p><strong>Order Number</strong> #{{ $order->order_number ?? 'N/A' }}</p>
                <p><strong>Order Date</strong> {{ $order->created_at->format('F d, Y') ?? '2026-08-24' }}</p>
                <p><strong>Payment Method</strong> {{ ucfirst($order->payment_method ?? 'card') }}</p>
                <p><strong>Total Amount</strong> ${{ number_format($order->grand_total ?? 102.46, 2) }}</p>
            </div>

            <h4 style="margin: 14px 0 6px; font-size: 15px; color: #2c3e50;">Items Ordered:</h4>
            <table class="order-items">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Price</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->items ?? [] as $item)
                    <tr>
                        <td>{{ $item->product_name ?? 'Product Name' }}</td>
                        <td style="text-align: center;">{{ $item->quantity ?? 2 }}</td>
                        <td style="text-align: right;">${{ number_format($item->price ?? 24.99, 2) }}</td>
                        <td style="text-align: right;">${{ number_format($item->subtotal ?? 49.98, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center; color:#95a5a6;">— no items listed —</td></tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Shipping Address -->
            <div class="shipping-block">
                <p><strong>Shipping Address:</strong><br>
                {{ $order->shippingDetail->address ?? '123 Main Street' }}<br>
                @if(!empty($order->shippingDetail->apartment))
                    {{ $order->shippingDetail->apartment }}<br>
                @endif
                {{ $order->shippingDetail->city ?? 'New York' }}, 
                {{ $order->shippingDetail->state ?? 'NY' }} 
                {{ $order->shippingDetail->zip_code ?? '10001' }}<br>
                {{ $order->shippingDetail->country ?? 'United States' }}
                </p>
            </div>
        </div>

        <!-- Conditional Status Blocks -->
        @if(($newStatus ?? '') == 'shipped' && !empty($order->tracking_number))
        <div class="tracking-block">
            <p><strong>Tracking Information:</strong><br>
            Tracking Number: <strong>{{ $order->tracking_number }}</strong></p>
        </div>
        @endif

        @if(($newStatus ?? '') == 'delivered')
        <div class="delivery-block">
            <p>✅ Your order has been delivered! We hope you enjoy your purchase.</p>
        </div>
        @endif

        @if(($newStatus ?? '') == 'cancelled')
        <div class="cancelled-block">
            <p>⚠️ Your order has been cancelled. If you have any questions, please contact our support team.</p>
        </div>
        @endif

        @if(!in_array($newStatus ?? '', ['shipped','delivered','cancelled']) && !empty($newStatus))
        <div class="info-block">
            <p>📬 Your order is now <strong>{{ $newStatus }}</strong>. We'll keep you updated.</p>
        </div>
        @endif

        <p style="margin-top: 16px; font-size: 15px; color: #2c3e50;">
            If you have any questions about your order, please don't hesitate to contact us.
        </p>

      

        <!-- Footer -->
        <div class="footer">
            <p><span class="app-name">{{ $appName ?? 'Your Store' }}</span> — thank you for shopping with us!</p>
            <p style="font-size: 12px; color: #95a5a6;">This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</div>
</body>
</html>