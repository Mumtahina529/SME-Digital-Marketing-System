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
    <title>সাপোর্ট - <?php echo $userName; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap');
        body { font-family: 'Noto Sans Bengali', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
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
                <a href="providers.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
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
                <a href="support.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-headset mr-3"></i>সাপোর্ট
                </a>
                <a href="#" onclick="logout()" class="flex items-center px-4 py-3 hover:bg-red-500 rounded-lg transition mt-4">
                    <i class="fas fa-sign-out-alt mr-3"></i>লগআউট
                </a>
            </nav>
        </div>
    </div>
    
    <div class="ml-64 p-8">
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-800">সাপোর্ট টিকেট</h1>
            <button onclick="showCreateModal()" class="bg-gradient-to-r from-green-500 to-teal-500 text-white px-6 py-3 rounded-lg font-semibold hover:from-green-600 hover:to-teal-600 transition">
                <i class="fas fa-plus mr-2"></i>নতুন টিকেট
            </button>
        </div>
        
        <div id="ticketsList" class="space-y-4">
            <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
        </div>
    </div>
    
    <!-- Create Ticket Modal -->
    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">নতুন টিকেট তৈরি করুন</h2>
            <form id="ticketForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বিষয়</label>
                    <input type="text" name="subject" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">মেসেজ</label>
                    <textarea name="message" rows="5" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg"></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-green-500 to-teal-500 text-white py-3 rounded-lg font-semibold">তৈরি করুন</button>
                    <button type="button" onclick="hideCreateModal()" class="flex-1 bg-gray-500 text-white py-3 rounded-lg font-semibold">বাতিল</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function showCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
            document.getElementById('createModal').classList.add('flex');
        }
        
        function hideCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.getElementById('createModal').classList.remove('flex');
        }
        
        async function loadTickets() {
            try {
                const res = await fetch('../api/support.php?action=my_tickets', { cache: 'no-store' });
                if (!res.ok) throw new Error('network');
                const data = await res.json();
                
                const div = document.getElementById('ticketsList');
                if (!data.success) {
                    div.innerHTML = '<p class="text-red-500 text-center py-8">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                    return;
                }
                
                if (data.data.length > 0) {
                    div.innerHTML = data.data.map(t => `
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-800">${t.subject}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold ${t.status === 'resolved' ? 'bg-green-100 text-green-800' : t.status === 'in_progress' ? 'bg-blue-100 text-blue-800' : t.status === 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800'}">${t.status}</span>
                            </div>
                            <p class="text-gray-600 mb-4">${t.message}</p>
                            ${t.admin_response ? `
                                <div class="bg-blue-50 rounded-lg p-4 mb-4">
                                    <p class="font-semibold text-gray-800 mb-2">অ্যাডমিন উত্তর:</p>
                                    <p class="text-gray-700">${t.admin_response}</p>
                                </div>
                            ` : '<p class="text-gray-500 text-sm">এখনও উত্তর দেওয়া হয়নি</p>'}
                            <p class="text-sm text-gray-500">${new Date(t.created_at).toLocaleString('bn-BD')}</p>
                        </div>
                    `).join('');
                } else {
                    div.innerHTML = '<p class="text-gray-500 text-center py-8">কোন টিকেট নেই</p>';
                }
            } catch (error) {
                console.error('Error loading tickets:', error);
                document.getElementById('ticketsList').innerHTML = '<p class="text-red-500 text-center py-8">একটি ত্রুটি হয়েছে (নেটওয়ার্ক/সেশন)</p>';
            }
        }
        
        document.getElementById('ticketForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.append('action', 'create');
            
            try {
                const res = await fetch('../api/support.php', {
                    method: 'POST',
                    body: formData
                });
                if (!res.ok) throw new Error('network');
                const data = await res.json();
                
                if (data.success) {
                    alert(data.message);
                    hideCreateModal();
                    loadTickets();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error creating ticket:', error);
                alert('একটি ত্রুটি হয়েছে (নেটওয়ার্ক/সেশন)');
            }
        });
        
        async function logout() {
            const res = await fetch('../api/auth.php?action=logout', { method: 'POST' });
            const data = await res.json();
            if (data.success) window.location.href = data.redirect;
        }
        
        loadTickets();
    </script>
</body>
</html>

