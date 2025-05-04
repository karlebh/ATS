<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>OTP Authentication</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            .email-container {
                max-width: 600px;
                margin: 20px auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            .header {
                text-align: center;
                padding-bottom: 20px;
                border-bottom: 2px solid #eee;
            }

            .otp-code {
                font-size: 20px;
                font-weight: bold;
                color: #1a73e8;
                display: block;
                margin: 20px 0;
                text-align: center;
            }

            .message {
                font-size: 16px;
                line-height: 1.5;
                color: #333;
            }

            .footer {
                margin-top: 20px;
                text-align: center;
                font-size: 14px;
                color: #999;
            }

            .footer a {
                color: #1a73e8;
                text-decoration: none;
            }
        </style>
    </head>

    <body>
        <div class="email-container">
            <div class="header">
                <h2>Dear {{ $user->username }},</h2>
            </div>
            <div class="message">
                <p>To ensure the security of your account, please use the following One-Time Password (OTP) to complete
                    your authentication:</p>
                <span class="otp-code">🔑 Your OTP: {{ $otp }}</span>
                <p>This OTP is valid for 10 minutes. Please do not share this code with anyone. If you did not request
                    this, please ignore this email or contact our support team immediately.</p>
            </div>
            <div class="footer">
                <p>Thank you,</p>
                <p>© 2025 The Accurate Tools Shop. All rights reserved.</p>
            </div>
        </div>
    </body>

</html>
