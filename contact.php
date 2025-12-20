<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="যোগাযোগ করুন - SME Digital Marketing Platform">
    <title>যোগাযোগ - SME Digital Marketing Platform</title>
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bengali">
    <?php include 'components/navbar.php'; ?>
    
    <!-- Page Header -->
    <section style="background: var(--gradient-primary); padding: 6rem 0 4rem; text-align: center; color: white;">
        <div class="container">
            <h1 class="animate-fadeInUp" style="font-size: var(--text-6xl); font-weight: 900; margin-bottom: 1.5rem; color: white;">যোগাযোগ করুন</h1>
            <p class="animate-fadeInUp animate-delay-1" style="font-size: var(--text-xl); max-width: 700px; margin: 0 auto; opacity: 0.95;">আমরা আপনার সেবায় সর্বদা প্রস্তুত</p>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section class="section">
        <div class="container">
            <div class="grid grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div>
                    <h2 style="font-size: var(--text-3xl); font-weight: 800; margin-bottom: 1.5rem; color: var(--gray-900);">আমাদের একটি মেসেজ পাঠান</h2>
                    <p style="color: var(--gray-600); margin-bottom: 2rem; font-size: var(--text-lg);">
                        আপনার যেকোনো প্রশ্ন বা পরামর্শ আমাদের জানান। আমরা যত দ্রুত সম্ভব উত্তর দেব।
                    </p>
                    
                    <form id="contactForm" style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">নাম *</label>
                            <input type="text" name="name" required class="form-input" placeholder="আপনার নাম">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">ইমেইল *</label>
                            <input type="email" name="email" required class="form-input" placeholder="your@email.com">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">ফোন নম্বর</label>
                            <input type="tel" name="phone" class="form-input" placeholder="+880 1XXX-XXXXXX">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">বিষয়</label>
                            <select name="subject" class="form-select">
                                <option value="">নির্বাচন করুন</option>
                                <option value="general">সাধারণ জিজ্ঞাসা</option>
                                <option value="service">সার্ভিস সম্পর্কে</option>
                                <option value="support">টেকনিক্যাল সাপোর্ট</option>
                                <option value="partnership">পার্টনারশিপ</option>
                                <option value="other">অন্যান্য</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">মেসেজ *</label>
                            <textarea name="message" required class="form-textarea" rows="6" placeholder="আপনার মেসেজ লিখুন..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane" style="margin-right: 0.5rem;"></i>মেসেজ পাঠান
                        </button>
                    </form>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h2 style="font-size: var(--text-3xl); font-weight: 800; margin-bottom: 1.5rem; color: var(--gray-900);">যোগাযোগের তথ্য</h2>
                    <p style="color: var(--gray-600); margin-bottom: 2.5rem; font-size: var(--text-lg);">
                        আপনি চাইলে সরাসরি আমাদের সাথে যোগাযোগ করতে পারেন
                    </p>
                    
                    <div style="display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 3rem;">
                        <div class="card" style="padding: 1.5rem; border-left: 4px solid var(--primary-600);">
                            <div style="display: flex; align-items: start; gap: 1rem;">
                                <div style="width: 50px; height: 50px; background: var(--gradient-primary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-map-marker-alt" style="color: white; font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <h4 style="font-weight: 700; color: var(--gray-900); margin-bottom: 0.5rem;">আমাদের অফিস</h4>
                                    <p style="color: var(--gray-600);">ধানমন্ডি, ঢাকা - ১২০৫<br>বাংলাদেশ</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card" style="padding: 1.5rem; border-left: 4px solid var(--secondary-600);">
                            <div style="display: flex; align-items: start; gap: 1rem;">
                                <div style="width: 50px; height: 50px; background: var(--gradient-secondary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-phone" style="color: white; font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <h4 style="font-weight: 700; color: var(--gray-900); margin-bottom: 0.5rem;">ফোন</h4>
                                    <p style="color: var(--gray-600);">+880 1XXX-XXXXXX<br>+880 1YYY-YYYYYY</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card" style="padding: 1.5rem; border-left: 4px solid var(--accent-600);">
                            <div style="display: flex; align-items: start; gap: 1rem;">
                                <div style="width: 50px; height: 50px; background: var(--gradient-accent); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-envelope" style="color: white; font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <h4 style="font-weight: 700; color: var(--gray-900); margin-bottom: 0.5rem;">ইমেইল</h4>
                                    <p style="color: var(--gray-600);">info@smedigital.com<br>support@smedigital.com</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card" style="padding: 1.5rem; border-left: 4px solid #fa709a;">
                            <div style="display: flex; align-items: start; gap: 1rem;">
                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-clock" style="color: white; font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <h4 style="font-weight: 700; color: var(--gray-900); margin-bottom: 0.5rem;">কর্মসময়</h4>
                                    <p style="color: var(--gray-600);">শনিবার - বৃহস্পতিবার<br>সকাল ৯টা - রাত ৯টা</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div>
                        <h4 style="font-weight: 700; color: var(--gray-900); margin-bottom: 1rem;">সোশ্যাল মিডিয়া</h4>
                        <div style="display: flex; gap: 1rem;">
                            <a href="#" style="width: 50px; height: 50px; background: var(--gradient-primary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s; box-shadow: var(--shadow);">
                                <i class="fab fa-facebook-f" style="font-size: 1.25rem;"></i>
                            </a>
                            <a href="#" style="width: 50px; height: 50px; background: var(--gradient-accent); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s; box-shadow: var(--shadow);">
                                <i class="fab fa-twitter" style="font-size: 1.25rem;"></i>
                            </a>
                            <a href="#" style="width: 50px; height: 50px; background: var(--gradient-secondary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s; box-shadow: var(--shadow);">
                                <i class="fab fa-linkedin-in" style="font-size: 1.25rem;"></i>
                            </a>
                            <a href="#" style="width: 50px; height: 50px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s; box-shadow: var(--shadow);">
                                <i class="fab fa-instagram" style="font-size: 1.25rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Map Section -->
    <section class="section" style="background: var(--gray-100);">
        <div class="container">
            <h2 class="section-title">আমাদের অবস্থান</h2>
            <p class="section-subtitle">আমাদের অফিসে সরাসরি ভিজিট করুন</p>
            
            <div style="margin-top: 3rem; border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-xl); height: 400px; background: white; display: flex; align-items: center; justify-content: center;">
                <!-- Placeholder for map -->
                <div style="text-align: center; padding: 3rem;">
                    <div style="width: 100px; height: 100px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; font-size: 3rem;">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 style="font-size: var(--text-2xl); font-weight: 700; margin-bottom: 1rem; color: var(--gray-900);">ম্যাপ আসছে শীঘ্রই</h3>
                    <p style="color: var(--gray-600); max-width: 500px; margin: 0 auto;">Google Maps integration শীঘ্রই যুক্ত হবে। এই জায়গায় আমাদের অফিসের সঠিক লোকেশন দেখা যাবে।</p>
                    <p style="color: var(--primary-600); font-weight: 600; margin-top: 1rem;">
                        <i class="fas fa-map-marker-alt" style="margin-right: 0.5rem;"></i>ধানমন্ডি, ঢাকা - ১২০৫, বাংলাদেশ
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">সাধারণ প্রশ্নাবলী (FAQ)</h2>
            <p class="section-subtitle">প্রায়শই জিজ্ঞাসিত প্রশ্নসমূহ</p>
            
            <div style="max-width: 900px; margin: 3rem auto 0; display: flex; flex-direction: column; gap: 1rem;">
                <div class="card" style="padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="toggleFaq(this)">
                        <h4 style="font-weight: 700; color: var(--gray-900);">কীভাবে নিবন্ধন করব?</h4>
                        <i class="fas fa-chevron-down" style="color: var(--primary-600); transition: transform 0.3s;"></i>
                    </div>
                    <div class="faq-content" style="display: none; padding-top: 1rem; color: var(--gray-600);">
                        <p>হোম পেজ থেকে "নিবন্ধন" বাটনে ক্লিক করুন। তারপর আপনি কাস্টমার নাকি প্রোভাইডার তা নির্বাচন করে প্রয়োজনীয় তথ্য দিয়ে রেজিস্ট্রেশন সম্পন্ন করুন।</p>
                    </div>
                </div>
                
                <div class="card" style="padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="toggleFaq(this)">
                        <h4 style="font-weight: 700; color: var(--gray-900);">পেমেন্ট কীভাবে করব?</h4>
                        <i class="fas fa-chevron-down" style="color: var(--primary-600); transition: transform 0.3s;"></i>
                    </div>
                    <div class="faq-content" style="display: none; padding-top: 1rem; color: var(--gray-600);">
                        <p>আমরা বিভিন্ন পেমেন্ট মেথড সাপোর্ট করি - Card, Bkash, Nagad, এবং COD। ক্যাম্পেইন তৈরির সময় আপনার সুবিধামতো পেমেন্ট মেথড নির্বাচন করতে পারবেন।</p>
                    </div>
                </div>
                
                <div class="card" style="padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="toggleFaq(this)">
                        <h4 style="font-weight: 700; color: var(--gray-900);">প্রোভাইডার নির্বাচনের পর কী হবে?</h4>
                        <i class="fas fa-chevron-down" style="color: var(--primary-600); transition: transform 0.3s;"></i>
                    </div>
                    <div class="faq-content" style="display: none; padding-top: 1rem; color: var(--gray-600);">
                        <p>প্রোভাইডার নির্বাচনের পর অ্যাডমিন আপনার রিকোয়েস্ট রিভিউ করবে। অনুমোদন পেলে আপনি প্রোভাইডারের সাথে চ্যাট করতে এবং ক্যাম্পেইন তৈরি করতে পারবেন।</p>
                    </div>
                </div>
                
                <div class="card" style="padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="toggleFaq(this)">
                        <h4 style="font-weight: 700; color: var(--gray-900);">সাপোর্ট কীভাবে পাব?</h4>
                        <i class="fas fa-chevron-down" style="color: var(--primary-600); transition: transform 0.3s;"></i>
                    </div>
                    <div class="faq-content" style="display: none; padding-top: 1rem; color: var(--gray-600);">
                        <p>আপনি আপনার ড্যাশবোর্ড থেকে সাপোর্ট টিকেট তৈরি করতে পারবেন। এছাড়া ইমেইল, ফোন, বা লাইভ চ্যাটের মাধ্যমেও সাপোর্ট পাবেন।</p>
                    </div>
                </div>
                
                <div class="card" style="padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="toggleFaq(this)">
                        <h4 style="font-weight: 700; color: var(--gray-900);">রিফান্ড পলিসি কী?</h4>
                        <i class="fas fa-chevron-down" style="color: var(--primary-600); transition: transform 0.3s;"></i>
                    </div>
                    <div class="faq-content" style="display: none; padding-top: 1rem; color: var(--gray-600);">
                        <p>যদি সার্ভিস শুরু হওয়ার আগে ক্যান্সেল করতে চান, তাহলে সম্পূর্ণ রিফান্ড পাবেন। কাজ শুরু হওয়ার পর রিফান্ড পলিসি প্রজেক্টের ধরনের উপর নির্ভর করে।</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="section" style="background: var(--gradient-primary); color: white; text-align: center;">
        <div class="container">
            <h2 style="font-size: var(--text-4xl); font-weight: 900; margin-bottom: 1.5rem; color: white;">এখনও কোন প্রশ্ন আছে?</h2>
            <p style="font-size: var(--text-xl); margin-bottom: 2.5rem; opacity: 0.95;">আমাদের সাথে সরাসরি যোগাযোগ করুন</p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="#contactForm" class="btn btn-lg" style="background: white; color: var(--primary-600);" onclick="document.getElementById('contactForm').scrollIntoView({behavior: 'smooth'}); return false;">
                    <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>মেসেজ পাঠান
                </a>
                <a href="tel:+8801XXXXXXXXX" class="btn btn-lg btn-outline" style="border-color: white; color: white;">
                    <i class="fas fa-phone" style="margin-right: 0.5rem;"></i>কল করুন
                </a>
            </div>
        </div>
    </section>
    
    <?php include 'components/footer.php'; ?>
    
    <div id="message" class="fixed" style="display: none; position: fixed; top: 1rem; right: 1rem; padding: 1rem 1.5rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); z-index: 1000; max-width: 400px;"></div>
    
    <script>
        function toggleFaq(element) {
            const content = element.nextElementSibling;
            const icon = element.querySelector('i');
            
            if (content.style.display === 'none' || content.style.display === '') {
                content.style.display = 'block';
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            }
        }
        
        function showMessage(text, type = 'success') {
            const msg = document.getElementById('message');
            msg.className = `fixed ${type === 'success' ? 'bg-gradient-primary' : 'bg-gradient-secondary'} text-white`;
            msg.style.display = 'block';
            msg.textContent = text;
            setTimeout(() => msg.style.display = 'none', 3000);
        }
        
        document.getElementById('contactForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Simulate form submission
            showMessage('আপনার মেসেজ সফলভাবে পাঠানো হয়েছে! আমরা শীঘ্রই যোগাযোগ করব।', 'success');
            e.target.reset();
            
            // In production, you would send this to a backend API
            /*
            const formData = new FormData(e.target);
            const res = await fetch('api/contact.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                showMessage(data.message, 'success');
                e.target.reset();
            } else {
                showMessage(data.message, 'error');
            }
            */
        });
    </script>
</body>
</html>
