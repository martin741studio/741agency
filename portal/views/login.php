<?php
// views/login.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf(); // Check CSRF Token

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Config path logic to handle different inclusion contexts
    if (!defined('DB_PATH'))
        require_once __DIR__ . '/../config.php';

    $db = getDB();

    // 1. Rate Limiting Check
    $stmt = $db->prepare("SELECT attempts, locked_until FROM login_attempts WHERE ip_address = ?");
    $stmt->execute([$ip_address]);
    $attempt = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($attempt && $attempt['locked_until'] && strtotime($attempt['locked_until']) > time()) {
        $error = "Too many failed attempts. Please try again later.";
    } else {
        // 2. Verify User
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Success: Reset Failures
            $db->prepare("DELETE FROM login_attempts WHERE ip_address = ?")->execute([$ip_address]);

            // Upgrade Hash if needed (Argon2id)
            if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
                $new_hash = password_hash($password, PASSWORD_DEFAULT);
                $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?")->execute([$new_hash, $user['id']]);
            }

            session_regenerate_id(true); // Prevent Session Fixation
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['assigned_folder'] = $user['assigned_folder'];
            header('Location: index.php?route=dashboard');
            exit;
        } else {
            // Failure: Increment Attempts
            if ($attempt) {
                $attempts = $attempt['attempts'] + 1;
                $locked_until = null;
                if ($attempts >= 5) {
                    $locked_until = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                }
                $stmt = $db->prepare("UPDATE login_attempts SET attempts = ?, last_attempt = datetime('now'), locked_until = ? WHERE ip_address = ?");
                $stmt->execute([$attempts, $locked_until, $ip_address]);
            } else {
                $stmt = $db->prepare("INSERT INTO login_attempts (ip_address, attempts, last_attempt) VALUES (?, 1, datetime('now'))");
                $stmt->execute([$ip_address]);
            }

            $error = "Invalid credentials";
            // Security: Slow down brute force slightly
            usleep(random_int(100000, 300000));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - 741 Studio</title>
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

        h1,
        h2,
        h3 {
            font-family: 'Jost', sans-serif;
        }

        .btn-741 {
            background-color: #000;
            color: #FCB141;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-741:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .input-field {
            border: 2px solid #000;
            background: rgba(255, 255, 255, 0.9);
            color: #000;
        }

        .input-field:focus {
            outline: none;
            border-color: #fff;
            background: #fff;
        }
    </style>
</head>

<body class="flex items-center justify-center h-screen p-4">
    <div class="bg-white/90 backdrop-blur-sm p-10 rounded-3xl shadow-2xl w-full max-w-md border-2 border-black">
        <div class="text-center mb-8">
            <img src="https://741.studio/wp-content/uploads/2022/09/741studio-03-1-1.svg" alt="741 Studio"
                class="h-16 mx-auto mb-4">
            <h1 class="text-2xl font-bold uppercase tracking-widest">Client Portal</h1>
        </div>

        <?php if (isset($error)): ?>
            <div
                class="bg-red-100 text-red-800 p-3 rounded-xl mb-6 text-center text-sm font-semibold border border-red-200">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?route=login">
            <div class="mb-5">
                <label class="block text-black text-xs font-bold uppercase tracking-wider mb-2">Username</label>
                <input type="text" name="username" class="input-field w-full rounded-xl p-3" required>
            </div>
            <div class="mb-8">
                <label class="block text-black text-xs font-bold uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" class="input-field w-full rounded-xl p-3" required>
                <div class="text-right mt-2">
                    <a href="index.php?route=forgot_password"
                        class="text-xs font-bold text-gray-500 hover:text-black hover:underline">Forgot Password?</a>
                </div>
            </div>
            <button type="submit" class="btn-741 w-full py-4 text-sm">
                <?= csrf_field() ?>
                Access Dashboard
            </button>

            <p class="text-center text-xs mt-6 opacity-60 font-semibold">
                &copy; <?= date('Y') ?> 741 STUDIO
            </p>
        </form>
    </div>
</body>

</html>