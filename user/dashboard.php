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
    <title>ড্যাশবোর্ড - <?php echo $userName; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap');
        body { font-family: 'Noto Sans Bengali', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-purple-600 to-indigo-700 text-white shadow-xl">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-8"><i class="fas fa-bullhorn mr-2"></i>SME Marketing</h1>
            <nav class="space-y-2">
                <a href="dashboard.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-home mr-3"></i>ড্যাশবোর্ড
                </a>
                <a href="campaigns.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-bullhorn mr-3"></i>আমার ক্যাম্পেইন
                </a>
                <a href="providers.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-users mr-3"></i>আমার প্রোভাইডার
                </a>
                <a href="messages.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-comments mr-3"></i>মেসেজ <span id="msgBadge" class="ml-auto bg-red-500 text-xs px-2 py-1 rounded-full hidden">0</span>
                </a>
                <a href="payments.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-credit-card mr-3"></i>পেমেন্ট
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
    
    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">স্বাগতম, <?php echo $userName; ?>!</h1>
            <p class="text-gray-600 mt-2">আপনার ডিজিটাল মার্কেটিং ড্যাশবোর্ড</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">মোট ক্যাম্পেইন</p>
                        <p class="text-3xl font-bold mt-2" id="totalCampaigns">0</p>
                    </div>
                    <i class="fas fa-bullhorn text-4xl opacity-50"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">সক্রিয় ক্যাম্পেইন</p>
                        <p class="text-3xl font-bold mt-2" id="activeCampaigns">0</p>
                    </div>
                    <i class="fas fa-chart-line text-4xl opacity-50"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">মোট প্রোভাইডার</p>
                        <p class="text-3xl font-bold mt-2" id="totalProviders">0</p>
                    </div>
                    <i class="fas fa-users text-4xl opacity-50"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm">মোট খরচ</p>
                        <p class="text-3xl font-bold mt-2" id="totalSpent">৳0</p>
                    </div>
                    <i class="fas fa-money-bill-wave text-4xl opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Digital Marketing Packages -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">ডিজিটাল মার্কেটিং প্যাকেজ</h2>
                <a href="providers.php" class="text-purple-600 hover:underline text-sm">সব প্রোভাইডার দেখুন</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="border-2 border-purple-100 rounded-xl p-6 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-800">SEO Boost</h3>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">Starting</span>
                    </div>
                    <p class="text-3xl font-bold text-purple-600 mb-2">৳8,000</p>
                    <ul class="text-sm text-gray-600 space-y-1 mb-4">
                        <li>• কিওয়ার্ড রিসার্চ</li>
                        <li>• অন-পেজ অপ্টিমাইজেশন</li>
                        <li>• মাসিক রিপোর্ট</li>
                    </ul>
                    <a href="providers.php?category=SEO&budget=8000" class="block text-center bg-gradient-to-r from-purple-600 to-pink-600 text-white py-2 rounded-lg hover:from-purple-700 hover:to-pink-700 transition">
                        এখনই অর্ডার করুন
                    </a>
                </div>
                <div class="border-2 border-blue-100 rounded-xl p-6 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-800">Facebook Ads</h3>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Popular</span>
                    </div>
                    <p class="text-3xl font-bold text-blue-600 mb-2">৳12,000</p>
                    <ul class="text-sm text-gray-600 space-y-1 mb-4">
                        <li>• ক্রিয়েটিভ ডিজাইন</li>
                        <li>• অডিয়েন্স টার্গেটিং</li>
                        <li>• কনভার্সন ট্র্যাকিং</li>
                    </ul>
                    <a href="providers.php?category=Facebook%20Ads&budget=12000" class="block text-center bg-gradient-to-r from-blue-500 to-cyan-500 text-white py-2 rounded-lg hover:from-blue-600 hover:to-cyan-600 transition">
                        এখনই অর্ডার করুন
                    </a>
                </div>
                <div class="border-2 border-green-100 rounded-xl p-6 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-800">Google Ads</h3>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Performance</span>
                    </div>
                    <p class="text-3xl font-bold text-green-600 mb-2">৳15,000</p>
                    <ul class="text-sm text-gray-600 space-y-1 mb-4">
                        <li>• সার্চ ও ডিসপ্লে ক্যাম্পেইন</li>
                        <li>• বিড অপ্টিমাইজেশন</li>
                        <li>• সাপ্তাহিক রিপোর্ট</li>
                    </ul>
                    <a href="providers.php?category=Google%20Ads&budget=15000" class="block text-center bg-gradient-to-r from-green-500 to-teal-500 text-white py-2 rounded-lg hover:from-green-600 hover:to-teal-600 transition">
                        এখনই অর্ডার করুন
                    </a>
                </div>
            </div>
        </div>

        <!-- Available Providers (top picks) -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">উপলব্ধ প্রোভাইডার</h2>
                <a href="providers.php" class="text-purple-600 hover:underline text-sm">সব প্রোভাইডার</a>
            </div>
            <div id="dashboardProviders" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <p class="text-gray-500 text-center py-8 col-span-full">লোড হচ্ছে...</p>
            </div>
        </div>
        
        <!-- Recent Campaigns -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">সাম্প্রতিক ক্যাম্পেইন</h2>
                <a href="campaigns.php" class="text-purple-600 hover:underline text-sm">সব দেখুন</a>
            </div>
            <div id="recentCampaigns" class="space-y-4">
                <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
            </div>
        </div>
        
        <!-- Recent Notifications -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">সাম্প্রতিক নোটিফিকেশন</h2>
                <a href="notifications.php" class="text-purple-600 hover:underline text-sm">সব দেখুন</a>
            </div>
            <div id="recentNotifications" class="space-y-3">
                <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
            </div>
        </div>
    </div>
    
    <script>
        function showMessage(text, type = 'info') {
            // Simple fallback alert; can be replaced with a toast
            alert(text);
        }

        async function loadDashboard() {
            try {
                // Load campaigns
                const campaignsRes = await fetch('../api/campaigns.php?action=my_campaigns');
                const campaignsData = await campaignsRes.json();
                const campaigns = campaignsData.success ? (campaignsData.data || []) : [];

                document.getElementById('totalCampaigns').textContent = campaigns.length;
                document.getElementById('activeCampaigns').textContent = campaigns.filter(c => c.status === 'active').length;

                const recentDiv = document.getElementById('recentCampaigns');
                if (campaigns.length > 0) {
                    recentDiv.innerHTML = campaigns.slice(0, 5).map(c => `
                        <div class="border-l-4 ${c.status === 'active' ? 'border-green-500' : c.status === 'completed' ? 'border-blue-500' : 'border-gray-300'} bg-gray-50 p-4 rounded-lg hover:shadow-md transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-800">${c.title || 'N/A'}</h3>
                                    <p class="text-sm text-gray-600">${c.provider_name || 'N/A'} • ${c.platform || 'N/A'}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold ${c.status === 'active' ? 'bg-green-100 text-green-800' : c.status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}">${c.status || 'pending'}</span>
                            </div>
                        </div>
                    `).join('');
                } else {
                    recentDiv.innerHTML = '<div class="text-center py-8"><i class="fas fa-bullhorn text-gray-400 text-4xl mb-2"></i><p class="text-gray-500">কোন ক্যাম্পেইন নেই</p></div>';
                }
            } catch (error) {
                console.error('Error loading campaigns:', error);
                document.getElementById('recentCampaigns').innerHTML = '<p class="text-red-500 text-center py-8">ডাটা লোড করতে সমস্যা হয়েছে</p>';
            }
            
            try {
                // Load providers
                const providersRes = await fetch('../api/providers.php?action=my_providers');
                const providersData = await providersRes.json();
                if (providersData.success) {
                    document.getElementById('totalProviders').textContent = providersData.data.length;
                }
            } catch (error) {
                console.error('Error loading providers:', error);
            }
            
            try {
                // Load payments
                const paymentsRes = await fetch('../api/payments.php?action=my_payments');
                const paymentsData = await paymentsRes.json();
                if (paymentsData.success) {
                    const total = paymentsData.data.reduce((sum, p) => sum + parseFloat(p.amount || 0), 0);
                    document.getElementById('totalSpent').textContent = '৳' + total.toLocaleString('bn-BD');
                }
            } catch (error) {
                console.error('Error loading payments:', error);
            }
            
            try {
                // Load notifications
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
                            <div class="flex items-start p-3 ${!n.is_read ? 'bg-blue-50' : 'bg-gray-50'} rounded-lg hover:shadow-sm transition">
                                <i class="fas fa-bell text-purple-500 mt-1 mr-3"></i>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">${n.title || 'নোটিফিকেশন'}</p>
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
                document.getElementById('recentNotifications').innerHTML = '<p class="text-red-500 text-center py-8">একটি ত্রুটি হয়েছে</p>';
            }
        }
        
        async function logout() {
            if (!confirm('আপনি লগআউট করতে চান?')) return;
            const res = await fetch('../api/auth.php?action=logout', { method: 'POST' });
            const data = await res.json();
            if (data.success) {
                window.location.href = data.redirect;
            }
        }
        
        loadDashboard();
        // Load providers (top picks)
        async function loadDashboardProviders() {
            const container = document.getElementById('dashboardProviders');
            try {
                const res = await fetch('../api/providers.php?action=search', { cache: 'no-store' });
                if (!res.ok) throw new Error('network');
                
                let data;
                try {
                    data = await res.json();
                } catch (parseErr) {
                    throw new Error('parse');
                }

                if (!data.success) {
                    container.innerHTML = '<p class="text-red-500 text-center py-8 col-span-full">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                    return;
                }

                const providers = (data.data || []).slice(0, 6);
                if (providers.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center py-8 col-span-full">কোন প্রোভাইডার পাওয়া যায়নি</p>';
                    return;
                }

                container.innerHTML = providers.map(p => `
                    <div class="border-2 border-gray-100 rounded-xl p-5 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">${p.name}</h3>
                                <p class="text-sm text-gray-600">${p.company_name || ''}</p>
                            </div>
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">${p.category}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700 mb-2">
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold"><i class="fas fa-star mr-1 text-yellow-500"></i>${p.rating} (${p.total_reviews})</span>
                            <span><i class="fas fa-briefcase mr-1 text-blue-500"></i>${p.experience} বছর</span>
                        </div>
                        <p class="text-sm text-gray-700 mb-4"><i class="fas fa-money-bill-wave mr-1 text-green-500"></i>৳${p.budget_min} - ৳${p.budget_max}</p>
                        <a href="providers.php?category=${encodeURIComponent(p.category)}&budget=${encodeURIComponent(p.budget_min)}" class="block text-center bg-gradient-to-r from-purple-600 to-pink-600 text-white py-2 rounded-lg hover:from-purple-700 hover:to-pink-700 transition">
                            এই প্রোভাইডার দেখুন
                        </a>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Error loading providers:', error);
                container.innerHTML = '<p class="text-red-500 text-center py-8 col-span-full">একটি ত্রুটি হয়েছে (নেটওয়ার্ক/সেশন)</p>';
            }
        }

        loadDashboardProviders();
        setInterval(loadDashboard, 30000); // Refresh campaigns/stats every 30s
        setInterval(loadDashboardProviders, 60000); // Refresh providers every 60s
    </script>
</body>
</html>

