<?php
require_once '../config/config.php';
requireLogin('admin');

header('Content-Type: application/json');
$conn = getDBConnection();
$adminId = $_SESSION['admin_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'approve_provider_registration':
        $providerId = intval($_POST['provider_id']);
        $stmt = $conn->prepare("UPDATE providers SET status = 'approved', approved_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $providerId);

        if ($stmt->execute()) {
            $providerStmt = $conn->prepare("SELECT name, email FROM providers WHERE id = ?");
            $providerStmt->bind_param("i", $providerId);
            $providerStmt->execute();
            $provider = $providerStmt->get_result()->fetch_assoc();
            $providerStmt->close();

            createNotification($conn, 'provider', null, $providerId, null, 'অ্যাকাউন্ট অনুমোদিত', "আপনার প্রোভাইডার অ্যাকাউন্ট অনুমোদিত হয়েছে", 'success');
            echo json_encode(['success' => true, 'message' => 'প্রোভাইডার অনুমোদিত হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'অনুমোদন ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;

    case 'reject_provider_registration':
        $providerId = intval($_POST['provider_id']);
        $stmt = $conn->prepare("UPDATE providers SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $providerId);

        if ($stmt->execute()) {
            createNotification($conn, 'provider', null, $providerId, null, 'অ্যাকাউন্ট প্রত্যাখ্যান', "দুঃখিত, আপনার প্রোভাইডার অ্যাকাউন্ট প্রত্যাখ্যান করা হয়েছে", 'error');
            echo json_encode(['success' => true, 'message' => 'প্রোভাইডার প্রত্যাখ্যান করা হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'প্রত্যাখ্যান ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;

    case 'approve_provider_request':
        $requestId = intval($_POST['request_id']);
        $stmt = $conn->prepare("UPDATE provider_requests SET status = 'approved', approved_at = NOW(), admin_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $adminId, $requestId);

        if ($stmt->execute()) {
            // Unlock chat
            $requestStmt = $conn->prepare("SELECT user_id, provider_id FROM provider_requests WHERE id = ?");
            $requestStmt->bind_param("i", $requestId);
            $requestStmt->execute();
            $request = $requestStmt->get_result()->fetch_assoc();
            $requestStmt->close();

            $lockStmt = $conn->prepare("INSERT INTO chat_locks (user_id, provider_id, is_locked, unlocked_at, unlocked_by) VALUES (?, ?, FALSE, NOW(), ?) ON DUPLICATE KEY UPDATE is_locked = FALSE, unlocked_at = NOW(), unlocked_by = ?");
            $lockStmt->bind_param("iiii", $request['user_id'], $request['provider_id'], $adminId, $adminId);
            $lockStmt->execute();
            $lockStmt->close();

            createNotification($conn, 'user', $request['user_id'], null, null, 'প্রোভাইডার অনুমোদিত', "আপনার প্রোভাইডার অনুরোধ অনুমোদিত হয়েছে। এখন আপনি চ্যাট করতে পারবেন।", 'success');
            createNotification($conn, 'provider', null, $request['provider_id'], null, 'নতুন ক্লায়েন্ট', "একজন নতুন ক্লায়েন্ট আপনার সাথে কাজ করতে চায়", 'info');

            echo json_encode(['success' => true, 'message' => 'অনুরোধ অনুমোদিত হয়েছে এবং চ্যাট আনলক করা হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'অনুমোদন ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;

    case 'reject_provider_request':
        $requestId = intval($_POST['request_id']);
        $stmt = $conn->prepare("UPDATE provider_requests SET status = 'rejected', rejected_at = NOW(), admin_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $adminId, $requestId);

        if ($stmt->execute()) {
            $requestStmt = $conn->prepare("SELECT user_id FROM provider_requests WHERE id = ?");
            $requestStmt->bind_param("i", $requestId);
            $requestStmt->execute();
            $request = $requestStmt->get_result()->fetch_assoc();
            $requestStmt->close();

            createNotification($conn, 'user', $request['user_id'], null, null, 'প্রোভাইডার অনুরোধ প্রত্যাখ্যান', "দুঃখিত, আপনার প্রোভাইডার অনুরোধ প্রত্যাখ্যান করা হয়েছে", 'error');

            echo json_encode(['success' => true, 'message' => 'অনুরোধ প্রত্যাখ্যান করা হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'প্রত্যাখ্যান ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;

    case 'unlock_chat':
        $userId = intval($_POST['user_id']);
        $providerId = intval($_POST['provider_id']);

        $stmt = $conn->prepare("INSERT INTO chat_locks (user_id, provider_id, is_locked, unlocked_at, unlocked_by) VALUES (?, ?, FALSE, NOW(), ?) ON DUPLICATE KEY UPDATE is_locked = FALSE, unlocked_at = NOW(), unlocked_by = ?");
        $stmt->bind_param("iiii", $userId, $providerId, $adminId, $adminId);

        if ($stmt->execute()) {
            createNotification($conn, 'user', $userId, null, null, 'চ্যাট আনলক করা হয়েছে', "আপনি এখন প্রোভাইডারের সাথে চ্যাট করতে পারবেন", 'success');
            createNotification($conn, 'provider', null, $providerId, null, 'চ্যাট আনলক করা হয়েছে', "আপনি এখন ক্লায়েন্টের সাথে চ্যাট করতে পারবেন", 'success');

            echo json_encode(['success' => true, 'message' => 'চ্যাট আনলক করা হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'আনলক ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;

    case 'dashboard_stats':
        // Total Users
        $usersStmt = $conn->query("SELECT COUNT(*) as total FROM users");
        $users = $usersStmt->fetch_assoc()['total'];
        $usersStmt->close();

        // Total Providers
        $providersStmt = $conn->query("SELECT COUNT(*) as total FROM providers WHERE status = 'approved'");
        $providers = $providersStmt->fetch_assoc()['total'];
        $providersStmt->close();

        // Active Campaigns
        $campaignsStmt = $conn->query("SELECT COUNT(*) as total FROM campaigns WHERE status = 'active'");
        $campaigns = $campaignsStmt->fetch_assoc()['total'];
        $campaignsStmt->close();

        // Pending Approvals
        $pendingRegStmt = $conn->query("SELECT COUNT(*) as total FROM providers WHERE status = 'pending'");
        $pendingProviderReg = $pendingRegStmt->fetch_assoc()['total'];
        $pendingRegStmt->close();

        $pendingReqStmt = $conn->query("SELECT COUNT(*) as total FROM provider_requests WHERE status = 'pending'");
        $pendingProviderReq = $pendingReqStmt->fetch_assoc()['total'];
        $pendingReqStmt->close();

        $pending = $pendingProviderReg + $pendingProviderReq;

        // Total Revenue
        $revenueStmt = $conn->query("SELECT SUM(amount) as total FROM payments WHERE status = 'completed'");
        $revenueRow = $revenueStmt->fetch_assoc();
        $revenue = ($revenueRow['total'] ?? 0) * 0.20; // 20% Platform Fee
        $revenueStmt->close();

        echo json_encode([
            'success' => true,
            'data' => [
                'total_users' => (int) $users,
                'total_providers' => (int) $providers,
                'active_campaigns' => (int) $campaigns,
                'pending_approvals' => (int) $pending,
                'total_revenue' => (float) $revenue
            ]
        ]);
        break;

    case 'pending_providers':
        $stmt = $conn->query("SELECT * FROM providers WHERE status = 'pending' ORDER BY created_at DESC");
        $providers = [];
        if ($stmt) {
            while ($row = $stmt->fetch_assoc()) {
                $providers[] = $row;
            }
            $stmt->close();
        }
        echo json_encode(['success' => true, 'data' => $providers]);
        break;

    case 'pending_requests':
        $stmt = $conn->query("SELECT pr.*, u.name as user_name, u.email as user_email, u.phone as user_phone, 
                              p.name as provider_name, p.email as provider_email, p.category as provider_category
                              FROM provider_requests pr 
                              JOIN users u ON pr.user_id = u.id 
                              JOIN providers p ON pr.provider_id = p.id 
                              WHERE pr.status = 'pending' 
                              ORDER BY pr.requested_at DESC");
        $requests = [];
        if ($stmt) {
            while ($row = $stmt->fetch_assoc()) {
                $requests[] = $row;
            }
            $stmt->close();
        }
        echo json_encode(['success' => true, 'data' => $requests]);
        break;

    case 'all_users':
        $stmt = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = [];
        if ($stmt) {
            while ($row = $stmt->fetch_assoc()) {
                // Get user statistics
                $campaignStmt = $conn->prepare("SELECT COUNT(*) as total FROM campaigns WHERE user_id = ?");
                $campaignStmt->bind_param("i", $row['id']);
                $campaignStmt->execute();
                $campaigns = $campaignStmt->get_result()->fetch_assoc()['total'];
                $campaignStmt->close();

                $paymentStmt = $conn->prepare("SELECT SUM(amount) as total FROM payments WHERE user_id = ? AND status = 'completed'");
                $paymentStmt->bind_param("i", $row['id']);
                $paymentStmt->execute();
                $spent = $paymentStmt->get_result()->fetch_assoc()['total'] ?? 0;
                $paymentStmt->close();

                $providerStmt = $conn->prepare("SELECT COUNT(*) as total FROM provider_requests WHERE user_id = ? AND status = 'approved'");
                $providerStmt->bind_param("i", $row['id']);
                $providerStmt->execute();
                $providers = $providerStmt->get_result()->fetch_assoc()['total'];
                $providerStmt->close();

                $row['total_campaigns'] = $campaigns;
                $row['total_spent'] = $spent;
                $row['total_providers'] = $providers;
                $users[] = $row;
            }
            $stmt->close();
        }
        echo json_encode(['success' => true, 'data' => $users]);
        break;

    case 'user_details':
        $userId = intval($_GET['user_id']);
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user) {
            // Get statistics
            $campaignStmt = $conn->prepare("SELECT COUNT(*) as total FROM campaigns WHERE user_id = ?");
            $campaignStmt->bind_param("i", $userId);
            $campaignStmt->execute();
            $user['total_campaigns'] = $campaignStmt->get_result()->fetch_assoc()['total'];
            $campaignStmt->close();

            $paymentStmt = $conn->prepare("SELECT SUM(amount) as total FROM payments WHERE user_id = ? AND status = 'completed'");
            $paymentStmt->bind_param("i", $userId);
            $paymentStmt->execute();
            $user['total_spent'] = $paymentStmt->get_result()->fetch_assoc()['total'] ?? 0;
            $paymentStmt->close();

            $providerStmt = $conn->prepare("SELECT COUNT(*) as total FROM provider_requests WHERE user_id = ? AND status = 'approved'");
            $providerStmt->bind_param("i", $userId);
            $providerStmt->execute();
            $user['total_providers'] = $providerStmt->get_result()->fetch_assoc()['total'];
            $providerStmt->close();

            echo json_encode(['success' => true, 'data' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ইউজার পাওয়া যায়নি']);
        }
        break;

    case 'update_user_status':
        $userId = intval($_POST['user_id']);
        $status = $_POST['status'];

        if (!in_array($status, ['active', 'inactive'])) {
            echo json_encode(['success' => false, 'message' => 'অবৈধ স্ট্যাটাস']);
            exit();
        }

        $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $userId);

        if ($stmt->execute()) {
            createNotification($conn, 'user', $userId, null, null, 'অ্যাকাউন্ট স্ট্যাটাস পরিবর্তন', "আপনার অ্যাকাউন্ট ${status} করা হয়েছে", $status === 'active' ? 'success' : 'warning');
            echo json_encode(['success' => true, 'message' => 'স্ট্যাটাস আপডেট করা হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'স্ট্যাটাস আপডেট ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;

    case 'all_providers':
        $stmt = $conn->query("SELECT * FROM providers ORDER BY created_at DESC");
        $providers = [];
        if ($stmt) {
            while ($row = $stmt->fetch_assoc()) {
                // Get provider statistics
                $campaignStmt = $conn->prepare("SELECT COUNT(*) as total FROM campaigns WHERE provider_id = ?");
                $campaignStmt->bind_param("i", $row['id']);
                $campaignStmt->execute();
                $campaigns = $campaignStmt->get_result()->fetch_assoc()['total'];
                $campaignStmt->close();

                $paymentStmt = $conn->prepare("SELECT SUM(amount) as total FROM payments WHERE provider_id = ? AND status = 'completed'");
                $paymentStmt->bind_param("i", $row['id']);
                $paymentStmt->execute();
                $earnings = $paymentStmt->get_result()->fetch_assoc()['total'] ?? 0;
                $paymentStmt->close();

                $clientStmt = $conn->prepare("SELECT COUNT(DISTINCT user_id) as total FROM provider_requests WHERE provider_id = ? AND status = 'approved'");
                $clientStmt->bind_param("i", $row['id']);
                $clientStmt->execute();
                $clients = $clientStmt->get_result()->fetch_assoc()['total'];
                $clientStmt->close();

                $row['total_campaigns'] = $campaigns;
                $row['total_earnings'] = $earnings;
                $row['total_clients'] = $clients;
                $providers[] = $row;
            }
            $stmt->close();
        }
        echo json_encode(['success' => true, 'data' => $providers]);
        break;

    case 'provider_details':
        $providerId = intval($_GET['provider_id']);
        $stmt = $conn->prepare("SELECT * FROM providers WHERE id = ?");
        $stmt->bind_param("i", $providerId);
        $stmt->execute();
        $provider = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($provider) {
            // Get statistics
            $campaignStmt = $conn->prepare("SELECT COUNT(*) as total FROM campaigns WHERE provider_id = ?");
            $campaignStmt->bind_param("i", $providerId);
            $campaignStmt->execute();
            $provider['total_campaigns'] = $campaignStmt->get_result()->fetch_assoc()['total'];
            $campaignStmt->close();

            $paymentStmt = $conn->prepare("SELECT SUM(amount) as total FROM payments WHERE provider_id = ? AND status = 'completed'");
            $paymentStmt->bind_param("i", $providerId);
            $paymentStmt->execute();
            $provider['total_earnings'] = $paymentStmt->get_result()->fetch_assoc()['total'] ?? 0;
            $paymentStmt->close();

            $clientStmt = $conn->prepare("SELECT COUNT(DISTINCT user_id) as total FROM provider_requests WHERE provider_id = ? AND status = 'approved'");
            $clientStmt->bind_param("i", $providerId);
            $clientStmt->execute();
            $provider['total_clients'] = $clientStmt->get_result()->fetch_assoc()['total'];
            $clientStmt->close();

            echo json_encode(['success' => true, 'data' => $provider]);
        } else {
            echo json_encode(['success' => false, 'message' => 'প্রোভাইডার পাওয়া যায়নি']);
        }
        break;

    case 'suspend_provider':
        $providerId = intval($_POST['provider_id']);
        $stmt = $conn->prepare("UPDATE providers SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $providerId);

        if ($stmt->execute()) {
            createNotification($conn, 'provider', null, $providerId, null, 'অ্যাকাউন্ট Suspend', "আপনার প্রোভাইডার অ্যাকাউন্ট suspend করা হয়েছে", 'warning');
            echo json_encode(['success' => true, 'message' => 'প্রোভাইডার suspend করা হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Suspend ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;

    case 'all_payouts':
        $stmt = $conn->query("SELECT pr.*, p.name as provider_name, p.email as provider_email, p.phone as provider_phone 
                              FROM payout_requests pr 
                              JOIN providers p ON pr.provider_id = p.id 
                              ORDER BY pr.requested_at DESC");
        $payouts = [];
        if ($stmt) {
            while ($row = $stmt->fetch_assoc()) {
                $payouts[] = $row;
            }
            $stmt->close();
        }
        echo json_encode(['success' => true, 'data' => $payouts]);
        break;

    case 'payout_details':
        $payoutId = intval($_GET['payout_id']);
        $stmt = $conn->prepare("SELECT pr.*, p.name as provider_name, p.email as provider_email, p.phone as provider_phone 
                                FROM payout_requests pr 
                                JOIN providers p ON pr.provider_id = p.id 
                                WHERE pr.id = ?");
        $stmt->bind_param("i", $payoutId);
        $stmt->execute();
        $payout = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($payout) {
            echo json_encode(['success' => true, 'data' => $payout]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Payout পাওয়া যায়নি']);
        }
        break;

    case 'reject_payout':
        $requestId = intval($_POST['request_id']);
        $stmt = $conn->prepare("UPDATE payout_requests SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $requestId);

        if ($stmt->execute()) {
            $requestStmt = $conn->prepare("SELECT provider_id, amount FROM payout_requests WHERE id = ?");
            $requestStmt->bind_param("i", $requestId);
            $requestStmt->execute();
            $request = $requestStmt->get_result()->fetch_assoc();
            $requestStmt->close();

            createNotification($conn, 'provider', null, $request['provider_id'], null, 'Payout প্রত্যাখ্যান', "আপনার payout অনুরোধ প্রত্যাখ্যান করা হয়েছে", 'error');

            echo json_encode(['success' => true, 'message' => 'Payout প্রত্যাখ্যান করা হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'প্রত্যাখ্যান ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;

    case 'complete_payout':
        $requestId = intval($_POST['request_id']);
        $stmt = $conn->prepare("UPDATE payout_requests SET status = 'completed' WHERE id = ?");
        $stmt->bind_param("i", $requestId);

        if ($stmt->execute()) {
            $requestStmt = $conn->prepare("SELECT provider_id, amount FROM payout_requests WHERE id = ?");
            $requestStmt->bind_param("i", $requestId);
            $requestStmt->execute();
            $request = $requestStmt->get_result()->fetch_assoc();
            $requestStmt->close();

            createNotification($conn, 'provider', null, $request['provider_id'], null, 'Payout সম্পন্ন', "আপনার payout অনুরোধ সম্পন্ন হয়েছে", 'success');

            echo json_encode(['success' => true, 'message' => 'Payout সম্পন্ন হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'সম্পন্ন ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;
}

$conn->close();
?>