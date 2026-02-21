<?php
// views/reset_password.php

$token = $_GET['token'] ?? '';
$message = '';
$error = '';
$valid_token = false;

$db = getDB();

// Cleanup expired
$db->exec("DELETE FROM password_resets WHERE expires_at < datetime('now')");

// Validate Token
if ($token) {
    $stmt = $db->prepare("SELECT email FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($reset) {
        $valid_token = true;
    } else {
        $error = "Invalid or expired token.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
    verify_csrf();
    $new_pass = trim($_POST['password']);
    if (strlen($new_pass) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $hash = password_hash($new_pass, PASSWORD_DEFAULT);
        $email = $reset['email']; // from fetch above

        // Update User
        $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
        $stmt->execute([$hash, $email]);

        // Delete Token
        $db->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);

        $message = "Password updated successfully! Redirecting...";
        header("refresh:2;url=index.php?route=login");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - 741 Studio</title>
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
        <h1 class="text-2xl font-bold uppercase mb-4">Set New Password</h1>

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

        <?php if ($valid_token && !$message): ?>
            <form method="POST">
                <?= csrf_field() ?>
                <div class="mb-6">
                    <input type="password" name="password" class="input-field" placeholder="New Password" required>
                </div>
                <button type="submit" class="btn-741 mb-4">Reset Password</button>
            </form>
        <?php elseif (!$valid_token): ?>
            <a href="index.php?route=forgot_password" class="text-sm font-bold hover:underline">Request a new link</a>
        <?php endif; ?>
    </div>
</body>

</html>