<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>New Task Notification</title>
        <style>
            body {
                font-family: 'Segoe UI', sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            .container {
                background-color: #ffffff;
                max-width: 600px;
                margin: 30px auto;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            }

            .header {
                background-color: #004aad;
                color: #ffffff;
                padding: 20px;
                border-radius: 8px 8px 0 0;
                text-align: center;
                font-size: 20px;
                font-weight: bold;
            }

            .content {
                color: #333333;
                padding: 20px 0;
            }

            .task-details {
                background-color: #f9f9f9;
                padding: 15px;
                border: 1px solid #e0e0e0;
                border-radius: 5px;
                margin: 20px 0;
            }

            .footer {
                font-size: 13px;
                color: #777777;
                text-align: center;
                margin-top: 30px;
            }

            .button {
                display: inline-block;
                background-color: #004aad;
                color: white;
                padding: 10px 20px;
                margin-top: 20px;
                text-decoration: none;
                border-radius: 5px;
            }

            .button:hover {
                background-color: #00347a;
            }
        </style>
    </head>

    <body>

        <div class="container">
            <div class="header">
                The Accurate Tools Shop
            </div>

            <div class="content">
                <p>Dear <strong>{{ $user->username }}</strong>,</p>

                <p>We hope you’re doing well.</p>

                <p>A new message has been sent to you in the Accurate Tools Shop system from a floor team member.</p>

                <p>Please log in to your account to review the task details and take the necessary action.</p>

                <div class="task-details">
                    <p><strong>Task Title:</strong> {{ $requestData['title'] }}</p>
                    <p><strong>Message:</strong><br>{{ $requestData['message'] }}</p>
                </div>

                <p>If you have any questions or need further assistance, feel free to reach out.</p>
            </div>

            <div class="footer">
                Thank you,<br>
                The Accurate Tools Shop Team<br><br>
                &copy; 2025 The Accurate Tools Shop. All rights reserved.
            </div>
        </div>

    </body>

</html>
