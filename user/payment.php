<?php
require_once '../config/config.php';
requireLogin('user');
$userId = $_SESSION['user_id'];
$campaignId = $_GET['campaign_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>পেমেন্ট করুন</title>
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
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">পেমেন্ট করুন</h1>

            <form id="paymentForm" class="space-y-4">
                <input type="hidden" name="campaign_id" value="<?php echo $campaignId; ?>">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount (৳)</label>
                    <input type="number" name="amount" required min="0" step="0.01"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">পেমেন্ট মেথড</label>
                    <select name="payment_method" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                        <option value="">নির্বাচন করুন</option>
                        <option value="card">Card</option>
                        <option value="bkash">Bkash</option>
                        <option value="nagad">Nagad</option>
                        <option value="cod">COD</option>
                    </select>
                </div>

                <div id="transactionIdField" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction ID</label>
                    <input type="text" name="transaction_id"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-green-500 to-teal-500 text-white py-3 rounded-lg font-semibold hover:from-green-600 hover:to-teal-600 transition">
                    <i class="fas fa-credit-card mr-2"></i>পেমেন্ট করুন
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="campaigns.php" class="text-gray-600 hover:underline">বাতিল করুন</a>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('select[name="payment_method"]').addEventListener('change', function () {
            const transactionField = document.getElementById('transactionIdField');
            if (this.value === 'bkash' || this.value === 'nagad' || this.value === 'card') {
                transactionField.style.display = 'block';
            } else {
                transactionField.style.display = 'none';
            }
        });

        document.getElementById('paymentForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.append('action', 'create');

            const res = await fetch('../api/payments.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                alert(data.message);
                window.location.href = 'payments.php';
            } else {
                alert(data.message);
            }
        });
    </script>
</body>

</html>