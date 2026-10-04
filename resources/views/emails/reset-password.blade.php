<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #5B9F01;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            color: white;
            margin: 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #e0e0e0;
            border-top: none;
        }
        .button {
            display: inline-block;
            background: #5B9F01;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .button:hover {
            background: #4a7f01;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $appName }}</h1>
    </div>

    <div class="content">
        <h2>Reset Your Password</h2>

        <p>Hello,</p>

        <p>You are receiving this email because we received a password reset request for your account.</p>

        <p style="text-align: center;">
            <a href="{{ $resetUrl }}" class="button">Reset Password</a>
        </p>

        <p>This password reset link will expire in 1 hour.</p>

        <p>If you did not request a password reset, no further action is required.</p>

        <hr>

        <p><strong>Email:</strong> {{ $email }}</p>

        <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
        <p style="word-break: break-all; font-size: 12px; color: #666;">{{ $resetUrl }}</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        <p>This email was sent to {{ $email }}</p>
    </div>
</body>
</html>
