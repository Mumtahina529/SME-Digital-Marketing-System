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
    <title>Payout ব্যবস্থাপনা - Admin</title>
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
                <a href="campaigns.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-bullhorn mr-3"></i>ক্যাম্পেইন
                </a>
                <a href="users.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-users mr-3"></i>ইউজার
                </a>
                <a href="providers.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-briefcase mr-3"></i>প্রোভাইডার
                </a>
                <a href="payouts.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
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
            <h1 class="text-3xl font-bold text-gray-800">Payout ব্যবস্থাপনা</h1>
            <p class="text-gray-600 mt-2">প্রোভাইডার payout অনুরোধ দেখুন এবং অনুমোদন করুন</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">মোট Payout</p>
                <p class="text-3xl font-bold mt-2" id="totalPayouts">0</p>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">অপেক্ষমান</p>
                <p class="text-3xl font-bold mt-2" id="pendingPayouts">0</p>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">অনুমোদিত</p>
                <p class="text-3xl font-bold mt-2" id="approvedPayouts">0</p>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">মোট পরিমাণ</p>
                <p class="text-3xl font-bold mt-2" id="totalAmount">৳0</p>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                    <select id="filterStatus" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <option value="">সব</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">পেমেন্ট মেথড</label>
                    <select id="filterMethod" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <option value="">সব</option>
                        <option value="bkash">Bkash</option>
                        <option value="nagad">Nagad</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">তারিখ</label>
                    <input type="date" id="filterDate" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                </div>
            </div>
            <button onclick="loadPayouts()" class="mt-4 w-full md:w-auto bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition">
                <i class="fas fa-filter mr-2"></i>ফিল্টার করুন
            </button>
        </div>
        
        <!-- Payouts List -->
        <div id="payoutsList" class="space-y-4">
            <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
        </div>
    </div>
    
    <!-- Payout Details Modal -->
    <div id="payoutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Payout বিস্তারিত</h2>
                <button onclick="closePayoutModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div id="payoutDetails" class="space-y-4">
                <!-- Payout details will be loaded here -->
            </div>
        </div>
    </div>
    
    <!-- Message Toast -->
    <div id="messageToast" class="fixed top-4 right-4 hidden p-4 rounded-lg shadow-lg z-50 max-w-sm"></div>
    
    <script>
        async function loadPayouts() {
            try {
                const res = await fetch('../api/admin.php?action=all_payouts');
                const data = await res.json();
                
                if (data.success) {
                    let payouts = data.data || [];
                    
                    // Apply filters
                    const status = document.getElementById('filterStatus').value;
                    const method = document.getElementById('filterMethod').value;
                    const date = document.getElementById('filterDate').value;
                    
                    if (status) {
                        payouts = payouts.filter(p => p.status === status);
                    }
                    
                    if (method) {
                        payouts = payouts.filter(p => p.payment_method === method);
                    }
                    
                    if (date) {
                        payouts = payouts.filter(p => p.requested_at.startsWith(date));
                    }
                    
                    // Update stats
                    const total = data.data.length;
                    const pending = data.data.filter(p => p.status === 'pending').length;
                    const approved = data.data.filter(p => p.status === 'approved' || p.status === 'completed').length;
                    const totalAmount = data.data.reduce((sum, p) => sum + parseFloat(p.amount || 0), 0);
                    
                    document.getElementById('totalPayouts').textContent = total;
                    document.getElementById('pendingPayouts').textContent = pending;
                    document.getElementById('approvedPayouts').textContent = approved;
                    document.getElementById('totalAmount').textContent = '৳' + totalAmount.toLocaleString('bn-BD');
                    
                    // Display payouts
                    const div = document.getElementById('payoutsList');
                    if (payouts.length > 0) {
                        div.innerHTML = payouts.map(p => {
                            const statusColors = {
                                'pending': 'bg-yellow-100 text-yellow-800',
                                'approved': 'bg-blue-100 text-blue-800',
                                'rejected': 'bg-red-100 text-red-800',
                                'completed': 'bg-green-100 text-green-800'
                            };
                            
                            const statusLabels = {
                                'pending': 'Pending',
                                'approved': 'Approved',
                                'rejected': 'Rejected',
                                'completed': 'Completed'
                            };
                            
                            return `
                                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-4 mb-3">
                                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-500 rounded-full flex items-center justify-center text-white font-bold">
                                                    ${(p.provider_name || 'P').charAt(0).toUpperCase()}
                                                </div>
                                                <div>
                                                    <h3 class="text-lg font-bold text-gray-800">${p.provider_name || 'Unknown Provider'}</h3>
                                                    <p class="text-sm text-gray-600">${p.provider_email || ''}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <p class="text-xs text-gray-600">পরিমাণ</p>
                                                    <p class="text-lg font-bold text-green-600">৳${parseFloat(p.amount || 0).toLocaleString('bn-BD')}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <p class="text-xs text-gray-600">পেমেন্ট মেথড</p>
                                                    <p class="text-sm font-semibold text-gray-800">${p.payment_method || 'N/A'}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <p class="text-xs text-gray-600">অনুরোধ তারিখ</p>
                                                    <p class="text-sm font-semibold text-gray-800">${new Date(p.requested_at).toLocaleDateString('bn-BD')}</p>
                                                </div>
                                                ${p.approved_at ? `
                                                    <div class="bg-gray-50 rounded-lg p-3">
                                                        <p class="text-xs text-gray-600">অনুমোদন তারিখ</p>
                                                        <p class="text-sm font-semibold text-gray-800">${new Date(p.approved_at).toLocaleDateString('bn-BD')}</p>
                                                    </div>
                                                ` : ''}
                                            </div>
                                            
                                            ${p.account_details ? `
                                                <div class="mt-4 bg-blue-50 rounded-lg p-3">
                                                    <p class="text-sm font-semibold text-gray-800 mb-1">অ্যাকাউন্ট বিবরণ:</p>
                                                    <p class="text-sm text-gray-700">${p.account_details}</p>
                                                </div>
                                            ` : ''}
                                        </div>
                                        
                                        <div class="flex flex-col items-end gap-2 ml-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold ${statusColors[p.status] || 'bg-gray-100 text-gray-800'}">
                                                ${statusLabels[p.status] || p.status}
                                            </span>
                                            <div class="flex flex-col gap-2">
                                                <button onclick="viewPayoutDetails(${p.id})" class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm">
                                                    <i class="fas fa-eye mr-1"></i>বিস্তারিত
                                                </button>
                                                ${p.status === 'pending' ? `
                                                    <button onclick="approvePayout(${p.id})" class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm">
                                                        <i class="fas fa-check mr-1"></i>Approve
                                                    </button>
                                                    <button onclick="rejectPayout(${p.id})" class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                                                        <i class="fas fa-times mr-1"></i>Reject
                                                    </button>
                                                ` : ''}
                                                ${p.status === 'approved' ? `
                                                    <button onclick="completePayout(${p.id})" class="px-3 py-1 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition text-sm">
                                                        <i class="fas fa-check-double mr-1"></i>Complete
                                                    </button>
                                                ` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        div.innerHTML = '<div class="text-center py-12"><i class="fas fa-money-bill-wave text-gray-400 text-5xl mb-4"></i><p class="text-gray-500 text-lg">কোন payout পাওয়া যায়নি</p></div>';
                    }
                } else {
                    document.getElementById('payoutsList').innerHTML = '<p class="text-red-500 text-center py-8">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                }
            } catch (error) {
                console.error('Error loading payouts:', error);
                document.getElementById('payoutsList').innerHTML = '<p class="text-red-500 text-center py-8">একটি ত্রুটি হয়েছে</p>';
            }
        }
        
        async function viewPayoutDetails(payoutId) {
            try {
                const res = await fetch(`../api/admin.php?action=payout_details&payout_id=${payoutId}`);
                const data = await res.json();
                
                if (data.success) {
                    const payout = data.data;
                    const div = document.getElementById('payoutDetails');
                    
                    div.innerHTML = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">প্রোভাইডার</label>
                                    <p class="text-gray-800 font-semibold">${payout.provider_name || 'N/A'}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">পরিমাণ</label>
                                    <p class="text-2xl font-bold text-green-600">৳${parseFloat(payout.amount || 0).toLocaleString('bn-BD')}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">পেমেন্ট মেথড</label>
                                    <p class="text-gray-800 font-semibold">${payout.payment_method || 'N/A'}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">স্ট্যাটাস</label>
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold ${payout.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : payout.status === 'approved' ? 'bg-blue-100 text-blue-800' : payout.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                        ${payout.status || 'N/A'}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">অনুরোধ তারিখ</label>
                                    <p class="text-gray-800">${new Date(payout.requested_at).toLocaleString('bn-BD')}</p>
                                </div>
                                ${payout.approved_at ? `
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">অনুমোদন তারিখ</label>
                                        <p class="text-gray-800">${new Date(payout.approved_at).toLocaleString('bn-BD')}</p>
                                    </div>
                                ` : ''}
                            </div>
                            
                            ${payout.account_details ? `
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">অ্যাকাউন্ট বিবরণ</label>
                                    <p class="text-gray-800 whitespace-pre-wrap">${payout.account_details}</p>
                                </div>
                            ` : ''}
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-bold text-gray-800 mb-2">প্রোভাইডার তথ্য</h3>
                                <p class="text-sm text-gray-600">ইমেইল: ${payout.provider_email || 'N/A'}</p>
                                <p class="text-sm text-gray-600">ফোন: ${payout.provider_phone || 'N/A'}</p>
                            </div>
                        </div>
                    `;
                    
                    document.getElementById('payoutModal').classList.remove('hidden');
                    document.getElementById('payoutModal').classList.add('flex');
                }
            } catch (error) {
                showMessage('বিস্তারিত লোড করতে সমস্যা হয়েছে', 'error');
                console.error('Error:', error);
            }
        }
        
        function closePayoutModal() {
            document.getElementById('payoutModal').classList.add('hidden');
            document.getElementById('payoutModal').classList.remove('flex');
        }
        
        async function approvePayout(id) {
            if (!confirm('আপনি এই payout অনুমোদন করতে চান?')) return;
            
            const formData = new FormData();
            formData.append('action', 'approve_payout');
            formData.append('request_id', id);
            
            try {
                const res = await fetch('../api/payments.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    showMessage(data.message, 'success');
                    loadPayouts();
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি হয়েছে', 'error');
                console.error('Error:', error);
            }
        }
        
        async function rejectPayout(id) {
            if (!confirm('আপনি এই payout প্রত্যাখ্যান করতে চান?')) return;
            
            const formData = new FormData();
            formData.append('action', 'reject_payout');
            formData.append('request_id', id);
            
            try {
                const res = await fetch('../api/admin.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    showMessage(data.message, 'success');
                    loadPayouts();
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি হয়েছে', 'error');
                console.error('Error:', error);
            }
        }
        
        async function completePayout(id) {
            if (!confirm('আপনি এই payout সম্পন্ন করতে চান?')) return;
            
            const formData = new FormData();
            formData.append('action', 'complete_payout');
            formData.append('request_id', id);
            
            try {
                const res = await fetch('../api/admin.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    showMessage(data.message, 'success');
                    loadPayouts();
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
        
        // Load payouts on page load
        loadPayouts();
        
        // Auto-refresh every 60 seconds
        setInterval(loadPayouts, 60000);
    </script>
</body>
</html>

