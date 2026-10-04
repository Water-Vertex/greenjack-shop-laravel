<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_number ?? 'N/A' }}</title>
    <style>
        @page { size: A4; margin: 10mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #111827;
            font-size: 12px;
            line-height: 1.5;
            padding: 20px;
            background: #fff;
        }
        .wrap { max-width: 800px; margin: 0 auto; }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 4px solid #00349A;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .header .brand h1 {
            font-size: 28px;
            font-weight: 800;
            color: #00349A;
            letter-spacing: -0.5px;
            margin-bottom: 2px;
        }
        .header .brand p {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header .meta { text-align: right; }
        .header .meta h2 {
            font-size: 22px;
            font-weight: 800;
            color: #111827;
            letter-spacing: 2px;
            margin-bottom: 6px;
        }
        .header .meta .row {
            font-size: 11px;
            color: #4b5563;
            margin-bottom: 3px;
        }
        .header .meta .row strong { color: #00349A; font-weight: 700; }

        .status-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 10px;
            border-radius: 12px;
            margin-top: 6px;
        }
        .status-paid    { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-failed  { background: #fee2e2; color: #991b1b; }

        /* INFO SECTION */
        .info-section {
            display: flex;
            gap: 30px;
            margin-bottom: 24px;
            padding: 14px 16px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #00349A;
        }
        .info-block { flex: 1; }
        .info-block h3 {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .info-block p {
            font-size: 12px;
            color: #374151;
            margin-bottom: 2px;
        }
        .info-block p strong {
            color: #111827;
            font-weight: 600;
        }

        /* ITEMS TABLE */
        .items-title {
            font-size: 14px;
            font-weight: 700;
            color: #00349A;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.items thead th {
            background: #00349A;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            text-align: left;
        }
        table.items thead th:last-child { text-align: right; }
        table.items tbody td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
            color: #374151;
            vertical-align: top;
        }
        table.items tbody td:last-child {
            text-align: right;
            font-weight: 700;
            color: #111827;
        }
        table.items tbody tr:last-child td { border-bottom: 2px solid #00349A; }
        .item-name { font-weight: 600; color: #111827; }
        .item-sku { font-size: 10px; color: #9ca3af; margin-top: 2px; }

        /* TOTALS */
        .totals-wrap {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
        }
        .totals {
            width: 300px;
            background: #f9fafb;
            border-radius: 8px;
            padding: 14px 18px;
            border: 1px solid #e5e7eb;
        }
        .totals .row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 12px;
            color: #4b5563;
        }
        .totals .row.grand {
            border-top: 2px solid #00349A;
            margin-top: 8px;
            padding-top: 10px;
            font-size: 16px;
            font-weight: 800;
            color: #00349A;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #6b7280;
            line-height: 1.8;
        }
        .footer strong { color: #00349A; }
        .thanks {
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            color: #00349A;
            margin-bottom: 8px;
        }

        @media print {
            body { padding: 0; font-size: 11px; }
            .info-section { background: #f9fafb !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table.items thead th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .totals { background: #f9fafb !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="wrap">

        <!-- HEADER -->
        <div class="header">
            <div class="brand">
                <h1>INVOICE</h1>
                <p>{{ config('app.name', 'Green Jack Shop') }}</p>
            </div>
            <div class="meta">
                <h2>#{{ $order->order_number ?? 'N/A' }}</h2>
                <div class="row">Date: <strong>{{ optional($order->created_at)->format('M d, Y') ?? 'N/A' }}</strong></div>
                <div class="row">Payment: <strong>{{ strtoupper($order->payment_method ?? 'N/A') }}</strong></div>
                @php
                    $status = $order->payment_status ?? 'pending';
                    $cls = $status === 'paid' ? 'status-paid' : ($status === 'failed' ? 'status-failed' : 'status-pending');
                @endphp
                <span class="status-badge {{ $cls }}">{{ $status }}</span>
            </div>
        </div>

        <!-- INFO SECTION -->
        <div class="info-section">
            <div class="info-block">
                <h3>Billed To</h3>
                <p><strong>{{ trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? '')) ?: 'Guest' }}</strong></p>
                @if($order->email)<p>{{ $order->email }}</p>@endif
                @if($order->phone)<p>{{ $order->phone }}</p>@endif
            </div>
            <div class="info-block">
                <h3>Ship To</h3>
                @if($order->shippingDetail)
                    <p>{{ $order->shippingDetail->address ?? 'N/A' }}</p>
                    @if($order->shippingDetail->apartment)
                        <p>{{ $order->shippingDetail->apartment }}</p>
                    @endif
                    <p>{{ $order->shippingDetail->city ?? '' }}, {{ $order->shippingDetail->state ?? '' }} {{ $order->shippingDetail->zip_code ?? '' }}</p>
                    <p>{{ $order->shippingDetail->country ?? '' }}</p>
                @else
                    <p>N/A</p>
                @endif
            </div>
        </div>

        <!-- ITEMS -->
        <div class="items-title">Order Items</div>
        <table class="items">
            <thead>
                <tr>
                    <th style="width: 55%;">Item</th>
                    <th style="width: 15%;">Price</th>
                    <th style="width: 10%;">Qty</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items ?? [] as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->product_name ?? 'Item' }}</div>
                            @if($item->product_sku ?? null)
                                <div class="item-sku">SKU: {{ $item->product_sku }}</div>
                            @endif
                            @if($item->attribute_string ?? null)
                                <div class="item-sku">{{ $item->attribute_string }}</div>
                            @endif
                        </td>
                        <td>${{ number_format((float) ($item->price ?? 0), 2) }}</td>
                        <td>{{ (int) ($item->quantity ?? 0) }}</td>
                        <td>${{ number_format((float) ($item->subtotal ?? 0), 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align: center; padding: 20px; color: #9ca3af;">No items</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- TOTALS -->
        <div class="totals-wrap">
            <div class="totals">
                <div class="row"><span>Subtotal</span><span>${{ number_format((float) ($order->subtotal ?? 0), 2) }}</span></div>
                <div class="row"><span>Shipping</span><span>{{ ($order->shipping_cost ?? 0) > 0 ? '$' . number_format((float) $order->shipping_cost, 2) : 'Free' }}</span></div>
                <div class="row"><span>Tax</span><span>${{ number_format((float) ($order->tax_amount ?? 0), 2) }}</span></div>
                @if(($order->discount_amount ?? 0) > 0)
                    <div class="row"><span>Discount</span><span>-${{ number_format((float) $order->discount_amount, 2) }}</span></div>
                @endif
                <div class="row grand"><span>Grand Total</span><span>${{ number_format((float) ($order->grand_total ?? 0), 2) }}</span></div>
            </div>
        </div>

        <!-- FOOTER -->
        <p class="thanks">Thank you for your business!</p>
        <div class="footer">
            <p><strong>{{ config('app.name', 'Green Jack Shop') }}</strong> &middot; support@greenjackshop.com &middot; www.greenjackshop.com</p>
            <p>This is a computer-generated invoice. No signature required.</p>
        </div>
    </div>

    <script>
        window.onload = function () {
            setTimeout(function () { window.print(); }, 300);
        };
    </script>
</body>
</html>
