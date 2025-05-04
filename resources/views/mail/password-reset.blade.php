<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Password Reset</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                line-height: 1.6;
                color: #333333;
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }

            .header {
                color: #2c3e50;
                font-size: 24px;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 1px solid #eeeeee;
            }

            .content {
                margin-bottom: 30px;
            }

            .button {
                display: inline-block;
                padding: 12px 24px;
                background-color: #3498db;
                color: #ffffff !important;
                text-decoration: none;
                border-radius: 4px;
                font-weight: bold;
                margin: 15px 0;
            }

            .button:hover {
                background-color: #2980b9;
            }

            .footer {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #eeeeee;
                font-size: 12px;
                color: #7f8c8d;
            }

            .url-box {
                background-color: #f5f5f5;
                padding: 15px;
                border-radius: 4px;
                word-break: break-all;
                font-family: monospace;
                margin: 20px 0;
            }
        </style>
    </head>

    <body>
        <div class="header">The Accurate Tools Shop</div>

        <div class="content">
            <p>Hello {{ $user->username }},</p>

            <p>You are receiving this email because we received a password reset request for your account.</p>

            <p>
                <a href="{!! $url !!}" class="button">Reset Password</a>
            </p>

            <p>This password reset link will expire in 60 minutes.</p>

            <p>If you did not request a password reset, no further action is required.</p>
        </div>

        <div class="footer">
            <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web
                browser:</p>

            <div class="url-box">{!! $url !!}</div>

            <p>© 2025 The Accurate Tools Shop. All rights reserved.</p>
        </div>
    </body>

</html>
