<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repair Confirmation</title>
</head>
<body style="margin:0; padding:20px; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px; background-color:#ffffff; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
        <tr>
            <td style="padding:30px 30px 20px; text-align:center; border-bottom:2px solid #4CAF50;">
                <h1 style="margin:0; color:#333; font-size:24px;">✅ Repair Confirmed</h1>
                <p style="margin:5px 0 0; color:#666; font-size:14px;">Thank you for choosing our service</p>
            </td>
        </tr>
        <tr>
            <td style="padding:30px;">
                <h2 style="color:#333; font-size:18px; margin:0 0 15px;">Hello {{ $repairRequest->first_name }}!</h2>

                <p style="color:#555; font-size:15px; line-height:1.6; margin:0 0 20px;">
                    Your repair request has been confirmed. Here are your details:
                </p>

                <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
                    <tr>
                        <td style="padding:8px 0; border-bottom:1px solid #eee; color:#666; font-size:13px; font-weight:bold;">Request #</td>
                        <td style="padding:8px 0; border-bottom:1px solid #eee; color:#333; font-size:14px; text-align:right;">{{ $repairRequest->id }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; border-bottom:1px solid #eee; color:#666; font-size:13px; font-weight:bold;">Device</td>
                        <td style="padding:8px 0; border-bottom:1px solid #eee; color:#333; font-size:14px; text-align:right;">
                            {{ $repairRequest->category?->name ?? 'N/A' }}
                            {{ $repairRequest->brand?->name ?? '' }}
                            {{ $repairRequest->model?->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; border-bottom:1px solid #eee; color:#666; font-size:13px; font-weight:bold;">Total</td>
                        <td style="padding:8px 0; border-bottom:1px solid #eee; color:#333; font-size:14px; text-align:right; font-weight:bold;">
                            ${{ number_format($repairRequest->total_price ?? 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; border-bottom:1px solid #eee; color:#666; font-size:13px; font-weight:bold;">Payment</td>
                        <td style="padding:8px 0; border-bottom:1px solid #eee; color:#333; font-size:14px; text-align:right; text-transform:capitalize;">
                            {{ $repairRequest->payment_method ?? 'N/A' }} ✓
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; border-bottom:1px solid #eee; color:#666; font-size:13px; font-weight:bold;">Date</td>
                        <td style="padding:8px 0; border-bottom:1px solid #eee; color:#333; font-size:14px; text-align:right;">
                            {{ $repairRequest->preferred_date ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:#666; font-size:13px; font-weight:bold;">Time</td>
                        <td style="padding:8px 0; color:#333; font-size:14px; text-align:right;">
                            {{ $repairRequest->preferred_time ?? 'N/A' }}
                        </td>
                    </tr>
                </table>

                @php
                    $problems = json_decode($repairRequest->problems, true);
                @endphp
                @if($problems && count($problems) > 0)
                    <div style="background:#f9f9f9; border-left:3px solid #4CAF50; padding:12px 15px; margin-bottom:20px;">
                        <p style="margin:0 0 8px; color:#666; font-size:13px; font-weight:bold;">Services Selected:</p>
                        @foreach($problems as $problem)
                            <p style="margin:3px 0; color:#333; font-size:14px;">
                                • {{ $problem['name'] ?? 'Service' }}
                                @if(!empty($problem['price']))
                                    <span style="color:#666; font-size:13px;">- ${{ number_format($problem['price'], 2) }}</span>
                                @endif
                            </p>
                        @endforeach
                    </div>
                @endif

                <div style="background:#e8f5e9; padding:12px 15px; border-radius:4px; margin:20px 0;">
                    <p style="margin:0; color:#2e7d32; font-size:14px;">
                        <strong>📧 Next Steps:</strong> Our team will contact you shortly to confirm your appointment.
                    </p>
                </div>

                <hr style="border:0; border-top:1px solid #eee; margin:25px 0;">

                <p style="color:#888; font-size:13px; text-align:center; margin:0;">
                    If you have any questions, please contact us at
                    <a href="mailto:{{ config('mail.from.address') }}" style="color:#4CAF50; text-decoration:none;">
                        {{ config('mail.from.address') }}
                    </a>
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding:20px 30px; text-align:center; background-color:#f9f9f9; border-top:1px solid #eee; border-radius:0 0 8px 8px;">
                <p style="margin:0; color:#999; font-size:12px;">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
