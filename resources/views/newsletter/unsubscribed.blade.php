<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Already Unsubscribed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: #f8fafc;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .centered-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 2rem 2.5rem;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .card-header {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1rem;
        }
        .card-body p {
            font-size: 1.1rem;
            color: #4a5568;
        }
        strong {
            color: #3182ce;
        }
    </style>
</head>
<body>
    <div class="centered-container">
        <div class="card">
            <div class="card-header">Already Unsubscribed</div>
            <div class="card-body">
                <p>The email address <strong>{{ $email }}</strong> is already unsubscribed from our newsletter.</p>
            </div>
        </div>
    </div>
</body>
</html>