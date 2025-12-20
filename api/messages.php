<?php
require_once '../config/config.php';

header('Content-Type: application/json');
$conn = getDBConnection();
$userType = getUserType();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'send':
        if ($userType === 'user') {
            requireLogin('user');
            $userId = $_SESSION['user_id'];
            $providerId = intval($_POST['provider_id']);

            // Check if chat is unlocked
            $lockCheck = $conn->prepare("SELECT is_locked FROM chat_locks WHERE user_id = ? AND provider_id = ?");
            $lockCheck->bind_param("ii", $userId, $providerId);
            $lockCheck->execute();
            $lock = $lockCheck->get_result()->fetch_assoc();
            $lockCheck->close();

            if (!$lock || $lock['is_locked']) {
                echo json_encode(['success' => false, 'message' => 'চ্যাট এখনও লক করা আছে। অ্যাডমিন অনুমোদনের জন্য অপেক্ষা করুন।']);
                exit();
            }

            $message = sanitizeInput($_POST['message']);
            $attachment = '';

            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
                $uploadDir = UPLOAD_DIR . 'messages/';
                $attachment = time() . '_' . basename($_FILES['attachment']['name']);
                move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadDir . $attachment);
            }

            $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, sender_type, receiver_type, message, attachment) VALUES (?, ?, 'user', 'provider', ?, ?)");
            $stmt->bind_param("iiss", $userId, $providerId, $message, $attachment);

            if ($stmt->execute()) {
                createNotification($conn, 'provider', null, $providerId, null, 'নতুন মেসেজ', "আপনার কাছে একটি নতুন মেসেজ এসেছে", 'message');
                echo json_encode(['success' => true, 'message' => 'মেসেজ পাঠানো হয়েছে']);
            } else {
                echo json_encode(['success' => false, 'message' => 'মেসেজ পাঠানো ব্যর্থ হয়েছে']);
            }
            $stmt->close();
        } elseif ($userType === 'provider') {
            requireLogin('provider');
            $providerId = $_SESSION['provider_id'];
            $userId = intval($_POST['user_id']);

            $lockCheck = $conn->prepare("SELECT is_locked FROM chat_locks WHERE user_id = ? AND provider_id = ?");
            $lockCheck->bind_param("ii", $userId, $providerId);
            $lockCheck->execute();
            $lock = $lockCheck->get_result()->fetch_assoc();
            $lockCheck->close();

            if (!$lock || $lock['is_locked']) {
                echo json_encode(['success' => false, 'message' => 'চ্যাট এখনও লক করা আছে']);
                exit();
            }

            $message = sanitizeInput($_POST['message']);
            $attachment = '';

            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
                $uploadDir = UPLOAD_DIR . 'messages/';
                $attachment = time() . '_' . basename($_FILES['attachment']['name']);
                move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadDir . $attachment);
            }

            $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, sender_type, receiver_type, message, attachment) VALUES (?, ?, 'provider', 'user', ?, ?)");
            $stmt->bind_param("iiss", $providerId, $userId, $message, $attachment);

            if ($stmt->execute()) {
                createNotification($conn, 'user', $userId, null, null, 'নতুন মেসেজ', "আপনার কাছে একটি নতুন মেসেজ এসেছে", 'message');
                echo json_encode(['success' => true, 'message' => 'মেসেজ পাঠানো হয়েছে']);
            } else {
                echo json_encode(['success' => false, 'message' => 'মেসেজ পাঠানো ব্যর্থ হয়েছে']);
            }
            $stmt->close();
        }
        break;

    case 'get_conversation':
        if ($userType === 'user') {
            requireLogin('user');
            $userId = $_SESSION['user_id'];
            $providerId = intval($_GET['provider_id']);

            $stmt = $conn->prepare("SELECT * FROM messages WHERE 
                                    ((sender_id = ? AND sender_type = 'user' AND receiver_id = ? AND receiver_type = 'provider') OR 
                                     (sender_id = ? AND sender_type = 'provider' AND receiver_id = ? AND receiver_type = 'user'))
                                    ORDER BY created_at ASC");
            $stmt->bind_param("iiii", $userId, $providerId, $providerId, $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            $messages = [];
            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }

            // Mark as seen
            $updateStmt = $conn->prepare("UPDATE messages SET is_seen = TRUE WHERE receiver_id = ? AND receiver_type = 'user' AND sender_id = ? AND sender_type = 'provider'");
            $updateStmt->bind_param("ii", $userId, $providerId);
            $updateStmt->execute();
            $updateStmt->close();

            echo json_encode(['success' => true, 'data' => $messages]);
            $stmt->close();
        } elseif ($userType === 'provider') {
            requireLogin('provider');
            $providerId = $_SESSION['provider_id'];
            $userId = intval($_GET['user_id']);

            $stmt = $conn->prepare("SELECT * FROM messages WHERE 
                                    ((sender_id = ? AND sender_type = 'user' AND receiver_id = ? AND receiver_type = 'provider') OR 
                                     (sender_id = ? AND sender_type = 'provider' AND receiver_id = ? AND receiver_type = 'user'))
                                    ORDER BY created_at ASC");
            $stmt->bind_param("iiii", $userId, $providerId, $providerId, $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            $messages = [];
            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }

            $updateStmt = $conn->prepare("UPDATE messages SET is_seen = TRUE WHERE receiver_id = ? AND receiver_type = 'provider' AND sender_id = ? AND sender_type = 'user'");
            $updateStmt->bind_param("ii", $providerId, $userId);
            $updateStmt->execute();
            $updateStmt->close();

            echo json_encode(['success' => true, 'data' => $messages]);
            $stmt->close();
        }
        break;

    case 'conversations_list':
        if ($userType === 'user') {
            requireLogin('user');
            $userId = $_SESSION['user_id'];

            $stmt = $conn->prepare("SELECT DISTINCT p.id, p.name, p.company_name, p.category,
                                    (SELECT COUNT(*) FROM messages WHERE receiver_id = ? AND receiver_type = 'user' AND sender_id = p.id AND sender_type = 'provider' AND is_seen = FALSE) as unread_count,
                                    (SELECT message FROM messages WHERE (sender_id = ? AND sender_type = 'user' AND receiver_id = p.id AND receiver_type = 'provider') OR (sender_id = p.id AND sender_type = 'provider' AND receiver_id = ? AND receiver_type = 'user') ORDER BY created_at DESC LIMIT 1) as last_message,
                                    (SELECT created_at FROM messages WHERE (sender_id = ? AND sender_type = 'user' AND receiver_id = p.id AND receiver_type = 'provider') OR (sender_id = p.id AND sender_type = 'provider' AND receiver_id = ? AND receiver_type = 'user') ORDER BY created_at DESC LIMIT 1) as last_message_time,
                                    cl.is_locked
                                    FROM providers p
                                    JOIN provider_requests pr ON p.id = pr.provider_id
                                    LEFT JOIN chat_locks cl ON cl.user_id = ? AND cl.provider_id = p.id
                                    WHERE pr.user_id = ? AND pr.status = 'approved'
                                    ORDER BY last_message_time DESC");
            $stmt->bind_param("iiiiiii", $userId, $userId, $userId, $userId, $userId, $userId, $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            $conversations = [];
            while ($row = $result->fetch_assoc()) {
                $conversations[] = $row;
            }

            echo json_encode(['success' => true, 'data' => $conversations]);
            $stmt->close();
        } elseif ($userType === 'provider') {
            requireLogin('provider');
            $providerId = $_SESSION['provider_id'];

            $stmt = $conn->prepare("SELECT DISTINCT u.id, u.name, u.company_name,
                                    (SELECT COUNT(*) FROM messages WHERE receiver_id = ? AND receiver_type = 'provider' AND sender_id = u.id AND sender_type = 'user' AND is_seen = FALSE) as unread_count,
                                    (SELECT message FROM messages WHERE (sender_id = u.id AND sender_type = 'user' AND receiver_id = ? AND receiver_type = 'provider') OR (sender_id = ? AND sender_type = 'provider' AND receiver_id = u.id AND receiver_type = 'user') ORDER BY created_at DESC LIMIT 1) as last_message,
                                    (SELECT created_at FROM messages WHERE (sender_id = u.id AND sender_type = 'user' AND receiver_id = ? AND receiver_type = 'provider') OR (sender_id = ? AND sender_type = 'provider' AND receiver_id = u.id AND receiver_type = 'user') ORDER BY created_at DESC LIMIT 1) as last_message_time,
                                    cl.is_locked
                                    FROM users u
                                    JOIN provider_requests pr ON u.id = pr.user_id
                                    LEFT JOIN chat_locks cl ON cl.user_id = u.id AND cl.provider_id = ?
                                    WHERE pr.provider_id = ? AND pr.status = 'approved'
                                    ORDER BY last_message_time DESC");
            $stmt->bind_param("iiiiiii", $providerId, $providerId, $providerId, $providerId, $providerId, $providerId, $providerId);
            $stmt->execute();
            $result = $stmt->get_result();

            $conversations = [];
            while ($row = $result->fetch_assoc()) {
                $conversations[] = $row;
            }

            echo json_encode(['success' => true, 'data' => $conversations]);
            $stmt->close();
        }
        break;
}

$conn->close();
?>