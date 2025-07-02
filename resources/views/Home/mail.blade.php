<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
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
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
        }
        .content {
            padding: 20px;
        }
        .detail-row {
            margin-bottom: 15px;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
            display: block;
            margin-bottom: 5px;
        }
        .detail-value {
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 3px solid #068c35;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            font-size: 0.9em;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Contact Form Submission</h1>
        <p>You've received a new message from your website contact form</p>
    </div>

    <div class="content">
        <div class="detail-row">
            <span class="detail-label">Date & Time:</span>
            <div class="detail-value">{{ now()->format('F j, Y, g:i a') }}</div>
        </div>

        <div class="detail-row">
            <span class="detail-label">From:</span>
            <div class="detail-value">{{ $data['name'] }} &lt;{{ $data['email'] }}&gt;</div>
        </div>

        <div class="detail-row">
            <span class="detail-label">Message:</span>
            <div class="detail-value" style="white-space: pre-wrap;">{{ $data['message'] }}</div>
        </div>
    </div>

    <div class="footer">
        <p>This email was sent automatically from your website contact form.</p>
        <p>&copy; {{ date('Y') }} {{ 'Fiscal Support Services' }}. All rights reserved.</p>
    </div>
</body>
</html>