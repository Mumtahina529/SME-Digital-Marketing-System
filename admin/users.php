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
    <title>ইউজার ব্যবস্থাপনা - Admin</title>
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
                <a href="users.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
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
            <h1 class="text-3xl font-bold text-gray-800">ইউজার ব্যবস্থাপনা</h1>
            <p class="text-gray-600 mt-2">সব ইউজার দেখুন এবং ব্যবস্থাপনা করুন</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">মোট ইউজার</p>
                <p class="text-3xl font-bold mt-2" id="totalUsers">0</p>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">সক্রিয় ইউজার</p>
                <p class="text-3xl font-bold mt-2" id="activeUsers">0</p>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">নতুন (এই মাস)</p>
                <p class="text-3xl font-bold mt-2" id="newUsers">0</p>
            </div>
            <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">নিষ্ক্রিয় ইউজার</p>
                <p class="text-3xl font-bold mt-2" id="inactiveUsers">0</p>
            </div>
        </div>
        
        <!-- Search and Filter -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">খুঁজুন</label>
                    <input type="text" id="searchInput" placeholder="নাম, ইমেইল, বা ফোন..." class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                    <select id="filterStatus" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <option value="">সব</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">সাজান</label>
                    <select id="sortBy" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <option value="newest">নতুন প্রথম</option>
                        <option value="oldest">পুরাতন প্রথম</option>
                        <option value="name">নাম অনুযায়ী</option>
                    </select>
                </div>
            </div>
            <button onclick="loadUsers()" class="mt-4 w-full md:w-auto bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition">
                <i class="fas fa-search mr-2"></i>খুঁজুন
            </button>
        </div>
        
        <!-- Users List -->
        <div id="usersList" class="space-y-4">
            <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
        </div>
    </div>
    
    <!-- User Details Modal -->
    <div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">ইউজার বিস্তারিত</h2>
                <button onclick="closeUserModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div id="userDetails" class="space-y-4">
                <!-- User details will be loaded here -->
            </div>
        </div>
    </div>
    
    <!-- Message Toast -->
    <div id="messageToast" class="fixed top-4 right-4 hidden p-4 rounded-lg shadow-lg z-50 max-w-sm"></div>
    
    <script>
        async function loadUsers() {
            try {
                const res = await fetch('../api/admin.php?action=all_users');
                const data = await res.json();
                
                if (data.success) {
                    let users = data.data || [];
                    
                    // Apply filters
                    const search = document.getElementById('searchInput').value.toLowerCase();
                    const status = document.getElementById('filterStatus').value;
                    const sortBy = document.getElementById('sortBy').value;
                    
                    if (search) {
                        users = users.filter(u => 
                            (u.name && u.name.toLowerCase().includes(search)) ||
                            (u.email && u.email.toLowerCase().includes(search)) ||
                            (u.phone && u.phone.includes(search)) ||
                            (u.company_name && u.company_name.toLowerCase().includes(search))
                        );
                    }
                    
                    if (status) {
                        users = users.filter(u => u.status === status);
                    }
                    
                    // Sort
                    if (sortBy === 'newest') {
                        users.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                    } else if (sortBy === 'oldest') {
                        users.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                    } else if (sortBy === 'name') {
                        users.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
                    }
                    
                    // Update stats
                    const total = data.data.length;
                    const active = data.data.filter(u => u.status === 'active').length;
                    const inactive = data.data.filter(u => u.status === 'inactive').length;
                    const thisMonth = data.data.filter(u => {
                        const created = new Date(u.created_at);
                        const now = new Date();
                        return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear();
                    }).length;
                    
                    document.getElementById('totalUsers').textContent = total;
                    document.getElementById('activeUsers').textContent = active;
                    document.getElementById('inactiveUsers').textContent = inactive;
                    document.getElementById('newUsers').textContent = thisMonth;
                    
                    // Display users
                    const div = document.getElementById('usersList');
                    if (users.length > 0) {
                        div.innerHTML = users.map(u => `
                            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-4 flex-1">
                                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                            ${(u.name || 'U').charAt(0).toUpperCase()}
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-gray-800">${u.name || 'N/A'}</h3>
                                            <div class="mt-2 space-y-1">
                                                <p class="text-sm text-gray-600">
                                                    <i class="fas fa-envelope mr-2 text-purple-500"></i>${u.email || 'N/A'}
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <i class="fas fa-phone mr-2 text-blue-500"></i>${u.phone || 'N/A'}
                                                </p>
                                                ${u.company_name ? `
                                                    <p class="text-sm text-gray-600">
                                                        <i class="fas fa-building mr-2 text-green-500"></i>${u.company_name}
                                                    </p>
                                                ` : ''}
                                                <p class="text-xs text-gray-500">
                                                    <i class="fas fa-clock mr-1"></i>নিবন্ধন: ${new Date(u.created_at).toLocaleString('bn-BD')}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold ${u.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                            ${u.status === 'active' ? 'Active' : 'Inactive'}
                                        </span>
                                        <div class="flex gap-2">
                                            <button onclick="viewUserDetails(${u.id})" class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm">
                                                <i class="fas fa-eye mr-1"></i>বিস্তারিত
                                            </button>
                                            <button onclick="toggleUserStatus(${u.id}, '${u.status}')" class="px-3 py-1 ${u.status === 'active' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600'} text-white rounded-lg transition text-sm">
                                                <i class="fas ${u.status === 'active' ? 'fa-ban' : 'fa-check'} mr-1"></i>${u.status === 'active' ? 'নিষ্ক্রিয়' : 'সক্রিয়'}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        div.innerHTML = '<div class="text-center py-12"><i class="fas fa-users text-gray-400 text-5xl mb-4"></i><p class="text-gray-500 text-lg">কোন ইউজার পাওয়া যায়নি</p></div>';
                    }
                } else {
                    document.getElementById('usersList').innerHTML = '<p class="text-red-500 text-center py-8">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                }
            } catch (error) {
                console.error('Error loading users:', error);
                document.getElementById('usersList').innerHTML = '<p class="text-red-500 text-center py-8">একটি ত্রুটি হয়েছে</p>';
            }
        }
        
        async function viewUserDetails(userId) {
            try {
                const res = await fetch(`../api/admin.php?action=user_details&user_id=${userId}`);
                const data = await res.json();
                
                if (data.success) {
                    const user = data.data;
                    const div = document.getElementById('userDetails');
                    
                    div.innerHTML = `
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">নাম</label>
                                <p class="text-gray-800 font-semibold">${user.name || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ইমেইল</label>
                                <p class="text-gray-800 font-semibold">${user.email || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ফোন</label>
                                <p class="text-gray-800 font-semibold">${user.phone || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">স্ট্যাটাস</label>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold ${user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${user.status === 'active' ? 'Active' : 'Inactive'}
                                </span>
                            </div>
                            ${user.company_name ? `
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">কোম্পানির নাম</label>
                                    <p class="text-gray-800 font-semibold">${user.company_name}</p>
                                </div>
                            ` : ''}
                            ${user.address ? `
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ঠিকানা</label>
                                    <p class="text-gray-800">${user.address}</p>
                                </div>
                            ` : ''}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">নিবন্ধন তারিখ</label>
                                <p class="text-gray-800">${new Date(user.created_at).toLocaleString('bn-BD')}</p>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t">
                            <h3 class="font-bold text-gray-800 mb-4">স্ট্যাটিস্টিক্স</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-blue-50 rounded-lg p-4 text-center">
                                    <p class="text-2xl font-bold text-blue-600">${user.total_campaigns || 0}</p>
                                    <p class="text-sm text-gray-600">ক্যাম্পেইন</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4 text-center">
                                    <p class="text-2xl font-bold text-green-600">৳${parseFloat(user.total_spent || 0).toLocaleString('bn-BD')}</p>
                                    <p class="text-sm text-gray-600">মোট খরচ</p>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-4 text-center">
                                    <p class="text-2xl font-bold text-purple-600">${user.total_providers || 0}</p>
                                    <p class="text-sm text-gray-600">প্রোভাইডার</p>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.getElementById('userModal').classList.remove('hidden');
                    document.getElementById('userModal').classList.add('flex');
                }
            } catch (error) {
                showMessage('বিস্তারিত লোড করতে সমস্যা হয়েছে', 'error');
                console.error('Error:', error);
            }
        }
        
        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
            document.getElementById('userModal').classList.remove('flex');
        }
        
        async function toggleUserStatus(userId, currentStatus) {
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            const action = newStatus === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়';
            
            if (!confirm(`আপনি এই ইউজার ${action} করতে চান?`)) return;
            
            const formData = new FormData();
            formData.append('action', 'update_user_status');
            formData.append('user_id', userId);
            formData.append('status', newStatus);
            
            try {
                const res = await fetch('../api/admin.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    showMessage(data.message, 'success');
                    loadUsers();
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
        
        // Search on input
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                loadUsers();
            }
        });
        
        // Load users on page load
        loadUsers();
        
        // Auto-refresh every 60 seconds
        setInterval(loadUsers, 60000);
    </script>
</body>
</html>

