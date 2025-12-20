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
    <title>সাপোর্ট টিকেট - Admin</title>
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
                <a href="payouts.php" class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-money-bill-wave mr-3"></i>Payouts
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
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">সাপোর্ট টিকেট ব্যবস্থাপনা</h1>
            <p class="text-gray-600 mt-2">সব সাপোর্ট টিকেট দেখুন এবং উত্তর দিন</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">মোট টিকেট</p>
                <p class="text-3xl font-bold mt-2" id="totalTickets">0</p>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">খোলা</p>
                <p class="text-3xl font-bold mt-2" id="openTickets">0</p>
            </div>
            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">চলছে</p>
                <p class="text-3xl font-bold mt-2" id="inProgressTickets">0</p>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">সমাধান হয়েছে</p>
                <p class="text-3xl font-bold mt-2" id="resolvedTickets">0</p>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                    <select id="filterStatus" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <option value="">সব</option>
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ইউজার টাইপ</label>
                    <select id="filterUserType" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <option value="">সব</option>
                        <option value="user">User</option>
                        <option value="provider">Provider</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">তারিখ</label>
                    <input type="date" id="filterDate" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                </div>
            </div>
            <button onclick="loadTickets()" class="mt-4 w-full md:w-auto bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition">
                <i class="fas fa-filter mr-2"></i>ফিল্টার করুন
            </button>
        </div>
        
        <!-- Tickets List -->
        <div id="ticketsList" class="space-y-4">
            <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
        </div>
    </div>
    
    <!-- Response Modal -->
    <div id="responseModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">টিকেট উত্তর দিন</h2>
                <button onclick="closeResponseModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div id="ticketInfo" class="mb-6 p-4 bg-gray-50 rounded-lg">
                <!-- Ticket info will be loaded here -->
            </div>
            
            <form id="responseForm" class="space-y-4">
                <input type="hidden" id="ticketId">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস</label>
                    <select id="ticketStatus" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">উত্তর</label>
                    <textarea id="adminResponse" rows="6" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-green-500 to-teal-500 text-white py-3 rounded-lg font-semibold hover:from-green-600 hover:to-teal-600 transition">
                        <i class="fas fa-paper-plane mr-2"></i>উত্তর পাঠান
                    </button>
                    <button type="button" onclick="closeResponseModal()" class="flex-1 bg-gray-500 text-white py-3 rounded-lg font-semibold hover:bg-gray-600 transition">
                        <i class="fas fa-times mr-2"></i>বাতিল
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Message Toast -->
    <div id="messageToast" class="fixed top-4 right-4 hidden p-4 rounded-lg shadow-lg z-50 max-w-sm"></div>
    
    <script>
        async function loadTickets() {
            try {
                const res = await fetch('../api/support.php?action=admin_tickets');
                const data = await res.json();
                
                if (data.success) {
                    let tickets = data.data || [];
                    
                    // Apply filters
                    const status = document.getElementById('filterStatus').value;
                    const userType = document.getElementById('filterUserType').value;
                    const date = document.getElementById('filterDate').value;
                    
                    if (status) {
                        tickets = tickets.filter(t => t.status === status);
                    }
                    
                    if (userType) {
                        tickets = tickets.filter(t => t.user_type === userType);
                    }
                    
                    if (date) {
                        tickets = tickets.filter(t => t.created_at.startsWith(date));
                    }
                    
                    // Update stats
                    const total = data.data.length;
                    const open = data.data.filter(t => t.status === 'open').length;
                    const inProgress = data.data.filter(t => t.status === 'in_progress').length;
                    const resolved = data.data.filter(t => t.status === 'resolved' || t.status === 'closed').length;
                    
                    document.getElementById('totalTickets').textContent = total;
                    document.getElementById('openTickets').textContent = open;
                    document.getElementById('inProgressTickets').textContent = inProgress;
                    document.getElementById('resolvedTickets').textContent = resolved;
                    
                    // Display tickets
                    const div = document.getElementById('ticketsList');
                    if (tickets.length > 0) {
                        div.innerHTML = tickets.map(t => {
                            const statusColors = {
                                'open': 'bg-yellow-100 text-yellow-800',
                                'in_progress': 'bg-blue-100 text-blue-800',
                                'resolved': 'bg-green-100 text-green-800',
                                'closed': 'bg-gray-100 text-gray-800'
                            };
                            
                            const statusLabels = {
                                'open': 'Open',
                                'in_progress': 'In Progress',
                                'resolved': 'Resolved',
                                'closed': 'Closed'
                            };
                            
                            return `
                                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-start gap-4 mb-3">
                                                <div class="w-12 h-12 ${t.user_type === 'user' ? 'bg-gradient-to-r from-purple-500 to-pink-500' : 'bg-gradient-to-r from-blue-500 to-cyan-500'} rounded-full flex items-center justify-center text-white font-bold">
                                                    ${(t.user_name || 'U').charAt(0).toUpperCase()}
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <h3 class="text-lg font-bold text-gray-800">${t.user_name || 'Unknown'}</h3>
                                                        <span class="px-2 py-1 ${t.user_type === 'user' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'} rounded-full text-xs font-semibold">
                                                            ${t.user_type === 'user' ? 'User' : 'Provider'}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-600">${t.user_email || ''}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <h4 class="font-semibold text-gray-800 mb-2">${t.subject || 'No Subject'}</h4>
                                                <p class="text-gray-700 bg-gray-50 rounded-lg p-3">${t.message || 'No message'}</p>
                                            </div>
                                            
                                            ${t.admin_response ? `
                                                <div class="mb-3 bg-blue-50 rounded-lg p-3 border-l-4 border-blue-500">
                                                    <p class="text-sm font-semibold text-gray-800 mb-1">Admin Response:</p>
                                                    <p class="text-gray-700">${t.admin_response}</p>
                                                </div>
                                            ` : '<p class="text-yellow-600 text-sm mb-3"><i class="fas fa-exclamation-circle mr-1"></i>এখনও উত্তর দেওয়া হয়নি</p>'}
                                            
                                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                                <span><i class="fas fa-clock mr-1"></i>${new Date(t.created_at).toLocaleString('bn-BD')}</span>
                                                ${t.updated_at !== t.created_at ? `<span><i class="fas fa-edit mr-1"></i>Updated: ${new Date(t.updated_at).toLocaleString('bn-BD')}</span>` : ''}
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-col items-end gap-2 ml-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold ${statusColors[t.status] || 'bg-gray-100 text-gray-800'}">
                                                ${statusLabels[t.status] || t.status}
                                            </span>
                                            <button onclick="openResponseModal(${t.id})" class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition text-sm font-semibold">
                                                <i class="fas fa-reply mr-1"></i>${t.admin_response ? 'Update' : 'Reply'}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        div.innerHTML = '<div class="text-center py-12"><i class="fas fa-headset text-gray-400 text-5xl mb-4"></i><p class="text-gray-500 text-lg">কোন টিকেট পাওয়া যায়নি</p></div>';
                    }
                } else {
                    document.getElementById('ticketsList').innerHTML = '<p class="text-red-500 text-center py-8">ডাটা লোড করতে সমস্যা হয়েছে</p>';
                }
            } catch (error) {
                console.error('Error loading tickets:', error);
                document.getElementById('ticketsList').innerHTML = '<p class="text-red-500 text-center py-8">একটি ত্রুটি হয়েছে</p>';
            }
        }
        
        async function openResponseModal(ticketId) {
            try {
                const res = await fetch(`../api/support.php?action=admin_tickets`);
                const data = await res.json();
                
                if (data.success) {
                    const ticket = data.data.find(t => t.id == ticketId);
                    if (ticket) {
                        document.getElementById('ticketId').value = ticketId;
                        document.getElementById('ticketStatus').value = ticket.status === 'open' ? 'in_progress' : ticket.status;
                        document.getElementById('adminResponse').value = ticket.admin_response || '';
                        
                        document.getElementById('ticketInfo').innerHTML = `
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-800">From:</span>
                                    <span>${ticket.user_name || 'Unknown'}</span>
                                    <span class="px-2 py-1 ${ticket.user_type === 'user' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'} rounded-full text-xs">
                                        ${ticket.user_type === 'user' ? 'User' : 'Provider'}
                                    </span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-800">Subject:</span>
                                    <span>${ticket.subject || 'No Subject'}</span>
                                </div>
                                <div class="bg-gray-100 rounded-lg p-3 mt-2">
                                    <p class="text-sm text-gray-700">${ticket.message || 'No message'}</p>
                                </div>
                            </div>
                        `;
                        
                        document.getElementById('responseModal').classList.remove('hidden');
                        document.getElementById('responseModal').classList.add('flex');
                    }
                }
            } catch (error) {
                showMessage('টিকেট লোড করতে সমস্যা হয়েছে', 'error');
                console.error('Error:', error);
            }
        }
        
        function closeResponseModal() {
            document.getElementById('responseModal').classList.add('hidden');
            document.getElementById('responseModal').classList.remove('flex');
        }
        
        document.getElementById('responseForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('action', 'respond');
            formData.append('ticket_id', document.getElementById('ticketId').value);
            formData.append('response', document.getElementById('adminResponse').value);
            formData.append('status', document.getElementById('ticketStatus').value);
            
            try {
                const res = await fetch('../api/support.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    showMessage(data.message, 'success');
                    closeResponseModal();
                    loadTickets();
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি হয়েছে', 'error');
                console.error('Error:', error);
            }
        });
        
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
        
        // Load tickets on page load
        loadTickets();
        
        // Auto-refresh every 60 seconds
        setInterval(loadTickets, 60000);
    </script>
</body>
</html>

