<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repair Request Confirmation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
            color: #2d3748;
        }

        .container {
            max-width: 580px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        /* Header */
        .header {
            background: #ffffff;
            padding: 30px 30px 20px;
            border-bottom: 1px solid #edf2f7;
            text-align: center;
        }

        .header .logo {
            font-size: 22px;
            font-weight: 600;
            color: #1a202c;
            letter-spacing: -0.5px;
        }

        .header .logo i {
            color: #48bb78;
            margin-right: 8px;
        }

        .header .subtitle {
            color: #718096;
            font-size: 14px;
            margin-top: 4px;
        }

        /* Content */
        .content {
            padding: 30px 30px 25px;
        }

        /* Status Badge */
        .status-badge {
            background: #f0fff4;
            border: 1px solid #c6f6d5;
            color: #276749;
            padding: 10px 20px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .status-badge i {
            color: #48bb78;
            margin-right: 8px;
        }

        .status-badge strong {
            color: #1a202c;
        }

        /* Sections */
        .section {
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: #48bb78;
            width: 16px;
            font-size: 14px;
        }

        .section-content {
            background: #f7fafc;
            border-radius: 6px;
            padding: 14px 16px;
            border: 1px solid #edf2f7;
        }

        .section-content.no-bg {
            background: transparent;
            padding: 0;
            border: none;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 20px;
        }

        .info-item {
            padding: 4px 0;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-label {
            font-size: 11px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-weight: 600;
        }

        .info-value {
            font-size: 14px;
            color: #1a202c;
            font-weight: 500;
            margin-top: 1px;
        }

        /* Problems List */
        .problems-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .problems-list li {
            padding: 8px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #edf2f7;
            font-size: 14px;
        }

        .problems-list li:last-child {
            border-bottom: none;
        }

        .problem-name {
            color: #2d3748;
        }

        .problem-name .problem-desc {
            display: block;
            font-size: 12px;
            color: #a0aec0;
            font-weight: 400;
            margin-top: 1px;
        }

        .problem-price {
            color: #2d3748;
            font-weight: 500;
            white-space: nowrap;
        }

        /* Payment Summary */
        .payment-summary {
            background: #2d3748;
            border-radius: 6px;
            padding: 16px 20px;
            color: #ffffff;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 14px;
        }

        .payment-row .label {
            color: #a0aec0;
        }

        .payment-row .value {
            color: #ffffff;
        }

        .payment-row.total {
            border-top: 1px solid rgba(255,255,255,0.15);
            margin-top: 8px;
            padding-top: 10px;
            font-weight: 600;
            font-size: 16px;
        }

        .payment-row .payment-method {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #ffffff;
        }

        .payment-row .payment-method i {
            font-size: 14px;
        }

        .payment-row .payment-status {
            color: #68d391;
            font-weight: 500;
        }

        .payment-row .payment-status i {
            margin-right: 4px;
        }

        /* Device Info Box */
        .device-box {
            background: #ebf8ff;
            border: 1px solid #bee3f8;
            border-radius: 6px;
            padding: 12px 16px;
        }

        .device-box .device-name {
            font-size: 15px;
            font-weight: 600;
            color: #2b6cb0;
        }

        .device-box .device-detail {
            font-size: 13px;
            color: #2c5282;
            margin-top: 2px;
        }

        /* Next Steps */
        .next-steps {
            background: #f7fafc;
            border-radius: 6px;
            padding: 14px 18px;
            border: 1px solid #edf2f7;
        }

        .next-steps h4 {
            font-size: 13px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .next-steps ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .next-steps ul li {
            padding: 4px 0;
            font-size: 14px;
            color: #4a5568;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .next-steps ul li i {
            color: #48bb78;
            font-size: 12px;
            width: 16px;
        }

        /* Button */
        .btn-container {
            text-align: center;
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            padding: 10px 28px;
            background: #48bb78;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #38a169;
        }

        .btn i {
            margin-right: 8px;
        }

        /* Address */
        .address-text {
            font-size: 14px;
            color: #2d3748;
            line-height: 1.7;
        }

        /* Message */
        .message-text {
            font-size: 14px;
            color: #4a5568;
            font-style: italic;
            padding: 8px 0;
        }

        /* Footer */
        .footer {
            background: #f7fafc;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #edf2f7;
        }

        .footer p {
            font-size: 13px;
            color: #718096;
            margin: 0;
        }

        .footer .footer-links {
            margin-top: 6px;
        }

        .footer .footer-links a {
            color: #48bb78;
            text-decoration: none;
            font-size: 13px;
            margin: 0 10px;
        }

        .footer .footer-links a:hover {
            text-decoration: underline;
        }

        .footer .copyright {
            font-size: 11px;
            color: #a0aec0;
            margin-top: 8px;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #edf2f7;
            margin: 20px 0;
        }

        /* Responsive */
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .header,
            .content,
            .footer {
                padding-left: 18px;
                padding-right: 18px;
            }

            .header {
                padding-top: 20px;
                padding-bottom: 15px;
            }

            .header .logo {
                font-size: 18px;
            }

            .content {
                padding-top: 20px;
                padding-bottom: 18px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 4px;
            }

            .info-item.full-width {
                grid-column: 1;
            }

            .section-content {
                padding: 12px 14px;
            }

            .payment-summary {
                padding: 14px 16px;
            }

            .payment-row {
                font-size: 13px;
                padding: 3px 0;
            }

            .payment-row.total {
                font-size: 15px;
            }

            .problems-list li {
                font-size: 13px;
                padding: 6px 0;
                flex-wrap: wrap;
            }

            .problem-name .problem-desc {
                font-size: 11px;
            }

            .btn {
                padding: 10px 24px;
                font-size: 13px;
                width: 100%;
            }

            .next-steps ul li {
                font-size: 13px;
            }

            .footer .footer-links a {
                display: inline-block;
                margin: 0 6px;
                font-size: 12px;
            }
        }

        @media (max-width: 380px) {
            .status-badge {
                font-size: 13px;
                padding: 8px 14px;
            }

            .section-title {
                font-size: 12px;
            }

            .info-value {
                font-size: 13px;
            }

            .device-box .device-name {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <i class="fas fa-tools"></i> Repair Service
            </div>
            <div class="subtitle">Repair Request Confirmation</div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Status Badge -->
            <div class="status-badge">
                <i class="fas fa-check-circle"></i>
                Payment Successful · Request #<strong>{{ $repairRequest->id }}</strong>
                <span style="display:block; font-size:12px; color:#48bb78; margin-top:2px;">
                    {{ $repairRequest->created_at->format('F d, Y') }}
                </span>
            </div>

            <!-- Customer Information -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-user"></i> Customer Details
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Name</div>
                            <div class="info-value">{{ $repairRequest->first_name }} {{ $repairRequest->last_name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $repairRequest->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Phone</div>
                            <div class="info-value">{{ $repairRequest->mobile }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">IMEI</div>
                            <div class="info-value">{{ $repairRequest->imei ?: '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Device Information -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-mobile-alt"></i> Device
                </div>
                <div class="device-box">
                    <div class="device-name">
                        {{ $repairRequest->category->name ?? 'N/A' }}
                    </div>
                    <div class="device-detail">
                        {{ $repairRequest->brand->name ?? 'N/A' }} · {{ $repairRequest->model->name ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Problems -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-list"></i> Services
                </div>
                <div class="section-content">
                    @php
                        $problems = json_decode($repairRequest->problems, true);
                        $subtotal = 0;
                    @endphp
                    @if($problems && count($problems) > 0)
                        <ul class="problems-list">
                            @foreach($problems as $problem)
                                @php
                                    $price = floatval($problem['price'] ?? 0);
                                    $subtotal += $price;
                                @endphp
                                <li>
                                    <span class="problem-name">
                                        {{ $problem['name'] ?? 'Service' }}
                                        @if(!empty($problem['description']))
                                            <span class="problem-desc">{{ $problem['description'] }}</span>
                                        @endif
                                    </span>
                                    <span class="problem-price">${{ number_format($price, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <span style="color: #a0aec0; font-size: 14px;">No services selected</span>
                    @endif
                </div>
            </div>

            <!-- Appointment -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-calendar-alt"></i> Appointment
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Type</div>
                            <div class="info-value">
                                @if($repairRequest->service_type == 'walk_in')
                                    Walk-in
                                @elseif($repairRequest->service_type == 'pickup_drop')
                                    Pickup & Drop
                                @elseif($repairRequest->service_type == 'postal')
                                    Postal
                                @else
                                    {{ ucfirst($repairRequest->service_type) }}
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date</div>
                            <div class="info-value">
                                @php
                                    try {
                                        echo \Carbon\Carbon::parse($repairRequest->preferred_date)->format('M d, Y');
                                    } catch (\Exception $e) {
                                        echo $repairRequest->preferred_date ?? '—';
                                    }
                                @endphp
                            </div>
                        </div>
                        <div class="info-item full-width">
                            <div class="info-label">Time</div>
                            <div class="info-value">{{ $repairRequest->preferred_time ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address -->
            @if($repairRequest->address)
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-map-marker-alt"></i> Address
                </div>
                <div class="section-content">
                    <div class="address-text">
                        {{ $repairRequest->address }}
                        @if($repairRequest->city || $repairRequest->state || $repairRequest->zip_code)
                            <br>
                            @if($repairRequest->city){{ $repairRequest->city }}, @endif
                            @if($repairRequest->state){{ $repairRequest->state }} @endif
                            @if($repairRequest->zip_code) {{ $repairRequest->zip_code }} @endif
                        @endif
                        @if($repairRequest->country)
                            <br>{{ $repairRequest->country }}
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Message -->
            @if($repairRequest->message)
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-comment"></i> Note
                </div>
                <div class="section-content">
                    <div class="message-text">"{{ $repairRequest->message }}"</div>
                </div>
            </div>
            @endif

            <hr class="divider">

            <!-- Payment Summary -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-credit-card"></i> Payment
                </div>
                <div class="payment-summary">
                    <div class="payment-row">
                        <span class="label">Subtotal</span>
                        <span class="value">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="payment-row">
                        <span class="label">Tax (8%)</span>
                        <span class="value">${{ number_format($repairRequest->tax ?? 0, 2) }}</span>
                    </div>
                    <div class="payment-row total">
                        <span>Total</span>
                        <span>${{ number_format($repairRequest->total_price ?? 0, 2) }}</span>
                    </div>
                    <div class="payment-row" style="margin-top:8px; padding-top:8px; border-top:1px solid rgba(255,255,255,0.08);">
                        <span class="label">Method</span>
                        <span class="payment-method">
                            <i class="fas {{ $repairRequest->payment_method == 'stripe' ? 'fa-credit-card' : 'fa-paypal' }}"></i>
                            {{ ucfirst($repairRequest->payment_method) }}
                        </span>
                    </div>
                    <div class="payment-row">
                        <span class="label">Status</span>
                        <span class="payment-status"><i class="fas fa-check-circle"></i> Paid</span>
                    </div>
                    @if($repairRequest->paid_at)
                    <div class="payment-row">
                        <span class="label">Paid On</span>
                        <span class="value">
                            @php
                                try {
                                    echo \Carbon\Carbon::parse($repairRequest->paid_at)->format('M d, Y h:i A');
                                } catch (\Exception $e) {
                                    echo $repairRequest->paid_at;
                                }
                            @endphp
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h4><i class="fas fa-arrow-right" style="color:#48bb78; margin-right:6px;"></i> Next Steps</h4>
                <ul>
                    <li><i class="fas fa-check"></i> We'll review your request within 24 hours</li>
                    <li><i class="fas fa-phone"></i> Our team will call to confirm the appointment</li>
                    <li><i class="fas fa-tablet-alt"></i> Keep your device ready on the scheduled date</li>
                </ul>
            </div>

            <!-- Button -->
            <div class="btn-container">
                <a href="{{ route('device-repair', ['slug' => $repairRequest->category->slug ?? '']) }}" class="btn">
                    <i class="fas fa-arrow-right"></i> View Status
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="font-weight:500; color:#2d3748;">Thank you for choosing our service</p>
            <div class="footer-links">
                <a href="#"><i class="fas fa-envelope"></i> support@yourcompany.com</a>
                <a href="#"><i class="fas fa-phone"></i> +1 (555) 000-0000</a>
            </div>
            <div class="copyright">
                &copy; {{ date('Y') }} Repair Service. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
