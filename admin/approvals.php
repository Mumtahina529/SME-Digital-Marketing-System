<?php
require_once '../config/config.php';
requireLogin('admin');
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অনুমোদন - Admin</title>
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
                <a href="approvals.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-check-circle mr-3"></i>অনুমোদন
                </a>
                <a href="campaigns.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
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
        <h1 class="text-3xl font-bold text-gray-800 mb-8">অনুমোদন ব্যবস্থাপনা</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">অপেক্ষমান প্রোভাইডার নিবন্ধন</h2>
                <div id="pendingProviders" class="space-y-4">
                    <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">অপেক্ষমান প্রোভাইডার অনুরোধ</h2>
                <div id="pendingRequests" class="space-y-4">
                    <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        async function loadApprovals() {
            try {
                const providersRes = await fetch('../api/admin.php?action=pending_providers');
                const providersData = await providersRes.json();
                
                if (providersData.success) {
                    const div = document.getElementById('pendingProviders');
                    if (providersData.data.length > 0) {
                        div.innerHTML = providersData.data.map(p => `
                            <div class="border-2 border-yellow-300 rounded-lg p-4 bg-yellow-50 hover:shadow-md transition">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-800 text-lg">${p.name || 'N/A'}</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-envelope mr-1"></i>${p.email || 'N/A'}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-phone mr-1"></i>${p.phone || 'N/A'}
                                        </p>
                                        ${p.company_name ? `<p class="text-sm text-gray-600"><i class="fas fa-building mr-1"></i>${p.company_name}</p>` : ''}
                                    </div>
                                    <span class="px-3 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs font-semibold">Pending</span>
                                </div>
                                
                                <div class="bg-white rounded-lg p-3 mt-3 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">ক্যাটাগরি:</span>
                                        <span class="text-sm font-semibold text-purple-600">${p.category || 'N/A'}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">অভিজ্ঞতা:</span>
                                        <span class="text-sm font-semibold">${p.experience || 0} বছর</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">বাজেট:</span>
                                        <span class="text-sm font-semibold text-green-600">৳${parseFloat(p.budget_min || 0).toLocaleString()} - ৳${parseFloat(p.budget_max || 0).toLocaleString()}</span>
                                    </div>
                                    ${p.bio ? `<p class="text-xs text-gray-500 mt-2 border-t pt-2">${p.bio}</p>` : ''}
                                    <p class="text-xs text-gray-400 mt-2">
                                        <i class="fas fa-clock mr-1"></i>${new Date(p.created_at).toLocaleString('bn-BD')}
                                    </p>
                                </div>
                                
                                <div class="mt-4 flex gap-2">
                                    <button onclick="approveProvider(${p.id})" class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition font-semibold">
                                        <i class="fas fa-check mr-2"></i>অনুমোদন
                                    </button>
                                    <button onclick="rejectProvider(${p.id})" class="flex-1 bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg hover:from-red-600 hover:to-red-700 transition font-semibold">
                                        <i class="fas fa-times mr-2"></i>প্রত্যাখ্যান
                                    </button>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        div.innerHTML = '<div class="text-center py-12"><i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i><p class="text-gray-500 text-lg">কোন অপেক্ষমান নিবন্ধন নেই</p></div>';
                    }
                } else {
                    document.getElementById('pendingProviders').innerHTML = '<div class="text-center py-8"><p class="text-red-500">ডাটা লোড করতে সমস্যা হয়েছে</p></div>';
                }
            } catch (error) {
                console.error('Error loading providers:', error);
                document.getElementById('pendingProviders').innerHTML = '<div class="text-center py-8"><p class="text-red-500">একটি ত্রুটি হয়েছে</p></div>';
            }
            
            try {
                const requestsRes = await fetch('../api/admin.php?action=pending_requests');
                const requestsData = await requestsRes.json();
                
                if (requestsData.success) {
                    const div = document.getElementById('pendingRequests');
                    if (requestsData.data.length > 0) {
                        div.innerHTML = requestsData.data.map(r => `
                            <div class="border-2 border-blue-300 rounded-lg p-4 bg-blue-50 hover:shadow-md transition">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                                ${(r.user_name || 'U').charAt(0).toUpperCase()}
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-800">${r.user_name || 'Unknown User'}</h3>
                                                <p class="text-xs text-gray-500">${r.user_email || ''}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 text-blue-600 mt-2">
                                            <i class="fas fa-arrow-right"></i>
                                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                                ${(r.provider_name || 'P').charAt(0).toUpperCase()}
                                            </div>
                                            <div>
                                                <p class="font-semibold">${r.provider_name || 'Unknown Provider'}</p>
                                                <p class="text-xs text-gray-500">${r.provider_category || ''}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-xs font-semibold">Pending</span>
                                </div>
                                
                                <div class="bg-white rounded-lg p-3 mt-3">
                                    <p class="text-xs text-gray-400">
                                        <i class="fas fa-clock mr-1"></i>${new Date(r.requested_at).toLocaleString('bn-BD')}
                                    </p>
                                </div>
                                
                                <div class="mt-4 flex gap-2">
                                    <button onclick="approveRequest(${r.id})" class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition font-semibold">
                                        <i class="fas fa-check mr-2"></i>অনুমোদন
                                    </button>
                                    <button onclick="rejectRequest(${r.id})" class="flex-1 bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg hover:from-red-600 hover:to-red-700 transition font-semibold">
                                        <i class="fas fa-times mr-2"></i>প্রত্যাখ্যান
                                    </button>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        div.innerHTML = '<div class="text-center py-12"><i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i><p class="text-gray-500 text-lg">কোন অপেক্ষমান অনুরোধ নেই</p></div>';
                    }
                } else {
                    document.getElementById('pendingRequests').innerHTML = '<div class="text-center py-8"><p class="text-red-500">ডাটা লোড করতে সমস্যা হয়েছে</p></div>';
                }
            } catch (error) {
                console.error('Error loading requests:', error);
                document.getElementById('pendingRequests').innerHTML = '<div class="text-center py-8"><p class="text-red-500">একটি ত্রুটি হয়েছে</p></div>';
            }
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
                    loadApprovals();
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
                    loadApprovals();
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
                    loadApprovals();
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
                    loadApprovals();
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি হয়েছে', 'error');
                console.error('Error:', error);
            }
        }
        
        function showMessage(text, type = 'success') {
            const messageDiv = document.createElement('div');
            messageDiv.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white font-semibold`;
            messageDiv.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>${text}`;
            document.body.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }
        
        async function logout() {
            const res = await fetch('../api/auth.php?action=logout', { method: 'POST' });
            const data = await res.json();
            if (data.success) window.location.href = data.redirect;
        }
        
        // Load on page load
        loadApprovals();
        
        // Auto-refresh every 30 seconds
        setInterval(loadApprovals, 30000);
    </script>
</body>
</html>

