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
    <title>অ্যাডমিন ড্যাশবোর্ড</title>
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
    <div class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-red-600 to-pink-700 text-white shadow-xl">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-8"><i class="fas fa-user-shield mr-2"></i>Admin</h1>
            <nav class="space-y-2">
                <a href="dashboard.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-home mr-3"></i>ড্যাশবোর্ড
                </a>
                <a href="approvals.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-check-circle mr-3"></i>অনুমোদন
                </a>
                <a href="campaigns.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-bullhorn mr-3"></i>ক্যাম্পেইন
                </a>
                <a href="users.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-users mr-3"></i>ইউজার
                </a>
                <a href="providers.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-briefcase mr-3"></i>প্রোভাইডার
                </a>
                <a href="payouts.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-money-bill-wave mr-3"></i>Payouts
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

    <div class="ml-64 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">স্বাগতম, <?php echo $adminName; ?>!</h1>
            <p class="text-gray-600 mt-2">অ্যাডমিন ড্যাশবোর্ড - সম্পূর্ণ সিস্টেম ওভারভিউ</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div
                class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">মোট ইউজার</p>
                        <p class="text-3xl font-bold mt-2" id="totalUsers">0</p>
                    </div>
                    <i class="fas fa-users text-4xl opacity-50"></i>
                </div>
            </div>
            <div
                class="bg-gradient-to-r from-green-500 to-teal-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">মোট প্রোভাইডার</p>
                        <p class="text-3xl font-bold mt-2" id="totalProviders">0</p>
                    </div>
                    <i class="fas fa-briefcase text-4xl opacity-50"></i>
                </div>
            </div>
            <div
                class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">সক্রিয় ক্যাম্পেইন</p>
                        <p class="text-3xl font-bold mt-2" id="activeCampaigns">0</p>
                    </div>
                    <i class="fas fa-bullhorn text-4xl opacity-50"></i>
                </div>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:scale-105 cursor-pointer"
                onclick="window.location.href='approvals.php'">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">অপেক্ষমান অনুমোদন</p>
                        <p class="text-3xl font-bold mt-2" id="pendingApprovals">0</p>
                    </div>
                    <i class="fas fa-exclamation-circle text-4xl opacity-50"></i>
                </div>
            </div>
            <div
                class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Platform Fee (20%)</p>
                        <p class="text-3xl font-bold mt-2" id="totalRevenue">৳0</p>
                    </div>
                    <i class="fas fa-money-bill-wave text-4xl opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">দ্রুত কাজ</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="approvals.php"
                    class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white p-4 rounded-lg text-center hover:shadow-lg transition">
                    <i class="fas fa-check-circle text-2xl mb-2"></i>
                    <p class="font-semibold">অনুমোদন</p>
                </a>
                <a href="campaigns.php"
                    class="bg-gradient-to-r from-purple-400 to-pink-500 text-white p-4 rounded-lg text-center hover:shadow-lg transition">
                    <i class="fas fa-bullhorn text-2xl mb-2"></i>
                    <p class="font-semibold">ক্যাম্পেইন</p>
                </a>
                <a href="users.php"
                    class="bg-gradient-to-r from-blue-400 to-cyan-500 text-white p-4 rounded-lg text-center hover:shadow-lg transition">
                    <i class="fas fa-users text-2xl mb-2"></i>
                    <p class="font-semibold">ইউজার</p>
                </a>
                <a href="payouts.php"
                    class="bg-gradient-to-r from-green-400 to-teal-500 text-white p-4 rounded-lg text-center hover:shadow-lg transition">
                    <i class="fas fa-money-bill-wave text-2xl mb-2"></i>
                    <p class="font-semibold">Payouts</p>
                </a>
            </div>
        </div>

        <!-- Pending Items -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">অপেক্ষমান প্রোভাইডার নিবন্ধন</h2>
                    <a href="approvals.php" class="text-purple-600 hover:underline text-sm">সব দেখুন</a>
                </div>
                <div id="pendingProviders" class="space-y-3 max-h-96 overflow-y-auto">
                    <p class="text-gray-500 text-center py-4">লোড হচ্ছে...</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">অপেক্ষমান প্রোভাইডার অনুরোধ</h2>
                    <a href="approvals.php" class="text-purple-600 hover:underline text-sm">সব দেখুন</a>
                </div>
                <div id="pendingRequests" class="space-y-3 max-h-96 overflow-y-auto">
                    <p class="text-gray-500 text-center py-4">লোড হচ্ছে...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Toast -->
    <div id="messageToast" class="fixed top-4 right-4 hidden p-4 rounded-lg shadow-lg z-50 max-w-sm"></div>

    <script>
        async function loadDashboard() {
            try {
                // Load Stats
                const statsRes = await fetch('../api/admin.php?action=dashboard_stats');
                const statsData = await statsRes.json();

                if (statsData.success) {
                    document.getElementById('totalUsers').textContent = statsData.data.total_users || 0;
                    document.getElementById('totalProviders').textContent = statsData.data.total_providers || 0;
                    document.getElementById('activeCampaigns').textContent = statsData.data.active_campaigns || 0;
                    document.getElementById('pendingApprovals').textContent = statsData.data.pending_approvals || 0;
                    document.getElementById('totalRevenue').textContent = '৳' + parseFloat(statsData.data.total_revenue || 0).toLocaleString('bn-BD');
                } else {
                    console.error('Failed to load stats');
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }

            try {
                // Load Pending Providers
                const providersRes = await fetch('../api/admin.php?action=pending_providers');
                const providersData = await providersRes.json();

                if (providersData.success) {
                    const div = document.getElementById('pendingProviders');
                    if (providersData.data.length > 0) {
                        div.innerHTML = providersData.data.slice(0, 5).map(p => `
                            <div class="border-l-4 border-yellow-500 bg-yellow-50 p-4 rounded-lg hover:shadow-md transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-800">${p.name || 'N/A'}</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-tag mr-1 text-purple-500"></i>${p.category || 'N/A'}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-clock mr-1"></i>${new Date(p.created_at).toLocaleDateString('bn-BD')}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3 flex gap-2">
                                    <button onclick="approveProvider(${p.id})" class="flex-1 bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition text-sm font-semibold">
                                        <i class="fas fa-check mr-1"></i>Approve
                                    </button>
                                    <button onclick="rejectProvider(${p.id})" class="flex-1 bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition text-sm font-semibold">
                                        <i class="fas fa-times mr-1"></i>Reject
                                    </button>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        div.innerHTML = '<div class="text-center py-8"><i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i><p class="text-gray-500">কোন অপেক্ষমান নিবন্ধন নেই</p></div>';
                    }
                } else {
                    document.getElementById('pendingProviders').innerHTML = '<p class="text-red-500 text-center py-4">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                }
            } catch (error) {
                console.error('Error loading providers:', error);
                document.getElementById('pendingProviders').innerHTML = '<p class="text-red-500 text-center py-4">একটি ত্রুটি হয়েছে</p>';
            }

            try {
                // Load Pending Requests
                const requestsRes = await fetch('../api/admin.php?action=pending_requests');
                const requestsData = await requestsRes.json();

                if (requestsData.success) {
                    const div = document.getElementById('pendingRequests');
                    if (requestsData.data.length > 0) {
                        div.innerHTML = requestsData.data.slice(0, 5).map(r => `
                            <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-lg hover:shadow-md transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                ${(r.user_name || 'U').charAt(0).toUpperCase()}
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-800 text-sm">${r.user_name || 'Unknown'}</h3>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 text-blue-600">
                                            <i class="fas fa-arrow-right text-xs"></i>
                                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                ${(r.provider_name || 'P').charAt(0).toUpperCase()}
                                            </div>
                                            <span class="text-sm font-semibold">${r.provider_name || 'Unknown'}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">
                                            <i class="fas fa-clock mr-1"></i>${new Date(r.requested_at).toLocaleDateString('bn-BD')}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3 flex gap-2">
                                    <button onclick="approveRequest(${r.id})" class="flex-1 bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition text-sm font-semibold">
                                        <i class="fas fa-check mr-1"></i>Approve
                                    </button>
                                    <button onclick="rejectRequest(${r.id})" class="flex-1 bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition text-sm font-semibold">
                                        <i class="fas fa-times mr-1"></i>Reject
                                    </button>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        div.innerHTML = '<div class="text-center py-8"><i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i><p class="text-gray-500">কোন অপেক্ষমান অনুরোধ নেই</p></div>';
                    }
                } else {
                    document.getElementById('pendingRequests').innerHTML = '<p class="text-red-500 text-center py-4">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                }
            } catch (error) {
                console.error('Error loading requests:', error);
                document.getElementById('pendingRequests').innerHTML = '<p class="text-red-500 text-center py-4">একটি ত্রুটি হয়েছে</p>';
            }
        }

        function showMessage(text, type = 'success') {
            const toast = document.getElementById('messageToast');
            toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white font-semibold`;
            toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>${text}`;
            toast.classList.remove('hidden');

            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        async function approveProvider(id) {
            if (!confirm('আপনি এই প্রোভাইডার নিবন্ধন অনুমোদন করতে চান?')) return;

            const formData = new FormData();
            formData.append('action', 'approve_provider_registration');
            formData.append('provider_id', id);

            try {
                const res = await fetch('../api/admin.php', { method: 'POST', body: formData });
                const data = await res.json();

                if (data.success) {
                    showMessage(data.message, 'success');
                    loadDashboard();
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি হয়েছে', 'error');
                console.error('Error:', error);
            }
        }

        async function rejectProvider(id) {
            if (!confirm('আপনি এই প্রোভাইডার নিবন্ধন প্রত্যাখ্যান করতে চান?')) return;

            const formData = new FormData();
            formData.append('action', 'reject_provider_registration');
            formData.append('provider_id', id);

            try {
                const res = await fetch('../api/admin.php', { method: 'POST', body: formData });
                const data = await res.json();

                if (data.success) {
                    showMessage(data.message, 'success');
                    loadDashboard();
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি হয়েছে', 'error');
                console.error('Error:', error);
            }
        }

        async function approveRequest(id) {
            if (!confirm('আপনি এই প্রোভাইডার অনুরোধ অনুমোদন করতে চান? চ্যাট স্বয়ংক্রিয়ভাবে আনলক হবে।')) return;

            const formData = new FormData();
            formData.append('action', 'approve_provider_request');
            formData.append('request_id', id);

            try {
                const res = await fetch('../api/admin.php', { method: 'POST', body: formData });
                const data = await res.json();

                if (data.success) {
                    showMessage(data.message, 'success');
                    loadDashboard();
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি হয়েছে', 'error');
                console.error('Error:', error);
            }
        }

        async function rejectRequest(id) {
            if (!confirm('আপনি এই প্রোভাইডার অনুরোধ প্রত্যাখ্যান করতে চান?')) return;

            const formData = new FormData();
            formData.append('action', 'reject_provider_request');
            formData.append('request_id', id);

            try {
                const res = await fetch('../api/admin.php', { method: 'POST', body: formData });
                const data = await res.json();

                if (data.success) {
                    showMessage(data.message, 'success');
                    loadDashboard();
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি হয়েছে', 'error');
                console.error('Error:', error);
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