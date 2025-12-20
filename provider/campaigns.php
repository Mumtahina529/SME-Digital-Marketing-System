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
    <title>ক্যাম্পেইন - <?php echo $providerName; ?></title>
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
                <a href="dashboard.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-home mr-3"></i>ড্যাশবোর্ড
                </a>
                <a href="campaigns.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-bullhorn mr-3"></i>ক্যাম্পেইন
                </a>
                <a href="messages.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-comments mr-3"></i>মেসেজ
                </a>
                <a href="payouts.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-money-bill-wave mr-3"></i>Payout
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
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">আমার ক্যাম্পেইন</h1>
            <p class="text-gray-600 mt-2">আপনার চলমান ও সম্পন্ন ক্যাম্পেইন দেখুন</p>
        </div>
        <div id="campaignsList" class="space-y-6">
            <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
        </div>
    </div>
    
    <!-- Update Modal -->
    <div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 max-w-3xl w-full mx-4 max-h-screen overflow-y-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">ক্যাম্পেইন আপডেট</h2>
            <form id="updateForm" class="space-y-4">
                <input type="hidden" id="updateCampaignId">
                
                <div class="flex items-center gap-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="workStarted" class="mr-2">
                        <span>কাজ শুরু হয়েছে</span>
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ad Results</label>
                    <textarea id="adResults" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Spend Report (৳)</label>
                        <input type="number" id="spendReport" min="0" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Leads Generated</label>
                        <input type="number" id="leadsGenerated" min="0" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Engagement/Reach</label>
                    <input type="number" id="engagementReach" min="0" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Daily Analytics</label>
                    <textarea id="dailyAnalytics" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg"></textarea>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-green-500 to-teal-500 text-white py-3 rounded-lg font-semibold">আপডেট করুন</button>
                    <button type="button" onclick="hideUpdateModal()" class="flex-1 bg-gray-500 text-white py-3 rounded-lg font-semibold">বাতিল</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function showMessage(text, type = 'success') {
            alert(text); // simple fallback; can be replaced with toast if desired
        }

        async function loadCampaigns() {
            try {
                const res = await fetch('../api/campaigns.php?action=provider_campaigns');
                const data = await res.json();
                
                const div = document.getElementById('campaignsList');
                if (!data.success) {
                    div.innerHTML = '<p class="text-red-500 text-center py-8">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                    return;
                }

                const campaigns = data.data || [];
                if (campaigns.length === 0) {
                    div.innerHTML = '<div class="text-center py-12"><i class="fas fa-bullhorn text-gray-400 text-4xl mb-2"></i><p class="text-gray-500">কোন ক্যাম্পেইন নেই</p></div>';
                    return;
                }

                const statusColor = {
                    'pending': 'bg-yellow-100 text-yellow-800',
                    'active': 'bg-green-100 text-green-800',
                    'completed': 'bg-blue-100 text-blue-800',
                    'cancelled': 'bg-red-100 text-red-800'
                };

                div.innerHTML = campaigns.map(c => {
                    const update = c.latest_update || {};
                    return `
                        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-800">${c.title || 'N/A'}</h3>
                                    <p class="text-gray-600 text-sm mt-1">
                                        <i class="fas fa-user mr-1 text-purple-500"></i>${c.user_name || 'Unknown'} 
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-bullhorn mr-1 text-blue-500"></i>${c.platform || 'N/A'}
                                    </p>
                                </div>
                                <span class="px-4 py-2 rounded-full text-sm font-semibold ${statusColor[c.status] || 'bg-gray-100 text-gray-800'}">${c.status || 'pending'}</span>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <p class="text-sm text-gray-600">বাজেট</p>
                                    <p class="text-lg font-semibold text-green-600">৳${parseFloat(c.budget || 0).toLocaleString('bn-BD')}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">শুরু</p>
                                    <p class="text-lg font-semibold">${c.start_date || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">শেষ</p>
                                    <p class="text-lg font-semibold">${c.end_date || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">টার্গেট</p>
                                    <p class="text-lg font-semibold">${c.target_audience || 'N/A'}</p>
                                </div>
                            </div>
                            
                            ${update.work_started ? `
                                <div class="bg-blue-50 rounded-lg p-4 mb-4">
                                    <h4 class="font-semibold text-gray-800 mb-2">বর্তমান আপডেট:</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div><p class="text-gray-600">Spend</p><p class="font-semibold">৳${update.spend_report || 0}</p></div>
                                        <div><p class="text-gray-600">Leads</p><p class="font-semibold">${update.leads_generated || 0}</p></div>
                                        <div><p class="text-gray-600">Reach</p><p class="font-semibold">${update.engagement_reach || 0}</p></div>
                                        <div><p class="text-gray-600">Updated</p><p class="font-semibold">${update.updated_at ? new Date(update.updated_at).toLocaleDateString('bn-BD') : 'N/A'}</p></div>
                                    </div>
                                    ${update.notes ? `<p class="text-sm text-gray-700 mt-2"><strong>নোট:</strong> ${update.notes}</p>` : ''}
                                </div>
                            ` : '<p class="text-sm text-gray-500 mb-4">কাজ এখনও শুরু হয়নি</p>'}
                            
                            <button onclick="showUpdateModal(${c.id})" class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-6 py-2 rounded-lg hover:from-blue-600 hover:to-cyan-600 transition">
                                <i class="fas fa-edit mr-2"></i>আপডেট করুন
                            </button>
                        </div>
                    `;
                }).join('');
            } catch (error) {
                console.error('Error loading campaigns:', error);
                document.getElementById('campaignsList').innerHTML = '<p class="text-red-500 text-center py-8">একটি ত্রুটি হয়েছে</p>';
            }
        }
        
        function showUpdateModal(campaignId) {
            document.getElementById('updateCampaignId').value = campaignId;
            document.getElementById('updateModal').classList.remove('hidden');
            document.getElementById('updateModal').classList.add('flex');
        }
        
        function hideUpdateModal() {
            document.getElementById('updateModal').classList.add('hidden');
            document.getElementById('updateModal').classList.remove('flex');
        }
        
        document.getElementById('updateForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('action', 'update_progress');
            formData.append('campaign_id', document.getElementById('updateCampaignId').value);
            formData.append('work_started', document.getElementById('workStarted').checked ? '1' : '0');
            formData.append('ad_results', document.getElementById('adResults').value);
            formData.append('spend_report', document.getElementById('spendReport').value);
            formData.append('leads_generated', document.getElementById('leadsGenerated').value);
            formData.append('engagement_reach', document.getElementById('engagementReach').value);
            formData.append('daily_analytics', document.getElementById('dailyAnalytics').value);
            formData.append('notes', document.getElementById('notes').value);
            
            try {
                const res = await fetch('../api/campaigns.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    showMessage(data.message, 'success');
                    hideUpdateModal();
                    loadCampaigns();
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি হয়েছে', 'error');
                console.error('Error updating campaign:', error);
            }
        });
        
        async function logout() {
            if (!confirm('আপনি লগআউট করতে চান?')) return;
            const res = await fetch('../api/auth.php?action=logout', { method: 'POST' });
            const data = await res.json();
            if (data.success) window.location.href = data.redirect;
        }
        
        loadCampaigns();
        setInterval(loadCampaigns, 30000);
    </script>
</body>
</html>

