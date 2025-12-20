<?php
require_once '../config/config.php';
requireLogin('user');

header('Content-Type: application/json');
$conn = getDBConnection();
$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'submit':
        $providerId = intval($_POST['provider_id']);
        $campaignId = intval($_POST['campaign_id']);
        $rating = intval($_POST['rating']);
        $feedback = sanitizeInput($_POST['feedback'] ?? '');
        
        // Check if campaign belongs to user and is completed
        $campaignStmt = $conn->prepare("SELECT id FROM campaigns WHERE id = ? AND user_id = ? AND status = 'completed'");
        $campaignStmt->bind_param("ii", $campaignId, $userId);
        $campaignStmt->execute();
        if ($campaignStmt->get_result()->num_rows == 0) {
            echo json_encode(['success' => false, 'message' => 'ক্যাম্পেইন পাওয়া যায়নি বা সম্পন্ন হয়নি']);
            exit();
        }
        $campaignStmt->close();
        
        // Check if already reviewed
        $checkStmt = $conn->prepare("SELECT id FROM reviews WHERE campaign_id = ?");
        $checkStmt->bind_param("i", $campaignId);
        $checkStmt->execute();
        if ($checkStmt->get_result()->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'আপনি ইতিমধ্যে রিভিউ দিয়েছেন']);
            exit();
        }
        $checkStmt->close();
        
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, provider_id, campaign_id, rating, feedback) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiis", $userId, $providerId, $campaignId, $rating, $feedback);
        
        if ($stmt->execute()) {
            // Update provider rating
            $ratingStmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM reviews WHERE provider_id = ?");
            $ratingStmt->bind_param("i", $providerId);
            $ratingStmt->execute();
            $ratingData = $ratingStmt->get_result()->fetch_assoc();
            $ratingStmt->close();
            
            $updateProviderStmt = $conn->prepare("UPDATE providers SET rating = ?, total_reviews = ? WHERE id = ?");
            $updateProviderStmt->bind_param("dii", $ratingData['avg_rating'], $ratingData['total'], $providerId);
            $updateProviderStmt->execute();
            $updateProviderStmt->close();
            
            createNotification($conn, 'provider', null, $providerId, null, 'নতুন রিভিউ', "আপনার একটি নতুন রিভিউ এসেছে", 'review');
            
            echo json_encode(['success' => true, 'message' => 'রিভিউ সফলভাবে জমা দেওয়া হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'রিভিউ জমা দেওয়া ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;
}

$conn->close();
?>

