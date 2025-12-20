<?php
require_once '../config/config.php';
requireLogin('provider');
$providerId = $_SESSION['provider_id'];
$userId = $_GET['user_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>মেসেজ - Provider</title>
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
                <a href="campaigns.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-bullhorn mr-3"></i>ক্যাম্পেইন
                </a>
                <a href="messages.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
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
    
    <div class="ml-64 flex h-screen">
        <div class="w-1/3 border-r bg-white">
            <div class="p-4 border-b">
                <h2 class="text-xl font-bold text-gray-800">কনভারসেশন</h2>
            </div>
            <div id="conversationsList" class="overflow-y-auto h-full">
                <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
            </div>
        </div>
        
        <div class="flex-1 flex flex-col">
            <div id="chatHeader" class="p-4 border-b bg-white">
                <p class="text-gray-500 text-center">একটি কনভারসেশন নির্বাচন করুন</p>
            </div>
            <div id="messagesArea" class="flex-1 overflow-y-auto p-4 bg-gray-50"></div>
            <div id="messageInputArea" class="p-4 border-t bg-white hidden">
                <form id="messageForm" class="flex gap-2">
                    <input type="file" id="attachmentInput" class="hidden" accept="image/*,application/pdf">
                    <button type="button" onclick="document.getElementById('attachmentInput').click()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        <i class="fas fa-paperclip"></i>
                    </button>
                    <input type="text" id="messageInput" placeholder="মেসেজ লিখুন..." class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg">
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-lg">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        let currentUserId = null;

        function showMessage(text, type = 'success') {
            alert(text); // simple fallback
        }

        async function loadConversations() {
            try {
                const res = await fetch('../api/messages.php?action=conversations_list');
                const data = await res.json();
                
                const div = document.getElementById('conversationsList');
                if (!data.success) {
                    div.innerHTML = '<p class="text-red-500 text-center py-8">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                    return;
                }

                const conversations = data.data || [];
                if (conversations.length > 0) {
                    div.innerHTML = conversations.map(c => `
                        <div onclick="openConversation(${c.id})" class="p-4 border-b hover:bg-gray-50 cursor-pointer ${currentUserId == c.id ? 'bg-blue-50' : ''}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-800">${c.name}</h3>
                                    <p class="text-sm text-gray-600">${c.company_name || ''}</p>
                                </div>
                                ${c.unread_count > 0 ? `<span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">${c.unread_count}</span>` : ''}
                            </div>
                            ${c.last_message ? `<p class="text-sm text-gray-500 mt-2 truncate">${c.last_message}</p>` : ''}
                            ${c.is_locked ? '<p class="text-xs text-yellow-600 mt-1"><i class="fas fa-lock"></i> চ্যাট লক</p>' : ''}
                        </div>
                    `).join('');
                } else {
                    div.innerHTML = '<div class="text-center py-12"><i class="fas fa-comments text-gray-400 text-4xl mb-2"></i><p class="text-gray-500">কোন কনভারসেশন নেই</p></div>';
                }
            } catch (error) {
                console.error('Error loading conversations:', error);
                document.getElementById('conversationsList').innerHTML = '<p class="text-red-500 text-center py-8">একটি ত্রুটি হয়েছে</p>';
            }
        }
        
        async function openConversation(userId) {
            currentUserId = userId;
            loadConversations();
            loadMessages(userId);
            
            try {
                const res = await fetch(`../api/messages.php?action=conversations_list`);
                const data = await res.json();
                if (data.success) {
                    const user = data.data.find(u => u.id == userId);
                    if (user && !user.is_locked) {
                        document.getElementById('chatHeader').innerHTML = `
                            <h3 class="font-bold text-gray-800">${user.name}</h3>
                            <p class="text-sm text-gray-600">${user.company_name || ''}</p>
                        `;
                        document.getElementById('messageInputArea').classList.remove('hidden');
                    } else {
                        document.getElementById('chatHeader').innerHTML = `
                            <h3 class="font-bold text-gray-800">${user ? user.name : 'Unknown'}</h3>
                            <p class="text-sm text-yellow-600"><i class="fas fa-lock mr-2"></i>চ্যাট লক করা আছে</p>
                        `;
                        document.getElementById('messageInputArea').classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error loading chat header:', error);
            }
        }
        
        async function loadMessages(userId) {
            try {
                const res = await fetch(`../api/messages.php?action=get_conversation&user_id=${userId}`);
                const data = await res.json();
                
                if (data.success) {
                    const div = document.getElementById('messagesArea');
                    div.innerHTML = data.data.map(m => {
                        const isMe = m.sender_type === 'provider';
                        return `
                            <div class="flex ${isMe ? 'justify-end' : 'justify-start'} mb-4">
                                <div class="max-w-md ${isMe ? 'bg-blue-500 text-white' : 'bg-white'} rounded-lg p-4 shadow">
                                    <p>${m.message}</p>
                                    ${m.attachment ? `<a href="../uploads/messages/${m.attachment}" target="_blank" class="text-sm underline mt-2 block">Attachment</a>` : ''}
                                    <p class="text-xs ${isMe ? 'text-blue-100' : 'text-gray-500'} mt-2">${new Date(m.created_at).toLocaleString('bn-BD')}</p>
                                </div>
                            </div>
                        `;
                    }).join('');
                    div.scrollTop = div.scrollHeight;
                }
            } catch (error) {
                console.error('Error loading messages:', error);
                document.getElementById('messagesArea').innerHTML = '<p class="text-red-500 text-center py-8">একটি ত্রুটি হয়েছে</p>';
            }
        }
        
        document.getElementById('messageForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!currentUserId) return;
            
            const formData = new FormData();
            formData.append('action', 'send');
            formData.append('user_id', currentUserId);
            formData.append('message', document.getElementById('messageInput').value);
            
            const file = document.getElementById('attachmentInput').files[0];
            if (file) formData.append('attachment', file);
            
            try {
                const res = await fetch('../api/messages.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    document.getElementById('messageInput').value = '';
                    document.getElementById('attachmentInput').value = '';
                    loadMessages(currentUserId);
                    loadConversations();
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি হয়েছে', 'error');
                console.error('Error sending message:', error);
            }
        });
        
        async function logout() {
            if (!confirm('আপনি লগআউট করতে চান?')) return;
            const res = await fetch('../api/auth.php?action=logout', { method: 'POST' });
            const data = await res.json();
            if (data.success) window.location.href = data.redirect;
        }
        
        <?php if ($userId): ?>
        openConversation(<?php echo $userId; ?>);
        <?php endif; ?>
        
        loadConversations();
        setInterval(() => {
            if (currentUserId) {
                loadMessages(currentUserId);
                loadConversations();
            }
        }, 5000);
    </script>
</body>
</html>

