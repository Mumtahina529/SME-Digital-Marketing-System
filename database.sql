-- SME Digital Marketing Platform Database Schema
-- All tables with proper relationships and constraints

CREATE DATABASE IF NOT EXISTS sme_digital_marketing;
USE sme_digital_marketing;

-- Users Table (Customers)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    company_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Providers Table
CREATE TABLE providers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    company_name VARCHAR(100),
    category ENUM('SEO', 'Facebook Ads', 'Google Ads', 'Graphics', 'Web Dev') NOT NULL,
    experience INT DEFAULT 0,
    budget_min DECIMAL(10,2) DEFAULT 0,
    budget_max DECIMAL(10,2) DEFAULT 0,
    availability ENUM('available', 'busy', 'unavailable') DEFAULT 'available',
    rating DECIMAL(3,2) DEFAULT 0.00,
    total_reviews INT DEFAULT 0,
    bio TEXT,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin Table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Provider Selection Requests (User selects provider)
CREATE TABLE provider_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    provider_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved_at TIMESTAMP NULL,
    rejected_at TIMESTAMP NULL,
    admin_id INT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL,
    UNIQUE KEY unique_user_provider (user_id, provider_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Campaigns Table
CREATE TABLE campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    provider_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    platform ENUM('Facebook', 'Instagram', 'Google Ads', 'LinkedIn', 'Twitter', 'Other') NOT NULL,
    budget DECIMAL(10,2) NOT NULL,
    target_audience TEXT,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    logo_file VARCHAR(255),
    brand_assets TEXT,
    status ENUM('pending', 'active', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Campaign Updates (Realtime work progress)
CREATE TABLE campaign_updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campaign_id INT NOT NULL,
    provider_id INT NOT NULL,
    work_started BOOLEAN DEFAULT FALSE,
    ad_results TEXT,
    spend_report DECIMAL(10,2) DEFAULT 0,
    leads_generated INT DEFAULT 0,
    engagement_reach INT DEFAULT 0,
    daily_analytics TEXT,
    notes TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Messages Table
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    sender_type ENUM('user', 'provider') NOT NULL,
    receiver_type ENUM('user', 'provider') NOT NULL,
    message TEXT NOT NULL,
    attachment VARCHAR(255),
    is_seen BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_conversation (sender_id, receiver_id, sender_type, receiver_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Chat Locks (Controls when chat is unlocked)
CREATE TABLE chat_locks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    provider_id INT NOT NULL,
    is_locked BOOLEAN DEFAULT TRUE,
    unlocked_at TIMESTAMP NULL,
    unlocked_by INT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE,
    FOREIGN KEY (unlocked_by) REFERENCES admins(id) ON DELETE SET NULL,
    UNIQUE KEY unique_user_provider_chat (user_id, provider_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Payments Table
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    provider_id INT NOT NULL,
    campaign_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('card', 'bkash', 'nagad', 'cod') NOT NULL,
    transaction_id VARCHAR(100),
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE,
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Payout Requests (Provider requests payment)
CREATE TABLE payout_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50),
    account_details TEXT,
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved_at TIMESTAMP NULL,
    approved_by INT NULL,
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Reviews Table
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    provider_id INT NOT NULL,
    campaign_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE,
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
    UNIQUE KEY unique_campaign_review (campaign_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Notifications Table
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    provider_id INT NULL,
    admin_id INT NULL,
    user_type ENUM('user', 'provider', 'admin') NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Support Tickets
CREATE TABLE support_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    provider_id INT NULL,
    user_type ENUM('user', 'provider') NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open',
    admin_response TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Default Admin (password: admin123)
INSERT INTO admins (name, email, password) VALUES 
('Admin', 'admin@sme.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert Sample Data for Testing
INSERT INTO users (name, email, phone, password, company_name) VALUES
('রহিম উদ্দিন', 'rahim@example.com', '01712345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'রহিম ট্রেডার্স'),
('করিম আহমেদ', 'karim@example.com', '01876543210', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'করিম ইন্ডাস্ট্রিজ');

INSERT INTO providers (name, email, phone, password, company_name, category, experience, budget_min, budget_max, availability, bio, status) VALUES
('আবুল কালাম', 'abul@example.com', '01911223344', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'কালাম ডিজিটাল', 'SEO', 5, 5000, 50000, 'available', 'আমি একজন অভিজ্ঞ SEO বিশেষজ্ঞ', 'approved'),
('ফাতেমা খাতুন', 'fatema@example.com', '01699887766', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ফাতেমা মার্কেটিং', 'Facebook Ads', 3, 10000, 100000, 'available', 'ফেসবুক বিজ্ঞাপন বিশেষজ্ঞ', 'approved'),
('হাসান মাহমুদ', 'hasan@example.com', '01555667788', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'হাসান গ্রাফিক্স', 'Graphics', 4, 3000, 30000, 'available', 'পেশাদার গ্রাফিক্স ডিজাইনার', 'approved'),
('মাহদি হাসান', 'mahdi@example.com', '01744110055', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'মাহদি ওয়েব', 'Web Dev', 6, 15000, 120000, 'available', 'ফুল-স্ট্যাক ওয়েব ডেভেলপার (PHP/JS)', 'approved'),
('রুবাইয়া আক্তার', 'rubaiya@example.com', '01833007711', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'রুবাইয়া সোশ্যাল', 'Facebook Ads', 4, 8000, 60000, 'available', 'ই-কমার্স ফেসবুক অ্যাডস স্পেশালিস্ট', 'approved'),
('তানভীর রহমান', 'tanvir@example.com', '01922003344', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'তানভীর ডিএস', 'Google Ads', 5, 12000, 90000, 'available', 'সার্চ ও ডিসপ্লে গুগল অ্যাডস অপটিমাইজার', 'approved'),
('শারমিন জাহান', 'sharmin@example.com', '01655007788', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'শারমিন ক্রিয়েটিভ', 'Graphics', 7, 4000, 45000, 'available', 'ব্র্যান্ডিং ও ক্রিয়েটিভ ডিজাইন এক্সপার্ট', 'approved'),
('সাজেদুল ইসলাম', 'sajed@example.com', '01522009933', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'সাজেদ আইটি', 'SEO', 8, 7000, 70000, 'available', 'টেকনিক্যাল ও কন্টেন্ট SEO কনসালট্যান্ট', 'approved');

