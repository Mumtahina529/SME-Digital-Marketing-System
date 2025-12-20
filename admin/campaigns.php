<?php
require_once '../config/config.php';
requireLogin('admin');
$adminId = $_SESSION['admin_id'];
$adminName = $_SESSION['admin_name'];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ক্যাম্পেইন ব্যবস্থাপনা - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap');
        body { font-family: 'Noto Sans Bengali', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-red-600 to-pink-700 text-white shadow-xl">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-8"><i class="fas fa-user-shield mr-2"></i>Admin</h1>
            <nav class="space-y-2">
                <a href="dashboard.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-home mr-3"></i>ড্যাশবোর্ড
                </a>
                <a href="approvals.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-check-circle mr-3"></i>অনুমোদন
                </a>
                <a href="campaigns.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-bullhorn mr-3"></i>ক্যাম্পেইন
                </a>
                <a href="users.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-users mr-3"></i>ইউজার
                </a>
                <a href="providers.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-briefcase mr-3"></i>প্রোভাইডার
                </a>
                <a href="payouts.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-money-bill-wave mr-3"></i>Payouts
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
            <h1 class="text-3xl font-bold text-gray-800">ক্যাম্পেইন ব্যবস্থাপনা</h1>
            <p class="text-gray-600 mt-2">সব ক্যাম্পেইন দেখুন এবং মনিটর করুন</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">মোট ক্যাম্পেইন</p>
                <p class="text-3xl font-bold mt-2" id="totalCampaigns">0</p>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">সক্রিয়</p>
                <p class="text-3xl font-bold mt-2" id="activeCampaigns">0</p>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">Pending</p>
                <p class="text-3xl font-bold mt-2" id="pendingCampaigns">0</p>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">সম্পন্ন</p>
                <p class="text-3xl font-bold mt-2" id="completedCampaigns">0</p>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">ফিল্টার</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                    <select id="filterStatus" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <option value="">সব</option>
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">প্ল্যাটফর্ম</label>
                    <select id="filterPlatform" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <option value="">সব</option>
                        <option value="Facebook">Facebook</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Google Ads">Google Ads</option>
                        <option value="LinkedIn">LinkedIn</option>
                        <option value="Twitter">Twitter</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">তারিখ থেকে</label>
                    <input type="date" id="filterDateFrom" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">তারিখ পর্যন্ত</label>
                    <input type="date" id="filterDateTo" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                </div>
            </div>
            <button onclick="loadCampaigns()" class="mt-4 w-full md:w-auto bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition">
                <i class="fas fa-filter mr-2"></i>ফিল্টার করুন
            </button>
        </div>
        
        <!-- Campaigns List -->
        <div id="campaignsList" class="space-y-6">
            <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
        </div>
    </div>
    
    <!-- Message Toast -->
    <div id="messageToast" class="fixed top-4 right-4 hidden p-4 rounded-lg shadow-lg z-50 max-w-sm"></div>
    
    <script>
        async function loadCampaigns() {
            try {
                const res = await fetch('../api/campaigns.php?action=all_campaigns');
                const data = await res.json();
                
                if (data.success) {
                    let campaigns = data.data || [];
                    
                    // Update stats
                    document.getElementById('totalCampaigns').textContent = campaigns.length;
                    document.getElementById('activeCampaigns').textContent = campaigns.filter(c => c.status === 'active').length;
                    document.getElementById('pendingCampaigns').textContent = campaigns.filter(c => c.status === 'pending').length;
                    document.getElementById('completedCampaigns').textContent = campaigns.filter(c => c.status === 'completed').length;
                    
                    // Apply filters
                    const status = document.getElementById('filterStatus').value;
                    const platform = document.getElementById('filterPlatform').value;
                    const dateFrom = document.getElementById('filterDateFrom').value;
                    const dateTo = document.getElementById('filterDateTo').value;
                    
                    if (status) {
                        campaigns = campaigns.filter(c => c.status === status);
                    }
                    if (platform) {
                        campaigns = campaigns.filter(c => c.platform === platform);
                    }
                    if (dateFrom) {
                        campaigns = campaigns.filter(c => c.start_date >= dateFrom);
                    }
                    if (dateTo) {
                        campaigns = campaigns.filter(c => c.end_date <= dateTo);
                    }
                    
                    // Load campaign updates for each campaign
                    const campaignsWithUpdates = await Promise.all(campaigns.map(async (c) => {
                        try {
                            const updateRes = await fetch(`../api/campaigns.php?action=campaign_updates&campaign_id=${c.id}`);
                            const updateData = await updateRes.json();
                            if (updateData.success && updateData.data) {
                                c.latest_update = updateData.data;
                            }
                        } catch (e) {
                            console.error('Error loading update for campaign', c.id, e);
                        }
                        return c;
                    }));
                    
                    const div = document.getElementById('campaignsList');
                    if (campaignsWithUpdates.length > 0) {
                        div.innerHTML = campaignsWithUpdates.map(c => {
                            const statusColors = {
                                'pending': 'bg-yellow-100 text-yellow-800',
                                'active': 'bg-green-100 text-green-800',
                                'completed': 'bg-blue-100 text-blue-800',
                                'cancelled': 'bg-red-100 text-red-800'
                            };
                            
                            const statusLabels = {
                                'pending': 'Pending',
                                'active': 'Active',
                                'completed': 'Completed',
                                'cancelled': 'Cancelled'
                            };
                            
                            const update = c.latest_update || {};
                            
                            return `
                                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-2xl font-bold text-gray-800">${c.title || 'N/A'}</h3>
                                            <div class="flex items-center gap-4 mt-2">
                                                <p class="text-gray-600">
                                                    <i class="fas fa-user mr-1 text-purple-500"></i>${c.user_name || 'Unknown'}
                                                </p>
                                                <p class="text-gray-600">
                                                    <i class="fas fa-briefcase mr-1 text-blue-500"></i>${c.provider_name || 'Unknown'}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="px-4 py-2 rounded-full text-sm font-semibold ${statusColors[c.status] || 'bg-gray-100 text-gray-800'}">
                                            ${statusLabels[c.status] || c.status}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs text-gray-600">প্ল্যাটফর্ম</p>
                                            <p class="font-semibold text-gray-800">${c.platform || 'N/A'}</p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs text-gray-600">বাজেট</p>
                                            <p class="font-semibold text-green-600">৳${parseFloat(c.budget || 0).toLocaleString('bn-BD')}</p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs text-gray-600">শুরু তারিখ</p>
                                            <p class="font-semibold text-gray-800">${c.start_date || 'N/A'}</p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs text-gray-600">শেষ তারিখ</p>
                                            <p class="font-semibold text-gray-800">${c.end_date || 'N/A'}</p>
                                        </div>
                                    </div>
                                    
                                    ${update.work_started ? `
                                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg p-4 mb-4 border-l-4 border-blue-500">
                                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                                <i class="fas fa-chart-line mr-2 text-blue-500"></i>কাজের আপডেট
                                            </h4>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                <div>
                                                    <p class="text-gray-600">Spend</p>
                                                    <p class="font-bold text-green-600">৳${parseFloat(update.spend_report || 0).toLocaleString('bn-BD')}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-600">Leads</p>
                                                    <p class="font-bold text-purple-600">${update.leads_generated || 0}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-600">Reach</p>
                                                    <p class="font-bold text-blue-600">${update.engagement_reach || 0}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-600">Updated</p>
                                                    <p class="font-semibold text-gray-800">${new Date(update.updated_at || c.created_at).toLocaleDateString('bn-BD')}</p>
                                                </div>
                                            </div>
                                            ${update.ad_results ? `
                                                <div class="mt-3 pt-3 border-t">
                                                    <p class="text-sm text-gray-600"><strong>Ad Results:</strong></p>
                                                    <p class="text-gray-800">${update.ad_results}</p>
                                                </div>
                                            ` : ''}
                                            ${update.notes ? `
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-600"><strong>Notes:</strong></p>
                                                    <p class="text-gray-800">${update.notes}</p>
                                                </div>
                                            ` : ''}
                                        </div>
                                    ` : c.status === 'active' ? `
                                        <div class="bg-yellow-50 rounded-lg p-3 mb-4 border-l-4 border-yellow-500">
                                            <p class="text-sm text-yellow-800">
                                                <i class="fas fa-info-circle mr-2"></i>কাজ এখনও শুরু হয়নি
                                            </p>
                                        </div>
                                    ` : ''}
                                    
                                    ${c.target_audience ? `
                                        <div class="bg-blue-50 rounded-lg p-3 mb-4">
                                            <p class="text-sm text-gray-600"><strong>টার্গেট অডিয়েন্স:</strong></p>
                                            <p class="text-gray-800">${c.target_audience}</p>
                                        </div>
                                    ` : ''}
                                    
                                    ${c.logo_file ? `
                                        <div class="mb-4">
                                            <img src="../uploads/campaigns/${c.logo_file}" alt="Logo" class="h-20 w-20 object-cover rounded-lg shadow">
                                        </div>
                                    ` : ''}
                                    
                                    <div class="flex items-center justify-between pt-4 border-t">
                                        <p class="text-xs text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>তৈরি হয়েছে: ${new Date(c.created_at).toLocaleString('bn-BD')}
                                        </p>
                                        <div class="flex gap-2">
                                            <button onclick="updateCampaignStatus(${c.id}, 'active')" ${c.status === 'active' ? 'disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed text-sm"' : 'class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition text-sm font-semibold"'} title="Active করুন">
                                                <i class="fas fa-play mr-1"></i>Active
                                            </button>
                                            <button onclick="updateCampaignStatus(${c.id}, 'completed')" ${c.status === 'completed' ? 'disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed text-sm"' : 'class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition text-sm font-semibold"'} title="সম্পন্ন করুন">
                                                <i class="fas fa-check mr-1"></i>Complete
                                            </button>
                                            <button onclick="updateCampaignStatus(${c.id}, 'cancelled')" ${c.status === 'cancelled' ? 'disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed text-sm"' : 'class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition text-sm font-semibold"'} title="বাতিল করুন">
                                                <i class="fas fa-times mr-1"></i>Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        div.innerHTML = '<div class="text-center py-12"><i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i><p class="text-gray-500 text-lg">কোন ক্যাম্পেইন পাওয়া যায়নি</p></div>';
                    }
                } else {
                    document.getElementById('campaignsList').innerHTML = '<div class="text-center py-8"><p class="text-red-500">ডাটা লোড করতে সমস্যা হয়েছে</p></div>';
                }
            } catch (error) {
                console.error('Error loading campaigns:', error);
                document.getElementById('campaignsList').innerHTML = '<p class="text-red-500 text-center py-8">একটি ত্রুটি হয়েছে</p>';
            }
        }
        
        async function updateCampaignStatus(campaignId, status) {
            const statusLabels = {
                'active': 'Active',
                'completed': 'Completed',
                'cancelled': 'Cancelled'
            };
            
            if (!confirm(`আপনি এই ক্যাম্পেইনের স্ট্যাটাস "${statusLabels[status]}" এ পরিবর্তন করতে চান?`)) return;
            
            const formData = new FormData();
            formData.append('action', 'update_status');
            formData.append('campaign_id', campaignId);
            formData.append('status', status);
            
            try {
                const res = await fetch('../api/campaigns.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    showMessage(data.message, 'success');
                    loadCampaigns();
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি হয়েছে', 'error');
                console.error('Error:', error);
            }
        }
        
        function showMessage(text, type = 'success') {
            const toast = document.getElementById('messageToast');
            const colors = {
                'success': 'bg-green-500',
                'error': 'bg-red-500',
                'info': 'bg-blue-500'
            };
            toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm ${colors[type] || colors.success} text-white font-semibold`;
            toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>${text}`;
            toast.classList.remove('hidden');
            
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }
        
        async function logout() {
            if (!confirm('আপনি লগআউট করতে চান?')) return;
            
            const res = await fetch('../api/auth.php?action=logout', { method: 'POST' });
            const data = await res.json();
            if (data.success) window.location.href = data.redirect;
        }
        
        // Load campaigns on page load
        loadCampaigns();
        
        // Auto-refresh every 60 seconds
        setInterval(loadCampaigns, 60000);
    </script>
</body>
</html>

