// Common components for static website

// Navbar HTML
const navbarHTML = `
<nav class="navbar glass-strong" style="position: sticky; top: 0; z-index: 1000; padding: 1rem 0;">
    <div class="container flex items-center justify-between">
        <!-- Logo -->
        <a href="index.html" class="logo flex items-center gap-3" style="text-decoration: none;">
            <div style="width: 50px; height: 50px; background: var(--gradient-primary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-rocket" style="color: white; font-size: 1.5rem;"></i>
            </div>
            <div>
                <div style="font-size: var(--text-xl); font-weight: 800; color: var(--gray-900);">SME Digital</div>
                <div style="font-size: var(--text-xs); color: var(--gray-600); font-weight: 500;">Marketing Platform</div>
            </div>
        </a>
        
        <!-- Desktop Menu -->
        <div class="nav-menu" id="navMenu" style="display: flex; align-items: center; gap: 2rem;">
            <a href="index.html" class="nav-link" style="font-weight: 600; color: var(--gray-700); transition: color 0.3s; text-decoration: none;">
                <i class="fas fa-home" style="margin-right: 0.5rem;"></i>হোম
            </a>
            <a href="about.html" class="nav-link" style="font-weight: 600; color: var(--gray-700); transition: color 0.3s; text-decoration: none;">
                <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>আমাদের সম্পর্কে
            </a>
            <a href="services.html" class="nav-link" style="font-weight: 600; color: var(--gray-700); transition: color 0.3s; text-decoration: none;">
                <i class="fas fa-briefcase" style="margin-right: 0.5rem;"></i>সার্ভিস
            </a>
            <a href="contact.html" class="nav-link" style="font-weight: 600; color: var(--gray-700); transition: color 0.3s; text-decoration: none;">
                <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>যোগাযোগ
            </a>
            
            <!-- Auth Buttons -->
            <div style="display: flex; gap: 1rem; margin-left: 1rem;">
                <a href="login.html" class="btn btn-outline btn-sm">
                    <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>লগইন
                </a>
                <a href="register.html" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus" style="margin-right: 0.5rem;"></i>নিবন্ধন
                </a>
            </div>
        </div>
        
        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-btn" id="mobileMenuBtn" style="display: none; background: none; border: none; font-size: 1.5rem; color: var(--gray-700); cursor: pointer;">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu" style="display: none; background: white; padding: 1rem; border-radius: var(--radius-lg); margin-top: 1rem; box-shadow: var(--shadow-xl);">
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <a href="index.html" class="nav-link" style="padding: 0.75rem; border-radius: var(--radius); font-weight: 600; color: var(--gray-700); text-decoration: none;">
                <i class="fas fa-home" style="margin-right: 0.5rem;"></i>হোম
            </a>
            <a href="about.html" class="nav-link" style="padding: 0.75rem; border-radius: var(--radius); font-weight: 600; color: var(--gray-700); text-decoration: none;">
                <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>আমাদের সম্পর্কে
            </a>
            <a href="services.html" class="nav-link" style="padding: 0.75rem; border-radius: var(--radius); font-weight: 600; color: var(--gray-700); text-decoration: none;">
                <i class="fas fa-briefcase" style="margin-right: 0.5rem;"></i>সার্ভিস
            </a>
            <a href="contact.html" class="nav-link" style="padding: 0.75rem; border-radius: var(--radius); font-weight: 600; color: var(--gray-700); text-decoration: none;">
                <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>যোগাযোগ
            </a>
            <div style="display: flex; flex-direction: column; gap: 0.5rem; padding-top: 1rem; border-top: 1px solid var(--gray-200);">
                <a href="login.html" class="btn btn-outline" style="width: 100%;">
                    <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>লগইন
                </a>
                <a href="register.html" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-user-plus" style="margin-right: 0.5rem;"></i>নিবন্ধন
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
.nav-link {
    position: relative;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 3px;
    background: var(--gradient-primary);
    transition: width 0.3s ease;
    border-radius: 2px;
}

.nav-link:hover::after,
.nav-link.active::after {
    width: 100%;
}

.nav-link:hover,
.nav-link.active {
    color: var(--primary-600) !important;
}

@media (max-width: 768px) {
    .nav-menu {
        display: none !important;
    }
    
    .mobile-menu-btn {
        display: block !important;
    }
}
</style>
`;

// Footer HTML
const footerHTML = `
<footer style="background: linear-gradient(135deg, #1f2937 0%, #111827 100%); color: white; padding: 4rem 0 2rem;">
    <div class="container">
        <div class="grid grid-cols-4 gap-8" style="margin-bottom: 3rem;">
            <!-- Company Info -->
            <div>
                <div class="flex items-center gap-3" style="margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; background: var(--gradient-primary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-rocket" style="color: white; font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <div style="font-size: var(--text-lg); font-weight: 800;">SME Digital</div>
                        <div style="font-size: var(--text-xs); opacity: 0.8;">Marketing Platform</div>
                    </div>
                </div>
                <p style="color: rgba(255, 255, 255, 0.7); line-height: 1.6; margin-bottom: 1.5rem;">
                    আমরা SME ব্যবসায়ীদের জন্য সেরা ডিজিটাল মার্কেটিং সমাধান প্রদান করি।
                </p>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="#" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.1); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; transition: all 0.3s; text-decoration: none; color: white;">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.1); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; transition: all 0.3s; text-decoration: none; color: white;">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.1); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; transition: all 0.3s; text-decoration: none; color: white;">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.1); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; transition: all 0.3s; text-decoration: none; color: white;">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 style="font-size: var(--text-lg); font-weight: 700; margin-bottom: 1.5rem;">দ্রুত লিংক</h3>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                    <li>
                        <a href="index.html" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>হোম
                        </a>
                    </li>
                    <li>
                        <a href="about.html" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>আমাদের সম্পর্কে
                        </a>
                    </li>
                    <li>
                        <a href="services.html" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>সার্ভিস
                        </a>
                    </li>
                    <li>
                        <a href="contact.html" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>যোগাযোগ
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Services -->
            <div>
                <h3 style="font-size: var(--text-lg); font-weight: 700; margin-bottom: 1.5rem;">সার্ভিসসমূহ</h3>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                    <li>
                        <a href="services.html#seo" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>SEO অপটিমাইজেশন
                        </a>
                    </li>
                    <li>
                        <a href="services.html#facebook-ads" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>Facebook Ads
                        </a>
                    </li>
                    <li>
                        <a href="services.html#google-ads" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>Google Ads
                        </a>
                    </li>
                    <li>
                        <a href="services.html#graphics" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>Graphics Design
                        </a>
                    </li>
                    <li>
                        <a href="services.html#web-dev" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>Web Development
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h3 style="font-size: var(--text-lg); font-weight: 700; margin-bottom: 1.5rem;">যোগাযোগ করুন</h3>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                    <li style="display: flex; align-items: start; gap: 0.75rem; color: rgba(255, 255, 255, 0.7);">
                        <i class="fas fa-map-marker-alt" style="margin-top: 0.25rem; color: var(--primary-400);"></i>
                        <span>ঢাকা, বাংলাদেশ</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; color: rgba(255, 255, 255, 0.7);">
                        <i class="fas fa-phone" style="color: var(--primary-400);"></i>
                        <span>+880 1XXX-XXXXXX</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; color: rgba(255, 255, 255, 0.7);">
                        <i class="fas fa-envelope" style="color: var(--primary-400);"></i>
                        <span>info@smedigital.com</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.75rem; color: rgba(255, 255, 255, 0.7);">
                        <i class="fas fa-clock" style="color: var(--primary-400);"></i>
                        <span>সকাল ৯টা - রাত ৯টা</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Newsletter -->
        <div style="background: rgba(255, 255, 255, 0.05); padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 3rem;">
            <div style="text-align: center; max-width: 600px; margin: 0 auto;">
                <h3 style="font-size: var(--text-xl); font-weight: 700; margin-bottom: 0.75rem;">নিউজলেটার সাবস্ক্রাইব করুন</h3>
                <p style="color: rgba(255, 255, 255, 0.7); margin-bottom: 1.5rem;">সর্বশেষ আপডেট এবং অফার পেতে সাবস্ক্রাইব করুন</p>
                <div style="display: flex; gap: 0.75rem; max-width: 400px; margin: 0 auto;">
                    <input type="email" placeholder="আপনার ইমেইল" id="newsletterEmail" style="flex: 1; padding: 0.75rem 1rem; border-radius: var(--radius); border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.1); color: white; font-size: var(--text-base);">
                    <button class="btn btn-primary" onclick="subscribeNewsletter()" style="white-space: nowrap;">
                        <i class="fas fa-paper-plane" style="margin-right: 0.5rem;"></i>সাবস্ক্রাইব
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 2rem; text-align: center;">
            <p style="color: rgba(255, 255, 255, 0.6); margin-bottom: 0.5rem;">
                &copy; <span id="currentYear"></span> SME Digital Marketing Platform. সর্বস্বত্ব সংরক্ষিত।
            </p>
            <div style="display: flex; justify-content: center; gap: 1.5rem; flex-wrap: wrap; margin-top: 1rem;">
                <a href="#" style="color: rgba(255, 255, 255, 0.6); text-decoration: none; font-size: var(--text-sm); transition: color 0.3s;">গোপনীয়তা নীতি</a>
                <a href="#" style="color: rgba(255, 255, 255, 0.6); text-decoration: none; font-size: var(--text-sm); transition: color 0.3s;">শর্তাবলী</a>
                <a href="#" style="color: rgba(255, 255, 255, 0.6); text-decoration: none; font-size: var(--text-sm); transition: color 0.3s;">কুকি নীতি</a>
            </div>
        </div>
    </div>
</footer>

<style>
footer a:hover {
    color: white !important;
}

footer .btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

@media (max-width: 1024px) {
    footer .grid-cols-4 {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 768px) {
    footer .grid-cols-4 {
        grid-template-columns: 1fr !important;
    }
    
    footer {
        padding: 3rem 0 1.5rem !important;
    }
}
</style>
`;

// Initialize components
function initializeComponents() {
    // Insert navbar
    const navbarContainer = document.getElementById('navbar');
    if (navbarContainer) {
        navbarContainer.innerHTML = navbarHTML;
    }
    
    // Insert footer
    const footerContainer = document.getElementById('footer');
    if (footerContainer) {
        footerContainer.innerHTML = footerHTML;
    }
    
    // Set current year in footer
    const yearElement = document.getElementById('currentYear');
    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }
    
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            if (mobileMenu.style.display === 'none' || mobileMenu.style.display === '') {
                mobileMenu.style.display = 'block';
                mobileMenuBtn.querySelector('i').classList.remove('fa-bars');
                mobileMenuBtn.querySelector('i').classList.add('fa-times');
            } else {
                mobileMenu.style.display = 'none';
                mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                mobileMenuBtn.querySelector('i').classList.add('fa-bars');
            }
        });
    }
    
    // Set active nav link
    setActiveNavLink();
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
}

// Set active navigation link based on current page
function setActiveNavLink() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    document.querySelectorAll('.nav-link').forEach(link => {
        const linkPage = link.getAttribute('href');
        if (linkPage === currentPage || 
            (currentPage === '' && linkPage === 'index.html') ||
            (currentPage === 'index.html' && linkPage === 'index.html')) {
            link.classList.add('active');
        }
    });
}

// Newsletter subscription
function subscribeNewsletter() {
    const email = document.getElementById('newsletterEmail');
    if (email && email.value) {
        alert(`ধন্যবাদ! ${email.value} সফলভাবে সাবস্ক্রাইব করেছেন।`);
        email.value = '';
    } else {
        alert('দয়া করে একটি বৈধ ইমেইল প্রবেশ করুন।');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initializeComponents);
