<?php
require_once '../config/config.php';

header('Content-Type: application/json');
$conn = getDBConnection();
$userType = getUserType();

if (!$userType) {
    echo json_encode(['success' => false, 'message' => 'লগইন প্রয়োজন']);
    exit();
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        if ($userType === 'user') {
            requireLogin('user');
            $userId = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? AND user_type = 'user' ORDER BY created_at DESC LIMIT 50");
            $stmt->bind_param("i", $userId);
        } elseif ($userType === 'provider') {
            requireLogin('provider');
            $providerId = $_SESSION['provider_id'];
            $stmt = $conn->prepare("SELECT * FROM notifications WHERE provider_id = ? AND user_type = 'provider' ORDER BY created_at DESC LIMIT 50");
            $stmt->bind_param("i", $providerId);
        } elseif ($userType === 'admin') {
            requireLogin('admin');
            $stmt = $conn->query("SELECT * FROM notifications WHERE user_type = 'admin' ORDER BY created_at DESC LIMIT 50");
        }
        
        if (isset($stmt) && $userType !== 'admin') {
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $stmt;
        }
        
        $notifications = [];
        while ($row = ($userType === 'admin' ? $result->fetch_assoc() : $result->fetch_assoc())) {
            $notifications[] = $row;
        }
        
        if ($userType !== 'admin') {
            $stmt->close();
        }
        
        echo json_encode(['success' => true, 'data' => $notifications]);
        break;
        
    case 'mark_read':
        $notificationId = intval($_POST['notification_id']);
        $stmt = $conn->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ?");
        $stmt->bind_param("i", $notificationId);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => true]);
        break;
        
    case 'unread_count':
        if ($userType === 'user') {
            requireLogin('user');
            $userId = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND user_type = 'user' AND is_read = FALSE");
            $stmt->bind_param("i", $userId);
        } elseif ($userType === 'provider') {
            requireLogin('provider');
            $providerId = $_SESSION['provider_id'];
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE provider_id = ? AND user_type = 'provider' AND is_read = FALSE");
            $stmt->bind_param("i", $providerId);
        } elseif ($userType === 'admin') {
            requireLogin('admin');
            $stmt = $conn->query("SELECT COUNT(*) as count FROM notifications WHERE user_type = 'admin' AND is_read = FALSE");
        }
        
        if ($userType !== 'admin') {
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->fetch_assoc()['count'];
            $stmt->close();
        } else {
            $count = $stmt->fetch_assoc()['count'];
        }
        
        echo json_encode(['success' => true, 'count' => $count]);
        break;
}

$conn->close();
?>

