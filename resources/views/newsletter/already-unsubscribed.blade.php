<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Already Unsubscribed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2eafc 100%);
            min-height: 100vh;
        }
        .unsubscribe-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(0,0,0,0.08);
            margin-top: 80px;
            background: #fff;
        }
        .unsubscribe-header {
            background: #2563eb;
            color: #fff;
            border-radius: 18px 18px 0 0;
            font-size: 1.5rem;
            font-weight: 600;
            padding: 1.5rem 2rem;
            text-align: center;
        }
        .unsubscribe-body {
            padding: 2rem;
            text-align: center;
        }
        .unsubscribe-body strong {
            color: #2563eb;
        }
        .unsubscribe-icon {
            font-size: 3rem;
            color: #2563eb;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card unsubscribe-card">
                <div class="unsubscribe-header">
                    Already Unsubscribed
                </div>
                <div class="unsubscribe-body">
                    <div class="unsubscribe-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-envelope-x" viewBox="0 0 16 16">
                          <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2zm12 1a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2V4a1 1 0 0 1 1-1h12zm1 2.383v6.634l-4.708-2.825L15 5.383zm-1.034 7.034H2.034a1 1 0 0 1-.534-.15l5.5-3.3 5.5 3.3a1 1 0 0 1-.534.15zm-13-1.4V5.383l4.708 2.825L1 11.017z"/>
                          <path d="M10.146 5.146a.5.5 0 0 1 .708 0L12 6.293l1.146-1.147a.5.5 0 0 1 .708.708L12.707 7l1.147 1.146a.5.5 0 0 1-.708.708L12 7.707l-1.146 1.147a.5.5 0 0 1-.708-.708L11.293 7l-1.147-1.146a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </div>
                    <p>The email address <strong>{{ $email }}</strong> is already unsubscribed from our newsletter.</p>
                    <p>You will no longer receive emails from us.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
