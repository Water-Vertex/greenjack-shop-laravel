<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Notification</title>
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
        .alert-badge {
            background: #e74c3c;
            color: white;
            padding: 8px 22px;
            border-radius: 50px;
            display: inline-block;
            font-weight: 600;
            font-size: 14px;
            margin: 0 0 16px 0;
            letter-spacing: 0.3px;
        }
        .greeting {
            font-size: 15px;
            color: #2c3e50;
            margin: 10px 0 18px;
        }
        .greeting strong {
            color: #2c3e50;
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
        .customer-info {
            background: #ffffff;
            padding: 14px 18px;
            border-radius: 6px;
            margin: 0 0 18px 0;
            border-left: 4px solid #3498db;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .customer-info h4 {
            margin: 0 0 6px 0;
            font-size: 14px;
            color: #2c3e50;
        }
        .customer-info p {
            margin: 4px 0;
            font-size: 14px;
            color: #4a5a6a;
        }
        .customer-info a {
            color: #3498db;
            text-decoration: none;
        }
        .customer-info a:hover {
            text-decoration: underline;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 20px;
            background: #ffffff;
            padding: 14px 16px;
            border-radius: 6px;
            margin: 0 0 18px 0;
            border: 1px solid #e9edf2;
        }
        .info-grid .label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #7f8c8d;
            font-weight: 600;
        }
        .info-grid .value {
            font-size: 14px;
            font-weight: 500;
            color: #2c3e50;
        }
        .payment-method {
            background: #e8f0fe;
            padding: 2px 14px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 500;
            color: #2c3e50;
            display: inline-block;
        }
        .status-paid {
            color: #27ae60;
            font-weight: 600;
        }
        .status-pending {
            color: #f39c12;
            font-weight: 600;
        }
        .status-processing {
            color: #2980b9;
            font-weight: 600;
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
        .totals {
            margin-top: 16px;
            padding-top: 14px;
            border-top: 2px solid #e9edf2;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 14px;
        }
        .total-row.grand-total {
            font-size: 17px;
            font-weight: 700;
            color: #2c3e50;
            border-top: 2px solid #2c3e50;
            padding-top: 12px;
            margin-top: 8px;
        }
        .shipping-block {
            background: #ffffff;
            padding: 14px 18px;
            border-radius: 6px;
            margin: 16px 0 12px;
            border-left: 4px solid #27ae60;
            border: 1px solid #e9edf2;
            border-left-width: 4px;
        }
        .shipping-block h4 {
            margin: 0 0 4px 0;
            font-size: 14px;
            color: #2c3e50;
        }
        .shipping-block p {
            margin: 4px 0;
            font-size: 14px;
            color: #4a5a6a;
        }
        .notes-block {
            background: #fef9e7;
            padding: 12px 18px;
            border-radius: 6px;
            margin: 14px 0 18px;
            border-left: 4px solid #f1c40f;
        }
        .notes-block p {
            margin: 0;
            font-size: 14px;
            color: #5a4a2a;
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
            .info-grid { grid-template-columns: 1fr; gap: 6px; }
            .order-items td, .order-items th { padding: 8px 8px; font-size: 13px; }
            .order-details { padding: 16px 16px; }
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <!-- Header -->
    <div class="header">
        <h1>🛒 New Order Received</h1>
        <div class="order-number">Order #{{ $order->order_number ?? 'N/A' }}</div>
    </div>

    <!-- Content -->
    <div class="content">
        <div style="margin-bottom: 4px;">
            <span class="alert-badge">⚠️ Action Required</span>
        </div>

        <p class="greeting">
            A new order has been placed and requires your attention.
        </p>

        <div class="order-details">
            <h3>Order Summary</h3>

            <!-- Customer Info -->
            <div class="customer-info">
                <h4>👤 Customer Information</h4>
                <p>
                    <strong>{{ $order->first_name ?? 'John' }} {{ $order->last_name ?? 'Doe' }}</strong><br>
                    Email: <a href="mailto:{{ $order->email ?? 'customer@example.com' }}">{{ $order->email ?? 'customer@example.com' }}</a><br>
                    Phone: {{ $order->phone ?? '+1 234 567 890' }}
                </p>
            </div>

            <!-- Order Meta -->
            <div class="info-grid">
                <div>
                    <span class="label">Order Date</span>
                    <span class="value">{{ $order->created_at->format('F d, Y H:i') ?? '2026-08-24 14:30' }}</span>
                </div>
                <div>
                    <span class="label">Payment Method</span>
                    <span class="payment-method">{{ ucfirst($order->payment_method ?? 'card') }}</span>
                </div>
                <div>
                    <span class="label">Payment Status</span>
                    <span class="{{ ($order->payment_status ?? 'pending') === 'paid' ? 'status-paid' : 'status-pending' }}">
                        {{ ucfirst($order->payment_status ?? 'pending') }}
                    </span>
                </div>
                <div>
                    <span class="label">Order Status</span>
                    <span class="status-processing">{{ ucfirst($order->status ?? 'processing') }}</span>
                </div>
            </div>

            <!-- Items -->
            <h4 style="margin: 16px 0 6px; font-size: 15px; color: #2c3e50;">Items Ordered:</h4>
            <table class="order-items">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Price</th>
                        <th style="text-align: right;">Total</th>
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

            <!-- Totals -->
            <div class="totals">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal ?? 89.97, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Shipping</span>
                    <span>${{ number_format($order->shipping_cost ?? 5.99, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Tax</span>
                    <span>${{ number_format($order->tax_amount ?? 6.50, 2) }}</span>
                </div>
                <div class="total-row grand-total">
                    <span>Grand Total</span>
                    <span>${{ number_format($order->grand_total ?? 102.46, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Shipping -->
        <div class="shipping-block">
            <h4>📦 Shipping Address</h4>
            <p>
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

        <!-- Notes -->
        @if(!empty($order->order_notes))
        <div class="notes-block">
            <p><strong>Order Notes:</strong><br>{{ $order->order_notes }}</p>
        </div>
        @else
        <div class="notes-block" style="background:#f8fafc; border-left-color:#bdc3c7;">
            <p style="color:#7f8c8d;">No order notes provided.</p>
        </div>
        @endif

      

        <!-- Footer -->
        <div class="footer">
            <p><span class="app-name">{{ $appName ?? 'Your Store' }}</span> — automated notification</p>
            <p style="font-size: 12px; color: #95a5a6;">Please process this order as soon as possible.</p>
            <p style="font-size: 11px; color: #bdc3c7; margin-top: 4px;">This is an automated message, please do not reply.</p>
        </div>
    </div>
</div>
</body>
</html>