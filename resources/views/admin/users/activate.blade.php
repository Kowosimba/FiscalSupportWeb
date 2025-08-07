<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activate User Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            background: #fff;
            max-width: 600px;
            margin: 40px auto;
            padding: 32px 24px;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }
        h3 {
            margin-bottom: 20px;
        }
        ul {
            padding-left: 20px;
            margin-bottom: 24px;
        }
        li {
            margin-bottom: 8px;
        }
        .btn {
            display: inline-block;
            padding: 10px 22px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            background: #198754;
            transition: background 0.2s;
        }
        .btn-secondary {
            background: #6c757d;
            margin-left: 10px;
        }
        .btn:hover {
            opacity: 0.92;
        }
    </style>
</head>
<body>
<div class="container">
    <h3>Activate User Account</h3>

    <p>You are about to activate the account for:</p>
    <ul>
        <li><strong>Name:</strong> {{ $user->name }}</li>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Role:</strong> {{ ucfirst($user->role ?? 'user') }}</li>
    </ul>

    <form method="POST" action="{{ route('admin.users.processActivation') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <button type="submit" class="btn">Confirm Activation</button>
        <a href="{{ route('login') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
