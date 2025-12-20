<?php
require_once '../config/config.php';

header('Content-Type: application/json');
$conn = getDBConnection();
$userType = getUserType();

if ($userType === 'user') {
    requireLogin('user');
    $userId = $_SESSION['user_id'];
} elseif ($userType === 'provider') {
    requireLogin('provider');
    $providerId = $_SESSION['provider_id'];
} elseif ($userType === 'admin') {
    requireLogin('admin');
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        requireLogin('user');
        $providerId = intval($_POST['provider_id']);
        $title = sanitizeInput($_POST['title']);
        $platform = $_POST['platform'];
        $budget = floatval($_POST['budget']);
        $targetAudience = sanitizeInput($_POST['target_audience']);
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        
        // Check if provider request is approved
        $check = $conn->prepare("SELECT id FROM provider_requests WHERE user_id = ? AND provider_id = ? AND status = 'approved'");
        $check->bind_param("ii", $userId, $providerId);
        $check->execute();
        if ($check->get_result()->num_rows == 0) {
            echo json_encode(['success' => false, 'message' => 'প্রোভাইডার অনুমোদিত নয়']);
            exit();
        }
        
        $logoFile = '';
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $uploadDir = UPLOAD_DIR . 'campaigns/';
            $logoFile = time() . '_' . basename($_FILES['logo']['name']);
            move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $logoFile);
        }
        
        $stmt = $conn->prepare("INSERT INTO campaigns (user_id, provider_id, title, platform, budget, target_audience, start_date, end_date, logo_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissdssss", $userId, $providerId, $title, $platform, $budget, $targetAudience, $startDate, $endDate, $logoFile);
        
        if ($stmt->execute()) {
            $campaignId = $conn->insert_id;
            
            // Create initial campaign update
            $updateStmt = $conn->prepare("INSERT INTO campaign_updates (campaign_id, provider_id) VALUES (?, ?)");
            $updateStmt->bind_param("ii", $campaignId, $providerId);
            $updateStmt->execute();
            $updateStmt->close();
            
            createNotification($conn, 'provider', null, $providerId, null, 'নতুন ক্যাম্পেইন', "আপনার জন্য একটি নতুন ক্যাম্পেইন তৈরি করা হয়েছে: {$title}", 'campaign');
            createNotification($conn, 'user', $userId, null, null, 'ক্যাম্পেইন তৈরি হয়েছে', "আপনার ক্যাম্পেইন সফলভাবে তৈরি হয়েছে", 'success');
            
            echo json_encode(['success' => true, 'message' => 'ক্যাম্পেইন সফলভাবে তৈরি হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ক্যাম্পেইন তৈরি ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;
        
    case 'my_campaigns':
        requireLogin('user');
        $stmt = $conn->prepare("SELECT c.*, p.name as provider_name, p.category as provider_category 
                                FROM campaigns c 
                                JOIN providers p ON c.provider_id = p.id 
                                WHERE c.user_id = ? 
                                ORDER BY c.created_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $campaigns = [];
        while ($row = $result->fetch_assoc()) {
            // Get latest update
            $updateStmt = $conn->prepare("SELECT * FROM campaign_updates WHERE campaign_id = ? ORDER BY updated_at DESC LIMIT 1");
            $updateStmt->bind_param("i", $row['id']);
            $updateStmt->execute();
            $update = $updateStmt->get_result()->fetch_assoc();
            $row['latest_update'] = $update;
            $updateStmt->close();
            
            $campaigns[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $campaigns]);
        $stmt->close();
        break;
        
    case 'provider_campaigns':
        requireLogin('provider');
        $stmt = $conn->prepare("SELECT c.*, u.name as user_name, u.company_name 
                                FROM campaigns c 
                                JOIN users u ON c.user_id = u.id 
                                WHERE c.provider_id = ? 
                                ORDER BY c.created_at DESC");
        $stmt->bind_param("i", $providerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $campaigns = [];
        while ($row = $result->fetch_assoc()) {
            $updateStmt = $conn->prepare("SELECT * FROM campaign_updates WHERE campaign_id = ? ORDER BY updated_at DESC LIMIT 1");
            $updateStmt->bind_param("i", $row['id']);
            $updateStmt->execute();
            $update = $updateStmt->get_result()->fetch_assoc();
            $row['latest_update'] = $update;
            $updateStmt->close();
            
            $campaigns[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $campaigns]);
        $stmt->close();
        break;
        
    case 'update_progress':
        requireLogin('provider');
        $campaignId = intval($_POST['campaign_id']);
        $workStarted = isset($_POST['work_started']) ? 1 : 0;
        $adResults = sanitizeInput($_POST['ad_results'] ?? '');
        $spendReport = floatval($_POST['spend_report'] ?? 0);
        $leadsGenerated = intval($_POST['leads_generated'] ?? 0);
        $engagementReach = intval($_POST['engagement_reach'] ?? 0);
        $dailyAnalytics = sanitizeInput($_POST['daily_analytics'] ?? '');
        $notes = sanitizeInput($_POST['notes'] ?? '');
        
        // Check if campaign belongs to provider
        $check = $conn->prepare("SELECT id FROM campaigns WHERE id = ? AND provider_id = ?");
        $check->bind_param("ii", $campaignId, $providerId);
        $check->execute();
        if ($check->get_result()->num_rows == 0) {
            echo json_encode(['success' => false, 'message' => 'ক্যাম্পেইন পাওয়া যায়নি']);
            exit();
        }
        
        $stmt = $conn->prepare("UPDATE campaign_updates SET work_started = ?, ad_results = ?, spend_report = ?, leads_generated = ?, engagement_reach = ?, daily_analytics = ?, notes = ? WHERE campaign_id = ?");
        $stmt->bind_param("isdiiisi", $workStarted, $adResults, $spendReport, $leadsGenerated, $engagementReach, $dailyAnalytics, $notes, $campaignId);
        
        if ($stmt->execute()) {
            // Get user_id for notification
            $userStmt = $conn->prepare("SELECT user_id, title FROM campaigns WHERE id = ?");
            $userStmt->bind_param("i", $campaignId);
            $userStmt->execute();
            $campaign = $userStmt->get_result()->fetch_assoc();
            $userStmt->close();
            
            createNotification($conn, 'user', $campaign['user_id'], null, null, 'ক্যাম্পেইন আপডেট', "ক্যাম্পেইন '{$campaign['title']}' আপডেট করা হয়েছে", 'update');
            
            echo json_encode(['success' => true, 'message' => 'আপডেট সফল হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'আপডেট ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;
        
    case 'all_campaigns':
        requireLogin('admin');
        $stmt = $conn->prepare("SELECT c.*, u.name as user_name, p.name as provider_name 
                                FROM campaigns c 
                                JOIN users u ON c.user_id = u.id 
                                JOIN providers p ON c.provider_id = p.id 
                                ORDER BY c.created_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $campaigns = [];
        while ($row = $result->fetch_assoc()) {
            $campaigns[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $campaigns]);
        $stmt->close();
        break;
        
    case 'update_status':
        requireLogin('admin');
        $campaignId = intval($_POST['campaign_id']);
        $status = $_POST['status'];
        
        $allowedStatuses = ['pending', 'active', 'completed', 'cancelled'];
        if (!in_array($status, $allowedStatuses)) {
            echo json_encode(['success' => false, 'message' => 'অবৈধ স্ট্যাটাস']);
            exit();
        }
        
        $stmt = $conn->prepare("UPDATE campaigns SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $campaignId);
        
        if ($stmt->execute()) {
            // Get campaign info for notification
            $campaignStmt = $conn->prepare("SELECT user_id, provider_id, title FROM campaigns WHERE id = ?");
            $campaignStmt->bind_param("i", $campaignId);
            $campaignStmt->execute();
            $campaign = $campaignStmt->get_result()->fetch_assoc();
            $campaignStmt->close();
            
            createNotification($conn, 'user', $campaign['user_id'], null, null, 'ক্যাম্পেইন স্ট্যাটাস আপডেট', "ক্যাম্পেইন '{$campaign['title']}' এর স্ট্যাটাস {$status} হয়েছে", 'update');
            createNotification($conn, 'provider', null, $campaign['provider_id'], null, 'ক্যাম্পেইন স্ট্যাটাস আপডেট', "ক্যাম্পেইন '{$campaign['title']}' এর স্ট্যাটাস {$status} হয়েছে", 'update');
            
            echo json_encode(['success' => true, 'message' => 'স্ট্যাটাস আপডেট করা হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'স্ট্যাটাস আপডেট ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;
        
    case 'campaign_updates':
        $campaignId = intval($_GET['campaign_id']);
        $stmt = $conn->prepare("SELECT * FROM campaign_updates WHERE campaign_id = ? ORDER BY updated_at DESC LIMIT 1");
        $stmt->bind_param("i", $campaignId);
        $stmt->execute();
        $result = $stmt->get_result();
        $update = $result->fetch_assoc();
        $stmt->close();
        
        echo json_encode(['success' => true, 'data' => $update]);
        break;
}

$conn->close();
?>

