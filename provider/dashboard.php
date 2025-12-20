<?php
require_once '../config/config.php';
requireLogin('provider');
$providerId = $_SESSION['provider_id'];
$providerName = $_SESSION['provider_name'];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>প্রোভাইডার ড্যাশবোর্ড - <?php echo $providerName; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap');
        body { font-family: 'Noto Sans Bengali', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-blue-600 to-cyan-700 text-white shadow-xl">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-8"><i class="fas fa-briefcase mr-2"></i>Provider</h1>
            <nav class="space-y-2">
                <a href="dashboard.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-home mr-3"></i>ড্যাশবোর্ড
                </a>
                <a href="campaigns.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-bullhorn mr-3"></i>ক্যাম্পেইন
                </a>
                <a href="messages.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-comments mr-3"></i>মেসেজ
                </a>
                <a href="payouts.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-money-bill-wave mr-3"></i>Payout
                </a>
                <a href="notifications.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-bell mr-3"></i>নোটিফিকেশন <span id="notifBadge" class="ml-auto bg-red-500 text-xs px-2 py-1 rounded-full hidden">0</span>
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
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">স্বাগতম, <?php echo $providerName; ?>!</h1>
            <p class="text-gray-600 mt-2">আপনার প্রোভাইডার ড্যাশবোর্ড</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">মোট ক্যাম্পেইন</p>
                        <p class="text-3xl font-bold mt-2" id="totalCampaigns">0</p>
                    </div>
                    <i class="fas fa-bullhorn text-4xl opacity-50"></i>
                </div>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">সক্রিয় ক্যাম্পেইন</p>
                        <p class="text-3xl font-bold mt-2" id="activeCampaigns">0</p>
                    </div>
                    <i class="fas fa-chart-line text-4xl opacity-50"></i>
                </div>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">মোট আয়</p>
                        <p class="text-3xl font-bold mt-2" id="totalEarnings">৳0</p>
                    </div>
                    <i class="fas fa-money-bill-wave text-4xl opacity-50"></i>
                </div>
            </div>
            <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">রেটিং</p>
                        <p class="text-3xl font-bold mt-2" id="rating">0.0</p>
                    </div>
                    <i class="fas fa-star text-4xl opacity-50"></i>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Recent Campaigns -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">সাম্প্রতিক ক্যাম্পেইন</h2>
                    <a href="campaigns.php" class="text-blue-600 hover:underline text-sm">সব দেখুন</a>
                </div>
                <div id="recentCampaigns" class="space-y-4">
                    <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
                </div>
            </div>
            
            <!-- Recent Notifications -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">সাম্প্রতিক নোটিফিকেশন</h2>
                    <a href="notifications.php" class="text-blue-600 hover:underline text-sm">সব দেখুন</a>
                </div>
                <div id="recentNotifications" class="space-y-3">
                    <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
                </div>
            </div>
        </div>
        
        <!-- Additional Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">মোট ক্লায়েন্ট</p>
                        <p class="text-2xl font-bold text-gray-800 mt-2" id="totalClients">0</p>
                    </div>
                    <i class="fas fa-users text-3xl text-blue-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Available Balance</p>
                        <p class="text-2xl font-bold text-green-600 mt-2" id="availableBalance">৳0</p>
                    </div>
                    <i class="fas fa-wallet text-3xl text-green-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pending Payouts</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-2" id="pendingPayouts">0</p>
                    </div>
                    <i class="fas fa-clock text-3xl text-yellow-500"></i>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        async function loadDashboard() {
            try {
                // Load Campaigns
                const campaignsRes = await fetch('../api/campaigns.php?action=provider_campaigns');
                const campaignsData = await campaignsRes.json();
                
                if (campaignsData.success) {
                    const campaigns = campaignsData.data || [];
                    document.getElementById('totalCampaigns').textContent = campaigns.length;
                    document.getElementById('activeCampaigns').textContent = campaigns.filter(c => c.status === 'active').length;
                    
                    const div = document.getElementById('recentCampaigns');
                    if (campaigns.length > 0) {
                        div.innerHTML = campaigns.slice(0, 5).map(c => `
                            <div class="border-l-4 ${c.status === 'active' ? 'border-green-500' : c.status === 'completed' ? 'border-blue-500' : 'border-gray-300'} bg-gray-50 p-4 rounded-lg hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">${c.title || 'N/A'}</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-user mr-1 text-purple-500"></i>${c.user_name || 'Unknown'} 
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-bullhorn mr-1 text-blue-500"></i>${c.platform || 'N/A'}
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-money-bill-wave mr-1 text-green-500"></i>৳${parseFloat(c.budget || 0).toLocaleString('bn-BD')}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${c.status === 'active' ? 'bg-green-100 text-green-800' : c.status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}">
                                        ${c.status || 'pending'}
                                    </span>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        div.innerHTML = '<div class="text-center py-8"><i class="fas fa-bullhorn text-gray-400 text-4xl mb-2"></i><p class="text-gray-500">কোন ক্যাম্পেইন নেই</p></div>';
                    }
                }
            } catch (error) {
                console.error('Error loading campaigns:', error);
            }
            
            try {
                // Load Earnings
                const paymentsRes = await fetch('../api/payments.php?action=provider_earnings');
                const paymentsData = await paymentsRes.json();
                
                if (paymentsData.success) {
                    const totalEarned = paymentsData.data.total_earned || 0;
                    const totalPaid = paymentsData.data.total_paid || 0;
                    const availableBalance = totalEarned - totalPaid;
                    
                    document.getElementById('totalEarnings').textContent = '৳' + parseFloat(totalEarned).toLocaleString('bn-BD');
                    document.getElementById('availableBalance').textContent = '৳' + parseFloat(availableBalance).toLocaleString('bn-BD');
                    document.getElementById('pendingPayouts').textContent = paymentsData.data.pending_payouts || 0;
                }
            } catch (error) {
                console.error('Error loading earnings:', error);
            }
            
            try {
                // Load Provider Info (Rating and Clients)
                const statsRes = await fetch('../api/providers.php?action=provider_stats');
                const statsData = await statsRes.json();
                
                if (statsData.success) {
                    document.getElementById('rating').textContent = parseFloat(statsData.data.rating || 0).toFixed(1);
                    document.getElementById('totalClients').textContent = statsData.data.total_clients || 0;
                }
            } catch (error) {
                console.error('Error loading provider stats:', error);
                // Fallback: Load from campaigns data
                try {
                    const campaignsRes = await fetch('../api/campaigns.php?action=provider_campaigns');
                    const campaignsData = await campaignsRes.json();
                    
                    if (campaignsData.success) {
                        const campaigns = campaignsData.data || [];
                        const uniqueClients = new Set(campaigns.map(c => c.user_id)).size;
                        document.getElementById('totalClients').textContent = uniqueClients;
                    }
                } catch (e) {
                    console.error('Error loading clients from campaigns:', e);
                }
            }
            
            try {
                // Load Notifications
                const notifRes = await fetch('../api/notifications.php?action=get');
                const notifData = await notifRes.json();
                
                if (notifData.success) {
                    const notifications = notifData.data || [];
                    const unread = notifications.filter(n => !n.is_read).length;
                    
                    if (unread > 0) {
                        document.getElementById('notifBadge').textContent = unread;
                        document.getElementById('notifBadge').classList.remove('hidden');
                    }
                    
                    const notifDiv = document.getElementById('recentNotifications');
                    if (notifications.length > 0) {
                        notifDiv.innerHTML = notifications.slice(0, 5).map(n => `
                            <div class="flex items-start p-3 ${!n.is_read ? 'bg-blue-50' : 'bg-gray-50'} rounded-lg">
                                <i class="fas fa-bell text-blue-500 mt-1 mr-3"></i>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">${n.title || 'Notification'}</p>
                                    <p class="text-sm text-gray-600">${n.message || ''}</p>
                                    <p class="text-xs text-gray-500 mt-1">${new Date(n.created_at).toLocaleString('bn-BD')}</p>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        notifDiv.innerHTML = '<div class="text-center py-8"><i class="fas fa-bell text-gray-400 text-4xl mb-2"></i><p class="text-gray-500">কোন নোটিফিকেশন নেই</p></div>';
                    }
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }
        
        async function logout() {
            if (!confirm('আপনি লগআউট করতে চান?')) return;
            
            const res = await fetch('../api/auth.php?action=logout', { method: 'POST' });
            const data = await res.json();
            if (data.success) window.location.href = data.redirect;
        }
        
        // Load dashboard on page load
        loadDashboard();
        
        // Auto-refresh every 30 seconds
        setInterval(loadDashboard, 30000);
    </script>
</body>
</html>

