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
    <title>প্রোভাইডার ব্যবস্থাপনা - Admin</title>
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
                <a href="providers.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
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
            <h1 class="text-3xl font-bold text-gray-800">প্রোভাইডার ব্যবস্থাপনা</h1>
            <p class="text-gray-600 mt-2">সব প্রোভাইডার দেখুন এবং ব্যবস্থাপনা করুন</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">মোট প্রোভাইডার</p>
                <p class="text-3xl font-bold mt-2" id="totalProviders">0</p>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">অনুমোদিত</p>
                <p class="text-3xl font-bold mt-2" id="approvedProviders">0</p>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">অপেক্ষমান</p>
                <p class="text-3xl font-bold mt-2" id="pendingProviders">0</p>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">গড় রেটিং</p>
                <p class="text-3xl font-bold mt-2" id="avgRating">0.0</p>
            </div>
        </div>
        
        <!-- Search and Filter -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">খুঁজুন</label>
                    <input type="text" id="searchInput" placeholder="নাম, ইমেইল, বা কোম্পানি..." class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ক্যাটাগরি</label>
                    <select id="filterCategory" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <option value="">সব</option>
                        <option value="SEO">SEO</option>
                        <option value="Facebook Ads">Facebook Ads</option>
                        <option value="Google Ads">Google Ads</option>
                        <option value="Graphics">Graphics</option>
                        <option value="Web Dev">Web Dev</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                    <select id="filterStatus" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <option value="">সব</option>
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">সাজান</label>
                    <select id="sortBy" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <option value="rating">রেটিং অনুযায়ী</option>
                        <option value="newest">নতুন প্রথম</option>
                        <option value="oldest">পুরাতন প্রথম</option>
                        <option value="name">নাম অনুযায়ী</option>
                    </select>
                </div>
            </div>
            <button onclick="loadProviders()" class="mt-4 w-full md:w-auto bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition">
                <i class="fas fa-search mr-2"></i>খুঁজুন
            </button>
        </div>
        
        <!-- Providers List -->
        <div id="providersList" class="space-y-4">
            <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
        </div>
    </div>
    
    <!-- Provider Details Modal -->
    <div id="providerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 max-w-3xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">প্রোভাইডার বিস্তারিত</h2>
                <button onclick="closeProviderModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div id="providerDetails" class="space-y-4">
                <!-- Provider details will be loaded here -->
            </div>
        </div>
    </div>
    
    <!-- Message Toast -->
    <div id="messageToast" class="fixed top-4 right-4 hidden p-4 rounded-lg shadow-lg z-50 max-w-sm"></div>
    
    <script>
        async function loadProviders() {
            try {
                const res = await fetch('../api/admin.php?action=all_providers');
                const data = await res.json();
                
                if (data.success) {
                    let providers = data.data || [];
                    
                    // Apply filters
                    const search = document.getElementById('searchInput').value.toLowerCase();
                    const category = document.getElementById('filterCategory').value;
                    const status = document.getElementById('filterStatus').value;
                    const sortBy = document.getElementById('sortBy').value;
                    
                    if (search) {
                        providers = providers.filter(p => 
                            (p.name && p.name.toLowerCase().includes(search)) ||
                            (p.email && p.email.toLowerCase().includes(search)) ||
                            (p.company_name && p.company_name.toLowerCase().includes(search)) ||
                            (p.phone && p.phone.includes(search))
                        );
                    }
                    
                    if (category) {
                        providers = providers.filter(p => p.category === category);
                    }
                    
                    if (status) {
                        providers = providers.filter(p => p.status === status);
                    }
                    
                    // Sort
                    if (sortBy === 'rating') {
                        providers.sort((a, b) => parseFloat(b.rating || 0) - parseFloat(a.rating || 0));
                    } else if (sortBy === 'newest') {
                        providers.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                    } else if (sortBy === 'oldest') {
                        providers.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                    } else if (sortBy === 'name') {
                        providers.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
                    }
                    
                    // Update stats
                    const total = data.data.length;
                    const approved = data.data.filter(p => p.status === 'approved').length;
                    const pending = data.data.filter(p => p.status === 'pending').length;
                    const ratings = data.data.filter(p => p.rating > 0).map(p => parseFloat(p.rating));
                    const avgRating = ratings.length > 0 ? (ratings.reduce((a, b) => a + b, 0) / ratings.length).toFixed(2) : 0;
                    
                    document.getElementById('totalProviders').textContent = total;
                    document.getElementById('approvedProviders').textContent = approved;
                    document.getElementById('pendingProviders').textContent = pending;
                    document.getElementById('avgRating').textContent = avgRating;
                    
                    // Display providers
                    const div = document.getElementById('providersList');
                    if (providers.length > 0) {
                        div.innerHTML = providers.map(p => {
                            const statusColors = {
                                'approved': 'bg-green-100 text-green-800',
                                'pending': 'bg-yellow-100 text-yellow-800',
                                'rejected': 'bg-red-100 text-red-800'
                            };
                            
                            const statusLabels = {
                                'approved': 'Approved',
                                'pending': 'Pending',
                                'rejected': 'Rejected'
                            };
                            
                            return `
                                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start gap-4 flex-1">
                                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                                ${(p.name || 'P').charAt(0).toUpperCase()}
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-gray-800">${p.name || 'N/A'}</h3>
                                                <div class="mt-2 space-y-1">
                                                    <p class="text-sm text-gray-600">
                                                        <i class="fas fa-envelope mr-2 text-purple-500"></i>${p.email || 'N/A'}
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        <i class="fas fa-phone mr-2 text-blue-500"></i>${p.phone || 'N/A'}
                                                    </p>
                                                    ${p.company_name ? `
                                                        <p class="text-sm text-gray-600">
                                                            <i class="fas fa-building mr-2 text-green-500"></i>${p.company_name}
                                                        </p>
                                                    ` : ''}
                                                    <div class="flex items-center gap-4 mt-2">
                                                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                                            <i class="fas fa-tag mr-1"></i>${p.category || 'N/A'}
                                                        </span>
                                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                                                            <i class="fas fa-star mr-1"></i>${parseFloat(p.rating || 0).toFixed(1)} (${p.total_reviews || 0})
                                                        </span>
                                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                                            <i class="fas fa-briefcase mr-1"></i>${p.experience || 0} বছর
                                                        </span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-2">
                                                        <i class="fas fa-money-bill-wave mr-1"></i>৳${parseFloat(p.budget_min || 0).toLocaleString('bn-BD')} - ৳${parseFloat(p.budget_max || 0).toLocaleString('bn-BD')}
                                                    </p>
                                                    <p class="text-xs text-gray-500">
                                                        <i class="fas fa-clock mr-1"></i>নিবন্ধন: ${new Date(p.created_at).toLocaleString('bn-BD')}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end gap-2">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold ${statusColors[p.status] || 'bg-gray-100 text-gray-800'}">
                                                ${statusLabels[p.status] || p.status}
                                            </span>
                                            <div class="flex flex-col gap-2">
                                                <button onclick="viewProviderDetails(${p.id})" class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm">
                                                    <i class="fas fa-eye mr-1"></i>বিস্তারিত
                                                </button>
                                                ${p.status === 'pending' ? `
                                                    <button onclick="approveProvider(${p.id})" class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm">
                                                        <i class="fas fa-check mr-1"></i>Approve
                                                    </button>
                                                    <button onclick="rejectProvider(${p.id})" class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                                                        <i class="fas fa-times mr-1"></i>Reject
                                                    </button>
                                                ` : ''}
                                                ${p.status === 'approved' ? `
                                                    <button onclick="suspendProvider(${p.id})" class="px-3 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm">
                                                        <i class="fas fa-ban mr-1"></i>Suspend
                                                    </button>
                                                ` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        div.innerHTML = '<div class="text-center py-12"><i class="fas fa-briefcase text-gray-400 text-5xl mb-4"></i><p class="text-gray-500 text-lg">কোন প্রোভাইডার পাওয়া যায়নি</p></div>';
                    }
                } else {
                    document.getElementById('providersList').innerHTML = '<p class="text-red-500 text-center py-8">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                }
            } catch (error) {
                console.error('Error loading providers:', error);
                document.getElementById('providersList').innerHTML = '<p class="text-red-500 text-center py-8">একটি ত্রুটি হয়েছে</p>';
            }
        }
        
        async function viewProviderDetails(providerId) {
            try {
                const res = await fetch(`../api/admin.php?action=provider_details&provider_id=${providerId}`);
                const data = await res.json();
                
                if (data.success) {
                    const provider = data.data;
                    const div = document.getElementById('providerDetails');
                    
                    div.innerHTML = `
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">নাম</label>
                                <p class="text-gray-800 font-semibold">${provider.name || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ইমেইল</label>
                                <p class="text-gray-800 font-semibold">${provider.email || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ফোন</label>
                                <p class="text-gray-800 font-semibold">${provider.phone || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ক্যাটাগরি</label>
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold">${provider.category || 'N/A'}</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">স্ট্যাটাস</label>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold ${provider.status === 'approved' ? 'bg-green-100 text-green-800' : provider.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">
                                    ${provider.status || 'N/A'}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">রেটিং</label>
                                <p class="text-gray-800 font-semibold">
                                    <i class="fas fa-star text-yellow-500"></i> ${parseFloat(provider.rating || 0).toFixed(1)} (${provider.total_reviews || 0} reviews)
                                </p>
                            </div>
                            ${provider.company_name ? `
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">কোম্পানির নাম</label>
                                    <p class="text-gray-800 font-semibold">${provider.company_name}</p>
                                </div>
                            ` : ''}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">অভিজ্ঞতা</label>
                                <p class="text-gray-800 font-semibold">${provider.experience || 0} বছর</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">বাজেট</label>
                                <p class="text-gray-800 font-semibold">৳${parseFloat(provider.budget_min || 0).toLocaleString('bn-BD')} - ৳${parseFloat(provider.budget_max || 0).toLocaleString('bn-BD')}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">উপলব্ধতা</label>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold ${provider.availability === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${provider.availability || 'N/A'}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">নিবন্ধন তারিখ</label>
                                <p class="text-gray-800">${new Date(provider.created_at).toLocaleString('bn-BD')}</p>
                            </div>
                            ${provider.bio ? `
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">বায়ো</label>
                                    <p class="text-gray-800">${provider.bio}</p>
                                </div>
                            ` : ''}
                            ${provider.address ? `
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ঠিকানা</label>
                                    <p class="text-gray-800">${provider.address}</p>
                                </div>
                            ` : ''}
                        </div>
                        
                        <div class="mt-6 pt-t-6 border-t">
                            <h3 class="font-bold text-gray-800 mb-4">স্ট্যাটিস্টিক্স</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-blue-50 rounded-lg p-4 text-center">
                                    <p class="text-2xl font-bold text-blue-600">${provider.total_campaigns || 0}</p>
                                    <p class="text-sm text-gray-600">ক্যাম্পেইন</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4 text-center">
                                    <p class="text-2xl font-bold text-green-600">৳${parseFloat(provider.total_earnings || 0).toLocaleString('bn-BD')}</p>
                                    <p class="text-sm text-gray-600">মোট আয়</p>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-4 text-center">
                                    <p class="text-2xl font-bold text-purple-600">${provider.total_clients || 0}</p>
                                    <p class="text-sm text-gray-600">ক্লায়েন্ট</p>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.getElementById('providerModal').classList.remove('hidden');
                    document.getElementById('providerModal').classList.add('flex');
                }
            } catch (error) {
                showMessage('বিস্তারিত লোড করতে সমস্যা হয়েছে', 'error');
                console.error('Error:', error);
            }
        }
        
        function closeProviderModal() {
            document.getElementById('providerModal').classList.add('hidden');
            document.getElementById('providerModal').classList.remove('flex');
        }
        
        async function approveProvider(id) {
            if (!confirm('আপনি এই প্রোভাইডার অনুমোদন করতে চান?')) return;
            
            const formData = new FormData();
            formData.append('action', 'approve_provider_registration');
            formData.append('provider_id', id);
            
            try {
                const res = await fetch('../api/admin.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    showMessage(data.message, 'success');
                    loadProviders();
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি হয়েছে', 'error');
                console.error('Error:', error);
            }
        }
        
        async function rejectProvider(id) {
            if (!confirm('আপনি এই প্রোভাইডার প্রত্যাখ্যান করতে চান?')) return;
            
            const formData = new FormData();
            formData.append('action', 'reject_provider_registration');
            formData.append('provider_id', id);
            
            try {
                const res = await fetch('../api/admin.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    showMessage(data.message, 'success');
                    loadProviders();
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি হয়েছে', 'error');
                console.error('Error:', error);
            }
        }
        
        async function suspendProvider(id) {
            if (!confirm('আপনি এই প্রোভাইডার suspend করতে চান?')) return;
            
            const formData = new FormData();
            formData.append('action', 'suspend_provider');
            formData.append('provider_id', id);
            
            try {
                const res = await fetch('../api/admin.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    showMessage(data.message, 'success');
                    loadProviders();
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
                loadProviders();
            }
        });
        
        // Load providers on page load
        loadProviders();
        
        // Auto-refresh every 60 seconds
        setInterval(loadProviders, 60000);
    </script>
</body>
</html>

