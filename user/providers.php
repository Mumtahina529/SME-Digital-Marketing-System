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
    <title>প্রোভাইডার - <?php echo $userName; ?></title>
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
                <a href="dashboard.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-home mr-3"></i>ড্যাশবোর্ড
                </a>
                <a href="campaigns.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-bullhorn mr-3"></i>আমার ক্যাম্পেইন
                </a>
                <a href="providers.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-users mr-3"></i>আমার প্রোভাইডার
                </a>
                <a href="messages.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-comments mr-3"></i>মেসেজ
                </a>
                <a href="payments.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-credit-card mr-3"></i>পেমেন্ট
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
    
    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">প্রোভাইডার খুঁজুন</h1>
            <p class="text-gray-600 mt-2">আপনার ব্যবসার জন্য সঠিক প্রোভাইডার খুঁজে নিন</p>
        </div>
        

        
        <!-- My Providers -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">আমার প্রোভাইডার</h2>
            <div id="myProviders" class="space-y-4">
                <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
            </div>
        </div>
        
        <!-- Available Providers -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">উপলব্ধ প্রোভাইডার</h2>
            <div id="availableProviders" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <p class="text-gray-500 text-center py-8 col-span-full">লোড হচ্ছে...</p>
            </div>
        </div>
    </div>
    
    <script>
        async function loadMyProviders() {
            try {
                const res = await fetch('../api/providers.php?action=my_providers', { cache: 'no-store' });
                if (!res.ok) throw new Error('network');
                const data = await res.json();
                
                const div = document.getElementById('myProviders');
                if (!data.success) {
                    div.innerHTML = '<p class="text-red-500 text-center py-8">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                    return;
                }
                
                if (data.data.length > 0) {
                    div.innerHTML = data.data.map(p => `
                        <div class="border-2 ${p.request_status === 'approved' ? 'border-green-500' : p.request_status === 'pending' ? 'border-yellow-500' : 'border-red-500'} rounded-xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">${p.name}</h3>
                                    <p class="text-gray-600">${p.company_name || ''}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold ${p.request_status === 'approved' ? 'bg-green-100 text-green-800' : p.request_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">${p.request_status === 'approved' ? 'অনুমোদিত' : p.request_status === 'pending' ? 'অপেক্ষমান' : 'প্রত্যাখ্যান'}</span>
                            </div>
                            <div class="space-y-2 mb-4">
                                <p class="text-sm"><i class="fas fa-tag mr-2 text-purple-500"></i><strong>ক্যাটাগরি:</strong> ${p.category}</p>
                                <p class="text-sm"><i class="fas fa-star mr-2 text-yellow-500"></i><strong>রেটিং:</strong> ${p.rating} (${p.total_reviews} রিভিউ)</p>
                                <p class="text-sm"><i class="fas fa-briefcase mr-2 text-blue-500"></i><strong>অভিজ্ঞতা:</strong> ${p.experience} বছর</p>
                                <p class="text-sm"><i class="fas fa-money-bill-wave mr-2 text-green-500"></i><strong>বাজেট:</strong> ৳${p.budget_min} - ৳${p.budget_max}</p>
                            </div>
                            ${p.request_status === 'approved' && !p.chat_locked ? 
                                `<a href="messages.php?provider_id=${encodeURIComponent(p.id)}" class="inline-block bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                                    <i class="fas fa-comments mr-2"></i>চ্যাট করুন
                                </a>` : 
                                p.chat_locked ? 
                                `<p class="text-yellow-600"><i class="fas fa-lock mr-2"></i>চ্যাট লক করা আছে</p>` :
                                ''
                            }
                        </div>
                    `).join('');
                } else {
                    div.innerHTML = '<p class="text-gray-500 text-center py-8">আপনার এখনও কোন প্রোভাইডার নেই</p>';
                }
            } catch (error) {
                console.error('Error loading my providers:', error);
                document.getElementById('myProviders').innerHTML = '<p class="text-red-500 text-center py-8">একটি ত্রুটি হয়েছে</p>';
            }
        }
        
        async function searchProviders() {
            try {
                const params = new URLSearchParams();
                params.append('action', 'search');
                
                const res = await fetch('../api/providers.php?' + params.toString(), { cache: 'no-store' });
                if (!res.ok) {
                    throw new Error('Network');
                }
                let data;
                try {
                    data = await res.json();
                } catch (parseErr) {
                    throw new Error('Parse');
                }
                
                const div = document.getElementById('availableProviders');
                if (!data.success) {
                    div.innerHTML = '<p class="text-red-500 text-center py-8 col-span-full">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                    return;
                }
                
                if (data.data.length > 0) {
                    div.innerHTML = data.data.map(p => `
                        <div class="border-2 border-gray-200 rounded-xl p-6 hover:shadow-lg transition ${p.already_requested ? 'opacity-50' : ''}">
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-gray-800">${p.name}</h3>
                                <p class="text-gray-600">${p.company_name}</p>
                            </div>
                            <div class="space-y-2 mb-4">
                                <p class="text-sm"><i class="fas fa-tag mr-2 text-purple-500"></i>${p.category}</p>
                                <p class="text-sm"><i class="fas fa-star mr-2 text-yellow-500"></i>${p.rating} (${p.total_reviews})</p>
                                <p class="text-sm"><i class="fas fa-briefcase mr-2 text-blue-500"></i>${p.experience} বছর</p>
                                <p class="text-sm"><i class="fas fa-money-bill-wave mr-2 text-green-500"></i>৳${p.budget_min} - ৳${p.budget_max}</p>
                            </div>
                            ${p.already_requested ? 
                                '<p class="text-red-600 text-sm"><i class="fas fa-check-circle mr-2"></i>ইতিমধ্যে অনুরোধ করা হয়েছে</p>' :
                                `<button onclick="requestProvider(${p.id})" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-2 rounded-lg hover:from-purple-700 hover:to-pink-700 transition">
                                    <i class="fas fa-handshake mr-2"></i>অনুরোধ করুন
                                </button>`
                            }
                        </div>
                    `).join('');
                } else {
                    div.innerHTML = '<p class="text-gray-500 text-center py-8 col-span-full">কোন প্রোভাইডার পাওয়া যায়নি</p>';
                }
            } catch (error) {
                console.error('Error searching providers:', error);
                document.getElementById('availableProviders').innerHTML = '<p class="text-red-500 text-center py-8 col-span-full">একটি ত্রুটি হয়েছে (নেটওয়ার্ক/সেশন)</p>';
            }
        }
        
        async function requestProvider(providerId) {
            if (!confirm('আপনি এই প্রোভাইডারকে অনুরোধ করতে চান?')) return;
            
            const formData = new FormData();
            formData.append('action', 'request');
            formData.append('provider_id', providerId);
            
            try {
                const res = await fetch('../api/providers.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    alert(data.message);
                    loadMyProviders();
                    searchProviders();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error requesting provider:', error);
                alert('একটি ত্রুটি হয়েছে');
            }
        }
        
        async function logout() {
            const res = await fetch('../api/auth.php?action=logout', { method: 'POST' });
            const data = await res.json();
            if (data.success) window.location.href = data.redirect;
        }
        


        loadMyProviders();
        searchProviders();
    </script>
</body>
</html>

