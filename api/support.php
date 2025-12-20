<?php
require_once '../config/config.php';

header('Content-Type: application/json');
$conn = getDBConnection();
$userType = getUserType();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        if ($userType === 'user') {
            requireLogin('user');
            $userId = $_SESSION['user_id'];
            $providerId = null;
            $userTypeValue = 'user';
        } elseif ($userType === 'provider') {
            requireLogin('provider');
            $providerId = $_SESSION['provider_id'];
            $userId = null;
            $userTypeValue = 'provider';
        } else {
            echo json_encode(['success' => false, 'message' => 'লগইন প্রয়োজন']);
            exit();
        }
        
        $subject = sanitizeInput($_POST['subject']);
        $message = sanitizeInput($_POST['message']);
        
        $stmt = $conn->prepare("INSERT INTO support_tickets (user_id, provider_id, user_type, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $userId, $providerId, $userTypeValue, $subject, $message);
        
        if ($stmt->execute()) {
            createNotification($conn, 'admin', null, null, null, 'নতুন সাপোর্ট টিকেট', "একটি নতুন সাপোর্ট টিকেট তৈরি করা হয়েছে: {$subject}", 'support');
            echo json_encode(['success' => true, 'message' => 'টিকেট সফলভাবে তৈরি হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'টিকেট তৈরি ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;
        
    case 'my_tickets':
        if ($userType === 'user') {
            requireLogin('user');
            $userId = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT * FROM support_tickets WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->bind_param("i", $userId);
        } elseif ($userType === 'provider') {
            requireLogin('provider');
            $providerId = $_SESSION['provider_id'];
            $stmt = $conn->prepare("SELECT * FROM support_tickets WHERE provider_id = ? ORDER BY created_at DESC");
            $stmt->bind_param("i", $providerId);
        } else {
            echo json_encode(['success' => false, 'message' => 'লগইন প্রয়োজন']);
            exit();
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $tickets = [];
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $tickets]);
        $stmt->close();
        break;
        
    case 'admin_tickets':
        requireLogin('admin');
        $stmt = $conn->query("SELECT st.*, 
                              CASE 
                                  WHEN st.user_type = 'user' THEN u.name
                                  WHEN st.user_type = 'provider' THEN p.name
                              END as user_name,
                              CASE 
                                  WHEN st.user_type = 'user' THEN u.email
                                  WHEN st.user_type = 'provider' THEN p.email
                              END as user_email
                              FROM support_tickets st
                              LEFT JOIN users u ON st.user_id = u.id AND st.user_type = 'user'
                              LEFT JOIN providers p ON st.provider_id = p.id AND st.user_type = 'provider'
                              ORDER BY st.created_at DESC");
        
        $tickets = [];
        while ($row = $stmt->fetch_assoc()) {
            $tickets[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $tickets]);
        break;
        
    case 'respond':
        requireLogin('admin');
        $ticketId = intval($_POST['ticket_id']);
        $response = sanitizeInput($_POST['response']);
        $status = $_POST['status'] ?? 'in_progress';
        
        $stmt = $conn->prepare("UPDATE support_tickets SET admin_response = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssi", $response, $status, $ticketId);
        
        if ($stmt->execute()) {
            $ticketStmt = $conn->prepare("SELECT user_id, provider_id, user_type FROM support_tickets WHERE id = ?");
            $ticketStmt->bind_param("i", $ticketId);
            $ticketStmt->execute();
            $ticket = $ticketStmt->get_result()->fetch_assoc();
            $ticketStmt->close();
            
            if ($ticket['user_type'] === 'user') {
                createNotification($conn, 'user', $ticket['user_id'], null, null, 'সাপোর্ট টিকেট আপডেট', "আপনার সাপোর্ট টিকেটে একটি উত্তর এসেছে", 'support');
            } else {
                createNotification($conn, 'provider', null, $ticket['provider_id'], null, 'সাপোর্ট টিকেট আপডেট', "আপনার সাপোর্ট টিকেটে একটি উত্তর এসেছে", 'support');
            }
            
            echo json_encode(['success' => true, 'message' => 'উত্তর পাঠানো হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'উত্তর পাঠানো ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;
}

$conn->close();
?>

