<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="লগইন করুন - SME Digital Marketing Platform">
    <title>লগইন - SME Digital Marketing Platform</title>
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .login-bg {
            min-height: 100vh;
            background: var(--gradient-aurora);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .login-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }
        
        .login-container {
            max-width: 1100px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            position: relative;
            z-index: 1;
        }
        
        .login-info {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-2xl);
            padding: 3rem;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-card {
            animation: fadeInRight 0.8s ease-out;
        }
        
        .login-info {
            animation: fadeInLeft 0.8s ease-out;
        }
        
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .feature-item {
            display: flex;
            align-items: start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }
            
            .login-info {
                display: none;
            }
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite ease-in-out;
        }
        
        .shape:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 200px;
            height: 200px;
            bottom: -100px;
            right: -100px;
            animation-delay: 5s;
        }
        
        .shape:nth-child(3) {
            width: 150px;
            height: 150px;
            top: 50%;
            right: 10%;
            animation-delay: 10s;
        }
        
        .user-type-btn {
            flex: 1;
            padding: 1rem;
            border: 2px solid var(--gray-300);
            background: white;
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .user-type-btn:hover {
            border-color: var(--primary-500);
            background: var(--primary-50);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .user-type-btn.active {
            border-color: var(--primary-600);
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-lg);
        }
        
        .user-type-btn i {
            font-size: 2rem;
        }
        
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: var(--gray-500);
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--gray-300);
        }
        
        .divider span {
            padding: 0 1rem;
            font-size: var(--text-sm);
            font-weight: 600;
        }
    </style>
</head>
<body class="bengali">
    <div class="login-bg">
        <!-- Floating Shapes -->
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        
        <div class="login-container">
            <!-- Login Info -->
            <div class="login-info">
                <div style="margin-bottom: 3rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                        <div style="width: 60px; height: 60px; background: white; border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-rocket" style="color: var(--primary-600); font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h1 style="font-size: var(--text-2xl); font-weight: 800; color: white; margin: 0;">SME Digital</h1>
                            <p style="font-size: var(--text-sm); opacity: 0.9; margin: 0;">Marketing Platform</p>
                        </div>
                    </div>
                    <h2 style="font-size: var(--text-3xl); font-weight: 800; margin-bottom: 1rem; color: white;">স্বাগতম!</h2>
                    <p style="font-size: var(--text-lg); opacity: 0.9; line-height: 1.6;">আপনার ব্যবসার ডিজিটাল সাফল্যের জন্য আমরা প্রস্তুত</p>
                </div>
                
                <div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h4 style="font-weight: 700; margin-bottom: 0.25rem;">৫০+ অভিজ্ঞ প্রোভাইডার</h4>
                            <p style="opacity: 0.9; font-size: var(--text-sm);">বিভিন্ন ক্যাটাগরিতে যাচাইকৃত এক্সপার্ট</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h4 style="font-weight: 700; margin-bottom: 0.25rem;">সম্পূর্ণ নিরাপদ</h4>
                            <p style="opacity: 0.9; font-size: var(--text-sm);">পেমেন্ট এবং ডাটা সুরক্ষিত</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div>
                            <h4 style="font-weight: 700; margin-bottom: 0.25rem;">২৪/৭ সাপোর্ট</h4>
                            <p style="opacity: 0.9; font-size: var(--text-sm);">সর্বদা আপনার সেবায় প্রস্তুত</p>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.2);">
                    <p style="opacity: 0.8; font-size: var(--text-sm); margin-bottom: 0.5rem;">এখনও অ্যাকাউন্ট নেই?</p>
                    <a href="register.php" style="display: inline-flex; align-items: center; gap: 0.5rem; color: white; font-weight: 600; text-decoration: none; background: rgba(255, 255, 255, 0.2); padding: 0.75rem 1.5rem; border-radius: var(--radius-lg); transition: all 0.3s;">
                        <i class="fas fa-user-plus"></i>
                        নিবন্ধন করুন
                    </a>
                </div>
            </div>
            
            <!-- Login Card -->
            <div class="card login-card" style="padding: 3rem;">
                <!-- Back to Home -->
                <div style="margin-bottom: 2rem;">
                    <a href="home.php" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--primary-600); text-decoration: none; font-weight: 600; transition: all 0.3s;">
                        <i class="fas fa-arrow-left"></i>হোমে ফিরে যান
                    </a>
                </div>
                
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <h2 style="font-size: var(--text-3xl); font-weight: 800; margin-bottom: 0.5rem; color: var(--gray-900);">লগইন করুন</h2>
                    <p style="color: var(--gray-600);">আপনার অ্যাকাউন্টে প্রবেশ করুন</p>
                </div>
                
                <form id="loginForm">
                    <!-- User Type Selection -->
                    <div style="margin-bottom: 2rem;">
                        <label class="form-label" style="text-align: center; display: block; margin-bottom: 1rem;">আমি হচ্ছি</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem;">
                            <div class="user-type-btn active" onclick="selectUserType(this, 'user')">
                                <i class="fas fa-user"></i>
                                <span style="font-size: var(--text-sm); font-weight: 600;">কাস্টমার</span>
                            </div>
                            <div class="user-type-btn" onclick="selectUserType(this, 'provider')">
                                <i class="fas fa-briefcase"></i>
                                <span style="font-size: var(--text-sm); font-weight: 600;">প্রোভাইডার</span>
                            </div>
                            <div class="user-type-btn" onclick="selectUserType(this, 'admin')">
                                <i class="fas fa-user-shield"></i>
                                <span style="font-size: var(--text-sm); font-weight: 600;">অ্যাডমিন</span>
                            </div>
                        </div>
                        <input type="hidden" name="user_type" id="user_type" value="user">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope" style="margin-right: 0.5rem; color: var(--primary-600);"></i>ইমেইল
                        </label>
                        <input type="email" name="email" required class="form-input" placeholder="your@email.com" style="padding-left: 3rem;">
                        <i class="fas fa-envelope" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-400); margin-top: 1.5rem;"></i>
                    </div>
                    
                    <div class="form-group" style="position: relative;">
                        <label class="form-label">
                            <i class="fas fa-lock" style="margin-right: 0.5rem; color: var(--primary-600);"></i>পাসওয়ার্ড
                        </label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="password" required class="form-input" placeholder="••••••••" style="padding-left: 3rem; padding-right: 3rem;">
                            <i class="fas fa-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-400);"></i>
                            <button type="button" onclick="togglePassword()" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--gray-400); cursor: pointer; padding: 0.5rem;">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="checkbox" name="remember" style="width: 18px; height: 18px; cursor: pointer;">
                            <span style="font-size: var(--text-sm); color: var(--gray-600);">মনে রাখুন</span>
                        </label>
                        <a href="#" style="font-size: var(--text-sm); color: var(--primary-600); text-decoration: none; font-weight: 600;">পাসওয়ার্ড ভুলে গেছেন?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                        <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>লগইন করুন
                    </button>
                    
                    <div class="divider">
                        <span>অথবা</span>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <button type="button" class="btn btn-outline" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <i class="fab fa-google" style="color: #EA4335;"></i>
                            Google
                        </button>
                        <button type="button" class="btn btn-outline" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <i class="fab fa-facebook-f" style="color: #1877F2;"></i>
                            Facebook
                        </button>
                    </div>
                </form>
                
                <div style="margin-top: 2rem; text-align: center; padding-top: 2rem; border-top: 1px solid var(--gray-200);">
                    <p style="color: var(--gray-600); margin: 0;">অ্যাকাউন্ট নেই? <a href="register.php" style="color: var(--primary-600); font-weight: 600; text-decoration: none;">নিবন্ধন করুন</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <div id="message" class="fixed" style="display: none; position: fixed; top: 1rem; right: 1rem; padding: 1rem 1.5rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); z-index: 1000; max-width: 400px;"></div>
    
    <script>
        function selectUserType(element, type) {
            // Remove active class from all buttons
            document.querySelectorAll('.user-type-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Add active class to clicked button
            element.classList.add('active');
            
            // Update hidden input
            document.getElementById('user_type').value = type;
        }
        
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        function showMessage(text, type = 'success') {
            const msg = document.getElementById('message');
            msg.className = `fixed ${type === 'success' ? 'bg-gradient-primary' : 'bg-gradient-secondary'} text-white`;
            msg.style.display = 'block';
            msg.textContent = text;
            setTimeout(() => msg.style.display = 'none', 3000);
        }
        
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.append('action', 'login');
            
            try {
                const res = await fetch('api/auth.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    showMessage(data.message, 'success');
                    setTimeout(() => window.location.href = data.redirect, 1000);
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('একটি ত্রুটি ঘটেছে। আবার চেষ্টা করুন।', 'error');
            }
        });
    </script>
</body>
</html>
