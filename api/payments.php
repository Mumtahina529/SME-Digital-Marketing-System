<?php
require_once '../config/config.php';

header('Content-Type: application/json');
$conn = getDBConnection();
$userType = getUserType();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        requireLogin('user');
        $userId = $_SESSION['user_id'];
        $campaignId = intval($_POST['campaign_id']);
        $amount = floatval($_POST['amount']);
        $paymentMethod = $_POST['payment_method'];
        $transactionId = sanitizeInput($_POST['transaction_id'] ?? '');

        // Get provider_id from campaign
        $campaignStmt = $conn->prepare("SELECT provider_id FROM campaigns WHERE id = ? AND user_id = ?");
        $campaignStmt->bind_param("ii", $campaignId, $userId);
        $campaignStmt->execute();
        $campaign = $campaignStmt->get_result()->fetch_assoc();
        $campaignStmt->close();

        if (!$campaign) {
            echo json_encode(['success' => false, 'message' => 'ক্যাম্পেইন পাওয়া যায়নি']);
            exit();
        }

        $providerId = $campaign['provider_id'];

        if ($paymentMethod === 'cod') {
            $status = 'pending';
        } else {
            $status = 'completed';
        }

        $stmt = $conn->prepare("INSERT INTO payments (user_id, provider_id, campaign_id, amount, payment_method, transaction_id, status, paid_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiidsss", $userId, $providerId, $campaignId, $amount, $paymentMethod, $transactionId, $status);

        if ($stmt->execute()) {
            if ($status === 'completed') {
                $updateStmt = $conn->prepare("UPDATE campaigns SET status = 'active' WHERE id = ?");
                $updateStmt->bind_param("i", $campaignId);
                $updateStmt->execute();
                $updateStmt->close();
            }

            createNotification($conn, 'provider', null, $providerId, null, 'পেমেন্ট প্রাপ্ত', "ক্যাম্পেইন ID {$campaignId} এর জন্য পেমেন্ট প্রাপ্ত হয়েছে", 'payment');
            createNotification($conn, 'user', $userId, null, null, 'পেমেন্ট সফল', "আপনার পেমেন্ট সফল হয়েছে", 'success');

            echo json_encode(['success' => true, 'message' => 'পেমেন্ট সফল হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'পেমেন্ট ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;

    case 'my_payments':
        requireLogin('user');
        $userId = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT p.*, pr.name as provider_name, c.title as campaign_title 
                                FROM payments p 
                                JOIN providers pr ON p.provider_id = pr.id 
                                JOIN campaigns c ON p.campaign_id = c.id 
                                WHERE p.user_id = ? 
                                ORDER BY p.created_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $payments = [];
        while ($row = $result->fetch_assoc()) {
            $payments[] = $row;
        }

        echo json_encode(['success' => true, 'data' => $payments]);
        $stmt->close();
        break;

    case 'request_payout':
        requireLogin('provider');
        $providerId = $_SESSION['provider_id'];
        $amount = floatval($_POST['amount']);
        $paymentMethod = sanitizeInput($_POST['payment_method']);
        $accountDetails = sanitizeInput($_POST['account_details']);

        // Check available balance
        $balanceStmt = $conn->prepare("SELECT SUM(amount) as total FROM payments WHERE provider_id = ? AND status = 'completed'");
        $balanceStmt->bind_param("i", $providerId);
        $balanceStmt->execute();
        $totalEarned = ($balanceStmt->get_result()->fetch_assoc()['total'] ?? 0) * 0.80; // 80% Provider Share
        $balanceStmt->close();

        $paidStmt = $conn->prepare("SELECT SUM(amount) as total FROM payout_requests WHERE provider_id = ? AND status IN ('pending', 'approved', 'completed')");
        $paidStmt->bind_param("i", $providerId);
        $paidStmt->execute();
        $totalPaid = $paidStmt->get_result()->fetch_assoc()['total'] ?? 0;
        $paidStmt->close();

        $availableBalance = $totalEarned - $totalPaid;

        if ($amount > $availableBalance) {
            echo json_encode(['success' => false, 'message' => 'পর্যাপ্ত ব্যালেন্স নেই']);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO payout_requests (provider_id, amount, payment_method, account_details) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $providerId, $amount, $paymentMethod, $accountDetails);

        if ($stmt->execute()) {
            createNotification($conn, 'admin', null, null, null, 'নতুন পayout অনুরোধ', "প্রোভাইডার ID {$providerId} একটি payout অনুরোধ করেছে", 'payout');
            createNotification($conn, 'provider', null, $providerId, null, 'Payout অনুরোধ পাঠানো', "আপনার payout অনুরোধ অ্যাডমিনের কাছে পাঠানো হয়েছে", 'info');

            echo json_encode(['success' => true, 'message' => 'Payout অনুরোধ পাঠানো হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'অনুরোধ ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;

    case 'approve_payout':
        requireLogin('admin');
        $requestId = intval($_POST['request_id']);
        $stmt = $conn->prepare("UPDATE payout_requests SET status = 'approved', approved_at = NOW(), approved_by = ? WHERE id = ?");
        $stmt->bind_param("ii", $_SESSION['admin_id'], $requestId);

        if ($stmt->execute()) {
            $requestStmt = $conn->prepare("SELECT provider_id, amount FROM payout_requests WHERE id = ?");
            $requestStmt->bind_param("i", $requestId);
            $requestStmt->execute();
            $request = $requestStmt->get_result()->fetch_assoc();
            $requestStmt->close();

            createNotification($conn, 'provider', null, $request['provider_id'], null, 'Payout অনুমোদিত', "আপনার payout অনুরোধ অনুমোদিত হয়েছে", 'success');

            echo json_encode(['success' => true, 'message' => 'Payout অনুমোদিত হয়েছে']);
        } else {
            echo json_encode(['success' => false, 'message' => 'অনুমোদন ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;

    case 'provider_earnings':
        requireLogin('provider');
        $providerId = $_SESSION['provider_id'];

        // Total earned
        $earnedStmt = $conn->prepare("SELECT SUM(amount) as total FROM payments WHERE provider_id = ? AND status = 'completed'");
        $earnedStmt->bind_param("i", $providerId);
        $earnedStmt->execute();
        $totalEarned = ($earnedStmt->get_result()->fetch_assoc()['total'] ?? 0) * 0.80; // 80% Provider Share
        $earnedStmt->close();

        // Total paid out
        $paidStmt = $conn->prepare("SELECT SUM(amount) as total FROM payout_requests WHERE provider_id = ? AND status IN ('approved', 'completed')");
        $paidStmt->bind_param("i", $providerId);
        $paidStmt->execute();
        $totalPaid = $paidStmt->get_result()->fetch_assoc()['total'] ?? 0;
        $paidStmt->close();

        // Pending payouts
        $pendingStmt = $conn->prepare("SELECT COUNT(*) as total FROM payout_requests WHERE provider_id = ? AND status = 'pending'");
        $pendingStmt->bind_param("i", $providerId);
        $pendingStmt->execute();
        $pendingPayouts = $pendingStmt->get_result()->fetch_assoc()['total'] ?? 0;
        $pendingStmt->close();

        echo json_encode([
            'success' => true,
            'data' => [
                'total_earned' => (float) $totalEarned,
                'total_paid' => (float) $totalPaid,
                'pending_payouts' => (int) $pendingPayouts
            ]
        ]);
        break;
}

$conn->close();
?>