<?php
// index.php
require 'config.php';

// Simple Router
$route = $_GET['route'] ?? 'dashboard';

// Authentication Check
$public_routes = ['login', 'forgot_password', 'reset_password'];
if (!isset($_SESSION['user_id']) && !in_array($route, $public_routes)) {
    header('Location: index.php?route=login');
    exit;
}

// Routing Logic
switch ($route) {
    case 'login':
        require 'views/login.php';
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?route=login');
        exit;

    case 'dashboard':
        require 'views/dashboard.php';
        break;

    case 'profile':
        require 'views/profile.php';
        break;

    case 'forgot_password':
        // Public route (no auth check needed)
        require 'views/forgot_password.php';
        break;

    case 'reset_password':
        // Public route
        require 'views/reset_password.php';
        break;

    case 'view':
        // Check access permission
        $report_slug = $_GET['report'] ?? '';

        // Security: Prevent Directory Traversal
        if (strpos($report_slug, '..') !== false) {
            die("Invalid report path.");
        }

        // Access Control for Clients
        if (
            $_SESSION['role'] === 'client' &&
            strpos($report_slug, $_SESSION['assigned_folder']) !== 0
        ) {
            die("Access Denied.");
        }

        require 'views/wrapper.php';
        break;

    case 'api/comment':
        // Handle AJAX comment submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf(); // Secure AJAX
            $content = trim($_POST['content']);
            $report_slug = $_POST['report_slug'];
            $user_id = $_SESSION['user_id'];

            if ($content) {
                $db = getDB();
                $stmt = $db->prepare("INSERT INTO comments (report_slug, user_id, content) VALUES (?, ?, ?)");
                $stmt->execute([$report_slug, $user_id, $content]);

                // Email Notification
                $user_info = $db->query("SELECT username, role, assigned_folder FROM users WHERE id = $user_id")->fetch(PDO::FETCH_ASSOC);

                $client_name = htmlspecialchars($user_info['username']);
                $client_role = htmlspecialchars($user_info['role']);
                $client_project = htmlspecialchars($user_info['assigned_folder']);
                $clean_report = htmlspecialchars($report_slug);
                $clean_content = htmlspecialchars($content);

                // Email Notification via SMTP
                try {
                    require_once __DIR__ . '/includes/Mailer.php';
                    $mailer = new Mailer();

                    $to = '741studio18@googlemail.com';
                    $subject = "[741 Client Feedback] New Comment from $client_name";

                    $message = "
                    <html>
                    <head>
                      <title>New Comment Notification</title>
                    </head>
                    <body>
                      <h3>New Comment from $client_name</h3>
                      <p><strong>Project:</strong> $client_project</p>
                      <p><strong>Report:</strong> $clean_report</p>
                      <hr>
                      <p><strong>Comment:</strong></p>
                      <div style='background: #f5f5f5; padding: 15px; border-left: 4px solid #333;'>
                        $clean_content
                      </div>
                      <hr>
                      <p><small>Sent from Client Portal via SMTP</small></p>
                      <p><a href='http://clients.741-studio.com/'>Login to Reply</a></p>
                    </body>
                    </html>
                    ";

                    $mailer->send($to, $subject, $message);
                } catch (Exception $e) {
                    error_log("Failed to send email notification: " . $e->getMessage());
                }

                echo json_encode(['status' => 'success']);
            }
        }
        exit;

    default:
        // If route not found, default to dashboard
        require 'views/dashboard.php';
        break;
}
?>