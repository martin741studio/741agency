<?php
// config.php
declare(strict_types=1);

// Security Headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Content-Security-Policy: default-src 'self' https: data:; script-src 'self' 'unsafe-inline' https:; style-src
'self' 'unsafe-inline' https:;");

// Secure Session Params
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_samesite', 'Strict');
// Only set Secure flag if HTTPS (which it is on production)
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', '1');
}

// Custom Session Path to avoid permission issues
$session_save_path = __DIR__ . '/sessions';
if (!file_exists($session_save_path)) {
    mkdir($session_save_path, 0777, true);
}
session_save_path($session_save_path);

session_start();

// CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
}

function verify_csrf(): void
{
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // Log potential attack
        error_log("CSRF verification failed from IP: " . $_SERVER['REMOTE_ADDR']);
        die("Invalid CSRF Token. Please refresh and try again.");
    }
}

// XSS Protection Helper
function e(string|null $string): string
{
    return htmlspecialchars((string) $string, ENT_QUOTES, 'UTF-8');
}

define('DB_PATH', __DIR__ . '/db/portal.db');

function getDB(): PDO
{
    try {
        $db = new PDO('sqlite:' . DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Enable foreign keys
        $db->exec("PRAGMA foreign_keys = ON;");
        return $db;
    } catch (PDOException $e) {
        // Don't die() with stack trace in production
        error_log("Database Error: " . $e->getMessage());
        die("System Error (DB). Please try again later.");
    }
}
?>