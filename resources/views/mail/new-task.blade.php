<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Task Notification</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            .email-container {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .email-header {
                text-align: center;
                padding-bottom: 20px;
            }

            .email-header h2 {
                color: #333;
            }

            .email-body {
                font-size: 16px;
                color: #333;
                line-height: 1.5;
            }

            .task-details {
                background-color: #f9f9f9;
                padding: 15px;
                margin-top: 20px;
                border-left: 4px solid #007BFF;
            }

            .task-details h3 {
                color: #007BFF;
            }

            .email-footer {
                text-align: center;
                font-size: 14px;
                color: #777;
                margin-top: 30px;
            }

            .email-footer a {
                color: #007BFF;
                text-decoration: none;
            }
        </style>
    </head>

    <body>
        <div class="email-container">
            <div class="email-header">
                <h2>Task Notification</h2>
            </div>
            <div class="email-body">
                <p>Dear {{ $user->username }},</p>
                <p>We hope you’re doing well.</p>
                <p>A new task has been assigned to you in The Accurate Tools Shop system. Please log in to your account
                    to review the task details and take the necessary action.</p>

                <div class="task-details">
                    <h3>Task Details:</h3>
                    <p><strong>Task Title:</strong> {{ $task->name }}</p>
                    <p><strong>Task Details:</strong> {{ $task->details }}</p>
                </div>

                <p>If you have any questions or need further assistance, feel free to reach out.</p>
            </div>
            <div class="email-footer">
                <p>Thank you,<br>The Accurate Tools Shop Team</p>
                <p>© 2025 The Accurate Tools Shop. All rights reserved.</p>
            </div>
        </div>
    </body>

</html>
