<?php
require_once '../config/config.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

$conn = getDBConnection();

switch ($action) {
    case 'register_user':
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $phone = sanitizeInput($_POST['phone']);
        $password = $_POST['password'];
        $company_name = sanitizeInput($_POST['company_name'] ?? '');
        
        // Check if email exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'ইমেইল ইতিমধ্যে ব্যবহার করা হয়েছে']);
            exit();
        }
        
        $hashedPassword = hashPassword($password);
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, company_name) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $hashedPassword, $company_name);
        
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            echo json_encode(['success' => true, 'message' => 'নিবন্ধন সফল হয়েছে', 'redirect' => 'user/dashboard.php']);
        } else {
            echo json_encode(['success' => false, 'message' => 'নিবন্ধন ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;
        
    case 'register_provider':
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $phone = sanitizeInput($_POST['phone']);
        $password = $_POST['password'];
        $company_name = sanitizeInput($_POST['company_name'] ?? '');
        $category = $_POST['category'];
        $experience = intval($_POST['experience']);
        $budget_min = floatval($_POST['budget_min']);
        $budget_max = floatval($_POST['budget_max']);
        $bio = sanitizeInput($_POST['bio'] ?? '');
        
        $check = $conn->prepare("SELECT id FROM providers WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'ইমেইল ইতিমধ্যে ব্যবহার করা হয়েছে']);
            exit();
        }
        
        $hashedPassword = hashPassword($password);
        $stmt = $conn->prepare("INSERT INTO providers (name, email, phone, password, company_name, category, experience, budget_min, budget_max, bio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssidds", $name, $email, $phone, $hashedPassword, $company_name, $category, $experience, $budget_min, $budget_max, $bio);
        
        if ($stmt->execute()) {
            $providerId = $conn->insert_id;
            createNotification($conn, 'admin', null, null, null, 'নতুন প্রোভাইডার নিবন্ধন', "{$name} একটি নতুন প্রোভাইডার হিসাবে নিবন্ধন করেছেন", 'provider_registration');
            echo json_encode(['success' => true, 'message' => 'নিবন্ধন সফল হয়েছে। অ্যাডমিন অনুমোদনের জন্য অপেক্ষা করুন।']);
        } else {
            echo json_encode(['success' => false, 'message' => 'নিবন্ধন ব্যর্থ হয়েছে']);
        }
        $stmt->close();
        break;
        
    case 'login':
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];
        $userType = $_POST['user_type'];
        
        if ($userType === 'user') {
            $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (verifyPassword($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    echo json_encode(['success' => true, 'message' => 'লগইন সফল', 'redirect' => 'user/dashboard.php']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'ভুল পাসওয়ার্ড']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'ইমেইল পাওয়া যায়নি']);
            }
            $stmt->close();
        } elseif ($userType === 'provider') {
            $stmt = $conn->prepare("SELECT id, name, email, password, status FROM providers WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $provider = $result->fetch_assoc();
                if (verifyPassword($password, $provider['password'])) {
                    if ($provider['status'] === 'pending') {
                        echo json_encode(['success' => false, 'message' => 'আপনার অ্যাকাউন্ট এখনও অনুমোদিত হয়নি']);
                    } else {
                        $_SESSION['provider_id'] = $provider['id'];
                        $_SESSION['provider_name'] = $provider['name'];
                        $_SESSION['provider_email'] = $provider['email'];
                        echo json_encode(['success' => true, 'message' => 'লগইন সফল', 'redirect' => 'provider/dashboard.php']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'ভুল পাসওয়ার্ড']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'ইমেইল পাওয়া যায়নি']);
            }
            $stmt->close();
        } elseif ($userType === 'admin') {
            $stmt = $conn->prepare("SELECT id, name, email, password FROM admins WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $admin = $result->fetch_assoc();
                if (verifyPassword($password, $admin['password'])) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_name'] = $admin['name'];
                    $_SESSION['admin_email'] = $admin['email'];
                    echo json_encode(['success' => true, 'message' => 'লগইন সফল', 'redirect' => 'admin/dashboard.php']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'ভুল পাসওয়ার্ড']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'ইমেইল পাওয়া যায়নি']);
            }
            $stmt->close();
        }
        break;
        
    case 'logout':
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'লগআউট সফল', 'redirect' => '../index.php']);
        break;
}

$conn->close();
?>

