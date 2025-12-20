<?php
require_once '../config/config.php';
requireLogin('provider');
$providerId = $_SESSION['provider_id'];
?>
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payout - Provider</title>
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
    <div class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-blue-600 to-cyan-700 text-white shadow-xl">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-8"><i class="fas fa-briefcase mr-2"></i>Provider</h1>
            <nav class="space-y-2">
                <a href="dashboard.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-home mr-3"></i>ড্যাশবোর্ড
                </a>
                <a href="campaigns.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-bullhorn mr-3"></i>ক্যাম্পেইন
                </a>
                <a href="messages.php"
                    class="flex items-center px-4 py-3 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                    <i class="fas fa-comments mr-3"></i>মেসেজ
                </a>
                <a href="payouts.php" class="flex items-center px-4 py-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-money-bill-wave mr-3"></i>Payout
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

    <div class="ml-64 p-8">
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-800">Payout Requests</h1>
            <button onclick="showRequestModal()"
                class="bg-gradient-to-r from-green-500 to-teal-500 text-white px-6 py-3 rounded-lg font-semibold">
                <i class="fas fa-plus mr-2"></i>নতুন Request
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Available Balance</h2>
            <p class="text-3xl font-bold text-green-600" id="availableBalance">৳0</p>
        </div>

        <div id="payoutsList" class="space-y-4">
            <p class="text-gray-500 text-center py-8">লোড হচ্ছে...</p>
        </div>
    </div>

    <!-- Request Modal -->
    <div id="requestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Payout Request</h2>
            <form id="payoutForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount (৳)</label>
                    <input type="number" name="amount" required min="0"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select name="payment_method" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                        <option value="bkash">Bkash</option>
                        <option value="nagad">Nagad</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Details</label>
                    <textarea name="account_details" rows="3" required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg"></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-green-500 to-teal-500 text-white py-3 rounded-lg font-semibold">Request</button>
                    <button type="button" onclick="hideRequestModal()"
                        class="flex-1 bg-gray-500 text-white py-3 rounded-lg font-semibold">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRequestModal() {
            document.getElementById('requestModal').classList.remove('hidden');
            document.getElementById('requestModal').classList.add('flex');
        }

        function hideRequestModal() {
            document.getElementById('requestModal').classList.add('hidden');
            document.getElementById('requestModal').classList.remove('flex');
        }

        document.getElementById('payoutForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.append('action', 'request_payout');

            const res = await fetch('../api/payments.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                alert(data.message);
                hideRequestModal();
                loadPayouts();
            } else {
                alert(data.message);
            }
        });

        async function loadPayouts() {
            // This would need a new API endpoint to get payout requests
            document.getElementById('payoutsList').innerHTML = '<p class="text-gray-500 text-center py-8">Payout requests will be shown here</p>';
        }

        async function logout() {
            const res = await fetch('../api/auth.php?action=logout', { method: 'POST' });
            const data = await res.json();
            if (data.success) window.location.href = data.redirect;
        }

        loadPayouts();
    </script>
</body>

</html>