<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SME Digital Marketing Platform - আপনার ব্যবসার ডিজিটাল সমাধান">
    <title>SME Digital Marketing Platform - হোম</title>
    
    <!-- Redirect to home.php -->
    <meta http-equiv="refresh" content="0;url=home.php">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
            font-family: 'Noto Sans Bengali', sans-serif;
            color: white;
            text-align: center;
        }
        
        .loader {
            display: inline-block;
            width: 60px;
            height: 60px;
            border: 6px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        h1 {
            margin-top: 2rem;
            font-size: 2rem;
        }
        
        p {
            margin-top: 1rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div>
        <div class="loader"></div>
        <h1>স্বাগতম</h1>
        <p>হোম পেজে যাচ্ছি...</p>
        <script>
            // Fallback JavaScript redirect
            setTimeout(() => {
                window.location.href = 'home.php';
            }, 100);
        </script>
    </div>
</body>
</html>
