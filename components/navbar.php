<?php
// Get the current page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar glass-strong" style="position: sticky; top: 0; z-index: 1000; padding: 1rem 0;">
    <div class="container flex items-center justify-between">
        <!-- Logo -->
        <a href="home.php" class="logo flex items-center gap-3" style="text-decoration: none;">
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
            <a href="home.php" class="nav-link <?php echo $current_page == 'home.php' ? 'active' : ''; ?>" style="font-weight: 600; color: var(--gray-700); transition: color 0.3s; text-decoration: none;">
                <i class="fas fa-home" style="margin-right: 0.5rem;"></i>হোম
            </a>
            <a href="about.php" class="nav-link <?php echo $current_page == 'about.php' ? 'active' : ''; ?>" style="font-weight: 600; color: var(--gray-700); transition: color 0.3s; text-decoration: none;">
                <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>আমাদের সম্পর্কে
            </a>
            <a href="services.php" class="nav-link <?php echo $current_page == 'services.php' ? 'active' : ''; ?>" style="font-weight: 600; color: var(--gray-700); transition: color 0.3s; text-decoration: none;">
                <i class="fas fa-briefcase" style="margin-right: 0.5rem;"></i>সার্ভিস
            </a>
            <a href="contact.php" class="nav-link <?php echo $current_page == 'contact.php' ? 'active' : ''; ?>" style="font-weight: 600; color: var(--gray-700); transition: color 0.3s; text-decoration: none;">
                <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>যোগাযোগ
            </a>
            
            <!-- Auth Buttons -->
            <div style="display: flex; gap: 1rem; margin-left: 1rem;">
                <a href="login.php" class="btn btn-outline btn-sm">
                    <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>লগইন
                </a>
                <a href="register.php" class="btn btn-primary btn-sm">
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
            <a href="home.php" class="nav-link" style="padding: 0.75rem; border-radius: var(--radius); font-weight: 600; color: var(--gray-700); text-decoration: none;">
                <i class="fas fa-home" style="margin-right: 0.5rem;"></i>হোম
            </a>
            <a href="about.php" class="nav-link" style="padding: 0.75rem; border-radius: var(--radius); font-weight: 600; color: var(--gray-700); text-decoration: none;">
                <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>আমাদের সম্পর্কে
            </a>
            <a href="services.php" class="nav-link" style="padding: 0.75rem; border-radius: var(--radius); font-weight: 600; color: var(--gray-700); text-decoration: none;">
                <i class="fas fa-briefcase" style="margin-right: 0.5rem;"></i>সার্ভিস
            </a>
            <a href="contact.php" class="nav-link" style="padding: 0.75rem; border-radius: var(--radius); font-weight: 600; color: var(--gray-700); text-decoration: none;">
                <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>যোগাযোগ
            </a>
            <div style="display: flex; flex-direction: column; gap: 0.5rem; padding-top: 1rem; border-top: 1px solid var(--gray-200);">
                <a href="login.php" class="btn btn-outline" style="width: 100%;">
                    <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>লগইন
                </a>
                <a href="register.php" class="btn btn-primary" style="width: 100%;">
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

<script>
// Mobile menu toggle
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const mobileMenu = document.getElementById('mobileMenu');

if (mobileMenuBtn) {
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

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
