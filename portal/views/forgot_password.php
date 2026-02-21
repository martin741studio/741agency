<?php
// views/forgot_password.php

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $db = getDB();
        // Check if user exists
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            // Generate Token
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Save to DB
            $db->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
            $stmt = $db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$email, $token, $expires]);

            // Send Email
            // Send Email via SMTP
            require_once __DIR__ . '/../includes/Mailer.php';
            $mailer = new Mailer();

            $reset_link = "http://clients.741-studio.com/index.php?route=reset_password&token=$token";
            $subject = "Reset Your Password - 741 Studio";
            $msg = "
            <h3>Password Reset Request</h3>
            <p>Click the link below to reset your password:</p>
            <p><a href='$reset_link'>$reset_link</a></p>
            <p><small>This link expires in 1 hour.</small></p>
            ";

            $mailer->send($email, $subject, $msg);
        }
        // Always show success to prevent enumeration
        $message = "If an account exists for this email, a reset link checks been sent.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - 741 Studio</title>
    <link rel="icon" href="/assets/images/favicon.jpg" type="image/jpeg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;700&family=Montserrat:wght@400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #FCB141 0%, #F9A825 100%);
            color: #000;
        }

        h1 {
            font-family: 'Jost', sans-serif;
        }

        .btn-741 {
            background-color: #000;
            color: #FCB141;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 12px 24px;
            width: 100%;
            transition: transform 0.2s;
        }

        .btn-741:hover {
            transform: translateY(-2px);
        }

        .input-field {
            border: 2px solid #000;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            background: white;
        }
    </style>
</head>

<body class="flex items-center justify-center h-screen p-4">
    <div
        class="bg-white/90 backdrop-blur-sm p-10 rounded-3xl shadow-2xl w-full max-w-md border-2 border-black text-center">
        <h1 class="text-2xl font-bold uppercase mb-4">Recovery</h1>
        <p class="mb-6 text-sm text-gray-600">Enter your email to receive a reset link.</p>

        <?php if ($message): ?>
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm font-bold">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm font-bold">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <?= csrf_field() ?>
            <div class="mb-6">
                <input type="email" name="email" class="input-field" placeholder="email@example.com" required>
            </div>
            <button type="submit" class="btn-741 mb-4">Send Link</button>
        </form>
        <a href="index.php?route=login" class="text-xs font-bold hover:underline">Back to Login</a>
    </div>
</body>

</html>