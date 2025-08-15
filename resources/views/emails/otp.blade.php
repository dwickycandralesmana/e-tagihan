<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
            text-align: center;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            letter-spacing: 5px;
        }
        .info {
            color: #666;
            margin: 20px 0;
            line-height: 1.6;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/img/' . getSetting('brand_logo')) }}" alt="Logo" height="100">
            <h2>E-TAGIHAN <br>
            SMK PENDA 2 KARANGANYAR</h2>
        </div>

        <div class="content">
            <h2>Halo!</h2>
            <p>Anda telah meminta kode OTP untuk verifikasi akun Anda.</p>

            <div class="otp-code">
                {{ $otp->otp_id }}
            </div>

            <div class="info">
                <p><strong>Kode ini akan kedaluwarsa dalam 5 menit.</strong></p>
                <p>Jika Anda tidak meminta kode ini, abaikan email ini.</p>
                <p>Untuk keamanan, jangan bagikan kode ini kepada siapa pun.</p>
            </div>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon jangan membalas email ini.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}.</p>
        </div>
    </div>
</body>
</html>
