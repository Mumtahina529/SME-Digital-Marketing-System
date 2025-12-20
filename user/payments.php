<?php
require_once '../config/config.php';
requireLogin('user');
$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>পেমেন্ট - <?php echo $userName; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap');
        body { font-family: 'Noto Sans Bengali', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-purple-600 to-indigo-700 text-white shadow-xl">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-8"><i class="fas fa-bullhorn mr-2"></i>SME Marketing</h1>
            <nav class="space-y-2">
                <a href="dashboard.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-home mr-3"></i>ড্যাশবোর্ড
                </a>
                <a href="campaigns.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-bullhorn mr-3"></i>আমার ক্যাম্পেইন
                </a>
                <a href="providers.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-users mr-3"></i>আমার প্রোভাইডার
                </a>
                <a href="messages.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-comments mr-3"></i>মেসেজ
                </a>
                <a href="payments.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-credit-card mr-3"></i>পেমেন্ট
                </a>
                <a href="notifications.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-bell mr-3"></i>নোটিফিকেশন
                </a>
                <a href="support.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-headset mr-3"></i>সাপোর্ট
                </a>
                <a href="#" onclick="logout()" class="flex items-center px-4 py-3 hover:bg-red-500 rounded-lg transition mt-4">
                    <i class="fas fa-sign-out-alt mr-3"></i>লগআউট
                </a>
            </nav>
        </div>
    </div>
    
    <div class="ml-64 p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">পেমেন্ট হিস্ট্রি</h1>
        <div id="paymentsList" class="space-y-4">
            <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
        </div>
    </div>
    
    <script>
        async function loadPayments() {
            try {
                const res = await fetch('../api/payments.php?action=my_payments', { cache: 'no-store' });
                if (!res.ok) throw new Error('network');
                const data = await res.json();
                
                const div = document.getElementById('paymentsList');
                if (!data.success) {
                    div.innerHTML = '<p class="text-red-500 text-center py-8">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                    return;
                }
                
                if (data.data.length > 0) {
                    div.innerHTML = data.data.map(p => `
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">${p.campaign_title}</h3>
                                    <p class="text-gray-600">${p.provider_name}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-green-600">৳${parseFloat(p.amount).toLocaleString('bn-BD')}</p>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${p.status === 'completed' ? 'bg-green-100 text-green-800' : p.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">${p.status}</span>
                                </div>
                            </div>
                            <div class="mt-4 grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600">পেমেন্ট মেথড</p>
                                    <p class="font-semibold">${p.payment_method}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">ট্রানজেকশন ID</p>
                                    <p class="font-semibold">${p.transaction_id || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">তারিখ</p>
                                    <p class="font-semibold">${new Date(p.created_at).toLocaleDateString('bn-BD')}</p>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    div.innerHTML = '<p class="text-gray-500 text-center py-8">কোন পেমেন্ট নেই</p>';
                }
            } catch (error) {
                console.error('Error loading payments:', error);
                document.getElementById('paymentsList').innerHTML = '<p class="text-red-500 text-center py-8">একটি ত্রুটি হয়েছে (নেটওয়ার্ক/সেশন)</p>';
            }
        }
        
        async function logout() {
            const res = await fetch('../api/auth.php?action=logout', { method: 'POST' });
            const data = await res.json();
            if (data.success) window.location.href = data.redirect;
        }
        
        loadPayments();
    </script>
</body>
</html>

