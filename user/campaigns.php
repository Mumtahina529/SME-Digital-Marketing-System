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
    <title>ক্যাম্পেইন - <?php echo $userName; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Noto Sans Bengali', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-purple-600 to-indigo-700 text-white shadow-xl">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-8"><i class="fas fa-bullhorn mr-2"></i>SME Marketing</h1>
            <nav class="space-y-2">
                <a href="dashboard.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-home mr-3"></i>ড্যাশবোর্ড
                </a>
                <a href="campaigns.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-bullhorn mr-3"></i>আমার ক্যাম্পেইন
                </a>
                <a href="providers.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-users mr-3"></i>আমার প্রোভাইডার
                </a>
                <a href="messages.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-comments mr-3"></i>মেসেজ
                </a>
                <a href="payments.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-credit-card mr-3"></i>পেমেন্ট
                </a>
                <a href="notifications.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-bell mr-3"></i>নোটিফিকেশন
                </a>
                <a href="support.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-headset mr-3"></i>সাপোর্ট
                </a>
                <a href="#" onclick="logout()"
                    class="flex items-center px-4 py-3 hover:bg-red-500 rounded-lg transition mt-4">
                    <i class="fas fa-sign-out-alt mr-3"></i>লগআউট
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">আমার ক্যাম্পেইন</h1>
                <p class="text-gray-600 mt-2">আপনার সব মার্কেটিং ক্যাম্পেইন</p>
            </div>
            <button onclick="showCreateModal()"
                class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition">
                <i class="fas fa-plus mr-2"></i>নতুন ক্যাম্পেইন
            </button>
        </div>

        <!-- Campaigns List -->
        <div id="campaignsList" class="space-y-6">
            <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
        </div>
    </div>

    <!-- Create Campaign Modal -->
    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">নতুন ক্যাম্পেইন তৈরি করুন</h2>
                <button onclick="hideCreateModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="createCampaignForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">প্রোভাইডার</label>
                    <select name="provider_id" id="providerSelect" required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                        <option value="">প্রোভাইডার নির্বাচন করুন</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ক্যাম্পেইন শিরোনাম</label>
                    <input type="text" name="title" required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">প্ল্যাটফর্ম</label>
                    <select name="platform" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                        <option value="Facebook">Facebook</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Google Ads">Google Ads</option>
                        <option value="LinkedIn">LinkedIn</option>
                        <option value="Twitter">Twitter</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বাজেট (৳)</label>
                    <input type="number" name="budget" required min="0"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">টার্গেট অডিয়েন্স</label>
                    <textarea name="target_audience" rows="3" required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শুরু তারিখ</label>
                        <input type="date" name="start_date" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">শেষ তারিখ</label>
                        <input type="date" name="end_date" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">লোগো (ঐচ্ছিক)</label>
                    <input type="file" name="logo" accept="image/*"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-green-500 to-teal-500 text-white py-3 rounded-lg font-semibold hover:from-green-600 hover:to-teal-600 transition">
                        <i class="fas fa-check mr-2"></i>তৈরি করুন
                    </button>
                    <button type="button" onclick="hideCreateModal()"
                        class="flex-1 bg-gray-500 text-white py-3 rounded-lg font-semibold hover:bg-gray-600 transition">
                        <i class="fas fa-times mr-2"></i>বাতিল
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">রিভিউ দিন</h2>
            <form id="reviewForm" class="space-y-4">
                <input type="hidden" id="reviewCampaignId">
                <input type="hidden" id="reviewProviderId">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">রেটিং</label>
                    <div class="flex gap-2" id="ratingStars">
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer" data-rating="1"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer" data-rating="2"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer" data-rating="3"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer" data-rating="4"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer" data-rating="5"></i>
                    </div>
                    <input type="hidden" id="rating" name="rating" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ফিডব্যাক</label>
                    <textarea name="feedback" rows="4" required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg"></textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-yellow-500 to-orange-500 text-white py-3 rounded-lg font-semibold">জমা
                        দিন</button>
                    <button type="button" onclick="hideReviewModal()"
                        class="flex-1 bg-gray-500 text-white py-3 rounded-lg font-semibold">বাতিল</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function loadCampaigns() {
            const res = await fetch('../api/campaigns.php?action=my_campaigns');
            const data = await res.json();

            if (data.success) {
                const div = document.getElementById('campaignsList');
                if (data.data.length > 0) {
                    div.innerHTML = data.data.map(c => {
                        const update = c.latest_update || {};
                        return `
                            <div class="bg-white rounded-xl shadow-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-800">${c.title}</h3>
                                        <p class="text-gray-600">${c.provider_name} • ${c.platform}</p>
                                    </div>
                                    <span class="px-4 py-2 rounded-full text-sm font-semibold ${c.status === 'active' ? 'bg-green-100 text-green-800' : c.status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}">${c.status}</span>
                                </div>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <div>
                                        <p class="text-sm text-gray-600">বাজেট</p>
                                        <p class="text-lg font-semibold">৳${parseFloat(c.budget).toLocaleString()}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">শুরু</p>
                                        <p class="text-lg font-semibold">${c.start_date}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">শেষ</p>
                                        <p class="text-lg font-semibold">${c.end_date}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">খরচ</p>
                                        <p class="text-lg font-semibold">৳${update.spend_report || 0}</p>
                                    </div>
                                </div>
                                
                                ${update.work_started ? `
                                    <div class="bg-blue-50 rounded-lg p-4 mb-4">
                                        <h4 class="font-semibold text-gray-800 mb-2">কাজের আপডেট:</h4>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                            <div>
                                                <p class="text-gray-600">লিড</p>
                                                <p class="font-semibold">${update.leads_generated || 0}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">রিচ</p>
                                                <p class="font-semibold">${update.engagement_reach || 0}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">রেজাল্ট</p>
                                                <p class="font-semibold">${update.ad_results || 'N/A'}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">আপডেট</p>
                                                <p class="font-semibold">${new Date(update.updated_at).toLocaleDateString('bn-BD')}</p>
                                            </div>
                                        </div>
                                        ${update.notes ? `<p class="mt-2 text-sm text-gray-700"><strong>নোট:</strong> ${update.notes}</p>` : ''}
                                    </div>
                                ` : '<p class="text-gray-500 text-sm">কাজ এখনও শুরু হয়নি</p>'}
                                
                                <div class="flex gap-2 mt-4">
                                    <a href="messages.php?provider_id=${c.provider_id}" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition">
                                        <i class="fas fa-comments mr-2"></i>চ্যাট
                                    </a>
                                    ${c.status === 'pending' ? `
                                        <a href="payment.php?campaign_id=${c.id}" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                            <i class="fas fa-credit-card mr-2"></i>পেমেন্ট করুন
                                        </a>
                                    ` : ''}
                                    ${c.status === 'completed' ? `
                                        <button onclick="showReviewModal(${c.id}, ${c.provider_id})" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                                            <i class="fas fa-star mr-2"></i>রিভিউ দিন
                                        </button>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    div.innerHTML = '<p class="text-gray-500 text-center py-8">কোন ক্যাম্পেইন নেই</p>';
                }
            }
        }

        async function loadApprovedProviders() {
            const res = await fetch('../api/providers.php?action=my_providers');
            const data = await res.json();

            const select = document.getElementById('providerSelect');
            if (data.success) {
                const approved = data.data.filter(p => p.request_status === 'approved');
                if (approved.length === 0) {
                    select.innerHTML = '<option value="">কোন অনুমোদিত প্রোভাইডার নেই (প্রথমে প্রোভাইডার নির্বাচন করুন)</option>';
                } else {
                    select.innerHTML = '<option value="">প্রোভাইডার নির্বাচন করুন</option>' +
                        approved.map(p => `<option value="${p.id}">${p.name} - ${p.category}</option>`).join('');
                }
            } else {
                select.innerHTML = '<option value="">লোড করতে সমস্যা হয়েছে</option>';
            }
        }

        function showCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
            document.getElementById('createModal').classList.add('flex');
            loadApprovedProviders();
        }

        function hideCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.getElementById('createModal').classList.remove('flex');
        }

        document.getElementById('createCampaignForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.append('action', 'create');

            const res = await fetch('../api/campaigns.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                alert(data.message);
                hideCreateModal();
                loadCampaigns();
            } else {
                alert(data.message);
            }
        });

        function showReviewModal(campaignId, providerId) {
            document.getElementById('reviewCampaignId').value = campaignId;
            document.getElementById('reviewProviderId').value = providerId;
            document.getElementById('reviewModal').classList.remove('hidden');
            document.getElementById('reviewModal').classList.add('flex');

            // Reset stars
            document.querySelectorAll('#ratingStars i').forEach(star => {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            });
            document.getElementById('rating').value = '';
        }

        function hideReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.getElementById('reviewModal').classList.remove('flex');
        }

        // Star rating
        document.querySelectorAll('#ratingStars i').forEach(star => {
            star.addEventListener('click', function () {
                const rating = parseInt(this.dataset.rating);
                document.getElementById('rating').value = rating;

                document.querySelectorAll('#ratingStars i').forEach((s, index) => {
                    if (index < rating) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
            });
        });

        document.getElementById('reviewForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData();
            formData.append('action', 'submit');
            formData.append('campaign_id', document.getElementById('reviewCampaignId').value);
            formData.append('provider_id', document.getElementById('reviewProviderId').value);
            formData.append('rating', document.getElementById('rating').value);
            formData.append('feedback', document.querySelector('#reviewForm textarea[name="feedback"]').value);

            const res = await fetch('../api/reviews.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                alert(data.message);
                hideReviewModal();
                loadCampaigns();
            } else {
                alert(data.message);
            }
        });

        async function logout() {
            const res = await fetch('../api/auth.php?action=logout', { method: 'POST' });
            const data = await res.json();
            if (data.success) window.location.href = data.redirect;
        }

        loadCampaigns();
        setInterval(loadCampaigns, 30000);
    </script>
</body>

</html>