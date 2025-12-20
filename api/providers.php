<?php
require_once '../config/config.php';

header('Content-Type: application/json');
$conn = getDBConnection();
$userType = getUserType();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Guard: require user login for user-facing actions, return JSON instead of redirect
$userActions = ['search', 'request', 'my_providers'];
if (in_array($action, $userActions, true) && $userType !== 'user') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'সেশন মেয়াদোত্তীর্ণ বা অনুমতি নেই']);
    $conn->close();
    exit();
}

switch ($action) {
    case 'search':
        requireLogin('user');
        $userId = $_SESSION['user_id'];
        $category = $_GET['category'] ?? '';
        $budget = floatval($_GET['budget'] ?? 0);
        $experience = intval($_GET['experience'] ?? 0);
        $availability = $_GET['availability'] ?? '';

        $query = "SELECT p.*, 
                  (SELECT COUNT(*) FROM provider_requests pr WHERE pr.provider_id = p.id AND pr.user_id = ? AND pr.status != 'rejected') as already_requested
                  FROM providers p 
                  WHERE p.status = 'approved'";

        $params = [$userId];
        $types = "i";

        if ($category) {
            $query .= " AND p.category = ?";
            $params[] = $category;
            $types .= "s";
        }

        if ($budget > 0) {
            $query .= " AND p.budget_min <= ? AND p.budget_max >= ?";
            $params[] = $budget;
            $params[] = $budget;
            $types .= "dd";
        }

        if ($experience > 0) {
            $query .= " AND p.experience >= ?";
            $params[] = $experience;
            $types .= "i";
        }

        if ($availability) {
            $query .= " AND p.availability = ?";
            $params[] = $availability;
            $types .= "s";
        }

        $query .= " ORDER BY p.rating DESC, p.total_reviews DESC";

        $stmt = $conn->prepare($query);
        if (count($params) > 0) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $providers = [];
        while ($row = $result->fetch_assoc()) {
            $row['already_requested'] = $row['already_requested'] > 0;
            $providers[] = $row;
        }

        echo json_encode(['success' => true, 'data' => $providers]);
        $stmt->close();
        break;

    case 'request':
        requireLogin('user');
        $userId = $_SESSION['user_id'];
        $providerId = intval($_POST['provider_id']);

        // Check if already requested
        $check = $conn->prepare("SELECT id FROM provider_requests WHERE user_id = ? AND provider_id = ?");
        $check->bind_param("ii", $userId, $providerId);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'আপনি ইতিমধ্যে এই প্রোভাইডারকে অনুরোধ করেছেন']);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO provider_requests (user_id, provider_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $providerId);

        if ($stmt->execute()) {
            // Create chat lock
            $lockStmt = $conn->prepare("INSERT INTO chat_locks (user_id, provider_id, is_locked) VALUES (?, ?, TRUE) ON DUPLICATE KEY UPDATE is_locked = TRUE");
            $lockStmt->bind_param("ii", $userId, $providerId);
            $lockStmt->execute();
            $lockStmt->close();

            // Get provider name
            $providerStmt = $conn->prepare("SELECT name FROM providers WHERE id = ?");
            $providerStmt->bind_param("i", $providerId);
            $providerStmt->execute();
            $provider = $providerStmt->get_result()->fetch_assoc();
            $providerStmt->close();

            createNotification($conn, 'admin', null, null, null, 'নতুন প্রোভাইডার অনুরোধ', "ইউজার ID {$userId} প্রোভাইডার {$provider['name']} কে অনুরোধ করেছেন", 'provider_request');
            createNotification($conn, 'user', $userId, null, null, 'প্রোভাইডার অনুরোধ পাঠানো হয়েছে', "আপনার অনুরোধ অ্যাডমিনের কাছে পাঠানো হয়েছে", 'info');

            echo json_encode(['success' => true, 'message' => 'অনুরোধ সফলভাবে পাঠানো হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'অনুরোধ ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;

    case 'my_providers':
        requireLogin('user');
        $userId = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT p.*, pr.status as request_status, pr.approved_at 
                                FROM provider_requests pr 
                                JOIN providers p ON pr.provider_id = p.id 
                                WHERE pr.user_id = ? 
                                ORDER BY pr.requested_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $providers = [];
        while ($row = $result->fetch_assoc()) {
            // Check chat lock status
            $lockStmt = $conn->prepare("SELECT is_locked FROM chat_locks WHERE user_id = ? AND provider_id = ?");
            $lockStmt->bind_param("ii", $userId, $row['id']);
            $lockStmt->execute();
            $lockResult = $lockStmt->get_result();
            $lock = $lockResult->fetch_assoc();
            $row['chat_locked'] = $lock ? $lock['is_locked'] : true;
            $lockStmt->close();

            $providers[] = $row;
        }

        echo json_encode(['success' => true, 'data' => $providers]);
        $stmt->close();
        break;

    case 'provider_stats':
        requireLogin('provider');
        $providerId = $_SESSION['provider_id'];

        // Get provider info
        $providerStmt = $conn->prepare("SELECT rating, total_reviews FROM providers WHERE id = ?");
        $providerStmt->bind_param("i", $providerId);
        $providerStmt->execute();
        $provider = $providerStmt->get_result()->fetch_assoc();
        $providerStmt->close();

        // Get total clients
        $clientStmt = $conn->prepare("SELECT COUNT(DISTINCT user_id) as total FROM provider_requests WHERE provider_id = ? AND status = 'approved'");
        $clientStmt->bind_param("i", $providerId);
        $clientStmt->execute();
        $clients = $clientStmt->get_result()->fetch_assoc()['total'] ?? 0;
        $clientStmt->close();

        echo json_encode([
            'success' => true,
            'data' => [
                'rating' => (float) ($provider['rating'] ?? 0),
                'total_reviews' => (int) ($provider['total_reviews'] ?? 0),
                'total_clients' => (int) $clients
            ]
        ]);
        break;
}

$conn->close();
?>