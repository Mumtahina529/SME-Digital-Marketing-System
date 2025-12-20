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
    <title>নোটিফিকেশন - <?php echo $userName; ?></title>
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
                <a href="payments.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-credit-card mr-3"></i>পেমেন্ট
                </a>
                <a href="notifications.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
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
        <h1 class="text-3xl font-bold text-gray-800 mb-8">নোটিফিকেশন</h1>
        <div id="notificationsList" class="space-y-4">
            <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
        </div>
    </div>
    
    <script>
        async function loadNotifications() {
            try {
                const res = await fetch('../api/notifications.php?action=get', { cache: 'no-store' });
                if (!res.ok) throw new Error('network');
                const data = await res.json();
                
                const div = document.getElementById('notificationsList');
                if (!data.success) {
                    div.innerHTML = '<p class="text-red-500 text-center py-8">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                    return;
                }
                
                if (data.data.length > 0) {
                    div.innerHTML = data.data.map(n => `
                        <div class="bg-white rounded-xl shadow-lg p-6 ${!n.is_read ? 'border-l-4 border-blue-500' : ''}">
                            <div class="flex items-start">
                                <i class="fas fa-bell text-purple-500 text-2xl mr-4 mt-1"></i>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-800">${n.title}</h3>
                                    <p class="text-gray-600 mt-2">${n.message}</p>
                                    <p class="text-sm text-gray-500 mt-2">${new Date(n.created_at).toLocaleString('bn-BD')}</p>
                                </div>
                                ${!n.is_read ? '<button onclick="markRead(' + n.id + ')" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">পড়া হয়েছে</button>' : ''}
                            </div>
                        </div>
                    `).join('');
                } else {
                    div.innerHTML = '<p class="text-gray-500 text-center py-8">কোন নোটিফিকেশন নেই</p>';
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
                document.getElementById('notificationsList').innerHTML = '<p class="text-red-500 text-center py-8">একটি ত্রুটি হয়েছে (নেটওয়ার্ক/সেশন)</p>';
            }
        }
        
        async function markRead(notificationId) {
            const formData = new FormData();
            formData.append('action', 'mark_read');
            formData.append('notification_id', notificationId);
            
            await fetch('api/notifications.php', {
                method: 'POST',
                body: formData
            });
            
            loadNotifications();
        }
        
        async function logout() {
            const res = await fetch('../api/auth.php?action=logout', { method: 'POST' });
            const data = await res.json();
            if (data.success) window.location.href = data.redirect;
        }
        
        loadNotifications();
        setInterval(loadNotifications, 10000);
    </script>
</body>
</html>

