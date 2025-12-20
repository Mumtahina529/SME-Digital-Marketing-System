<?php
session_start();

// Site Configuration
define('SITE_NAME', 'SME Digital Marketing Platform');
define('SITE_URL', 'http://localhost/New folder');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// Create upload directories if they don't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}
if (!file_exists(UPLOAD_DIR . 'campaigns/')) {
    mkdir(UPLOAD_DIR . 'campaigns/', 0777, true);
}
if (!file_exists(UPLOAD_DIR . 'messages/')) {
    mkdir(UPLOAD_DIR . 'messages/', 0777, true);
}

// Include database connection
require_once __DIR__ . '/database.php';

// Helper Functions
function isLoggedIn() {
    return isset($_SESSION['user_id']) || isset($_SESSION['provider_id']) || isset($_SESSION['admin_id']);
}

function getUserType() {
    if (isset($_SESSION['admin_id'])) return 'admin';
    if (isset($_SESSION['provider_id'])) return 'provider';
    if (isset($_SESSION['user_id'])) return 'user';
    return null;
}

function requireLogin($requiredType = null) {
    if (!isLoggedIn()) {
        header('Location: ../index.php');
        exit();
    }
    
    if ($requiredType && getUserType() !== $requiredType) {
        header('Location: ../index.php');
        exit();
    }
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function createNotification($conn, $userType, $userId, $providerId, $adminId, $title, $message, $type = 'info') {
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, provider_id, admin_id, user_type, title, message, type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiissss", $userId, $providerId, $adminId, $userType, $title, $message, $type);
    $stmt->execute();
    $stmt->close();
}
?>

