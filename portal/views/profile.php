<?php
// views/profile.php
// Assumes session is active (index.php handles auth)

$user_id = $_SESSION['user_id'];
$db = getDB();
$message = '';
$error = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $new_pass = trim($_POST['new_password']);

    try {
        // Update Email
        if (!empty($email)) {
            $stmt = $db->prepare("UPDATE users SET email = ? WHERE id = ?");
            $stmt->execute([$email, $user_id]);
            $message = "Profile updated successfully.";
        }

        // Update Password
        if (!empty($new_pass)) {
            $hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $stmt->execute([$hash, $user_id]);
            $message = "Password updated successfully.";
        }
    } catch (Exception $e) {
        $error = "Error updating profile: " . $e->getMessage();
    }
}

// Fetch current data
$stmt = $db->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - 741 Studio</title>
    <link rel="icon" href="/assets/images/favicon.jpg" type="image/jpeg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;700&family=Montserrat:wght@400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #F8F8F8;
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
            padding: 12px 24px;
            transition: transform 0.2s;
        }

        .btn-741:hover {
            transform: translateY(-2px);
        }

        .input-field {
            border: 2px solid #ddd;
            background: #fff;
            color: #000;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
        }

        .input-field:focus {
            outline: none;
            border-color: #FCB141;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-6 h-20 flex justify-between items-center">
            <a href="index.php?route=dashboard">
                <img src="https://741.studio/wp-content/uploads/2022/09/741studio-03-1-1.svg" alt="741 Studio"
                    class="h-8">
            </a>
            <a href="index.php?route=dashboard" class="text-sm font-bold hover:text-[#FCB141] transition">BACK TO
                DASHBOARD</a>
        </div>
    </header>

    <main class="flex-grow px-6 py-12">
        <div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-lg">
            <h1 class="text-3xl font-bold mb-6">Account Settings</h1>

            <?php if ($message): ?>
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm font-bold border border-green-200">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm font-bold border border-red-200">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500">Username</label>
                    <input type="text" value="<?= htmlspecialchars($user['username']) ?>"
                        class="input-field bg-gray-100 cursor-not-allowed" disabled>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                        class="input-field" placeholder="Enter your email for recovery">
                </div>

                <div class="mb-8">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2">New Password (Optional)</label>
                    <input type="password" name="new_password" class="input-field"
                        placeholder="Leave blank to keep current">
                </div>

                <button type="submit" class="btn-741 w-full">Save Changes</button>
            </form>
        </div>
    </main>
</body>

</html>