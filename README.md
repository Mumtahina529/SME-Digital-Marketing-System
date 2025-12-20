# SME Digital Marketing Platform

একটি সম্পূর্ণ কার্যকরী, মাল্টি-পেজ SME ডিজিটাল মার্কেটিং রিয়েলটাইম ওয়েবসাইট যা HTML, CSS, Tailwind CSS, JavaScript, PHP, এবং MySQL ব্যবহার করে তৈরি করা হয়েছে।

## বৈশিষ্ট্য

### ইউজার/কাস্টমার প্যানেল
- ✅ ড্যাশবোর্ড (ক্যাম্পেইন, প্রোভাইডার, পেমেন্ট স্ট্যাটিস্টিক্স)
- ✅ ক্যাম্পেইন ব্যবস্থাপনা (তৈরি, দেখুন, আপডেট)
- ✅ প্রোভাইডার খোঁজা এবং নির্বাচন
- ✅ মেসেজিং সিস্টেম (অ্যাডমিন অনুমোদনের পর আনলক)
- ✅ পেমেন্ট হিস্ট্রি
- ✅ নোটিফিকেশন
- ✅ সাপোর্ট টিকেট
- ✅ রিভিউ এবং রেটিং

### প্রোভাইডার প্যানেল
- ✅ ড্যাশবোর্ড
- ✅ ক্যাম্পেইন আপডেট (রিয়েলটাইম কাজের আপডেট)
- ✅ মেসেজিং
- ✅ Payout Requests
- ✅ নোটিফিকেশন
- ✅ সাপোর্ট

### অ্যাডমিন প্যানেল
- ✅ ড্যাশবোর্ড (সম্পূর্ণ স্ট্যাটিস্টিক্স)
- ✅ প্রোভাইডার নিবন্ধন অনুমোদন
- ✅ প্রোভাইডার অনুরোধ অনুমোদন
- ✅ চ্যাট আনলক/লক
- ✅ সব ক্যাম্পেইন মনিটরিং
- ✅ ইউজার এবং প্রোভাইডার ব্যবস্থাপনা
- ✅ Payout অনুমোদন
- ✅ সাপোর্ট টিকেট ব্যবস্থাপনা

## ইনস্টলেশন

### প্রয়োজনীয় সফটওয়্যার
- PHP 7.4 বা তার উপরে
- MySQL 5.7 বা তার উপরে
- Apache/Nginx Web Server
- XAMPP/WAMP/LAMP (Windows/Mac/Linux)

### সেটআপ ধাপ

1. **ডাটাবেস তৈরি করুন:**
   ```sql
   -- phpMyAdmin বা MySQL CLI ব্যবহার করে database.sql ফাইল import করুন
   mysql -u root -p < database.sql
   ```

2. **কনফিগারেশন:**
   - `config/database.php` ফাইলে আপনার MySQL credentials আপডেট করুন:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'sme_digital_marketing');
   ```

3. **Upload ফোল্ডার পারমিশন:**
   ```bash
   chmod -R 777 uploads/
   ```

4. **ওয়েব সার্ভার চালু করুন:**
   - XAMPP ব্যবহার করলে Apache এবং MySQL start করুন
   - ব্রাউজারে `http://localhost/New folder/` খুলুন

## ডিফল্ট লগইন

### অ্যাডমিন
- Email: `admin@sme.com`
- Password: `admin123`

### টেস্ট ইউজার
- Email: `rahim@example.com`
- Password: `admin123`

### টেস্ট প্রোভাইডার
- Email: `abul@example.com`
- Password: `admin123`

## ওয়ার্কফ্লো

### 1. নিবন্ধন এবং লগইন
- ইউজার এবং প্রোভাইডার আলাদা নিবন্ধন করতে পারে
- প্রোভাইডার নিবন্ধনের পর অ্যাডমিন অনুমোদনের জন্য অপেক্ষা করতে হবে

### 2. প্রোভাইডার নির্বাচন
- ইউজার প্রোভাইডার খুঁজতে পারে (ক্যাটাগরি, বাজেট, অভিজ্ঞতা, রেটিং অনুযায়ী)
- প্রোভাইডার নির্বাচন করলে অ্যাডমিনের কাছে অনুরোধ যায়
- একবার নির্বাচিত প্রোভাইডার আবার নির্বাচন করা যাবে না

### 3. চ্যাট লক/আনলক
- প্রোভাইডার নির্বাচনের পর চ্যাট লক থাকে
- অ্যাডমিন অনুমোদন দিলে চ্যাট আনলক হয়
- আনলকের পর ইউজার এবং প্রোভাইডার চ্যাট করতে পারে

### 4. ক্যাম্পেইন তৈরি
- অনুমোদিত প্রোভাইডারের সাথে ইউজার ক্যাম্পেইন তৈরি করতে পারে
- প্রোভাইডার রিয়েলটাইম কাজের আপডেট দিতে পারে

### 5. পেমেন্ট
- ইউজার Card, Bkash, Nagad, বা COD দিয়ে পেমেন্ট করতে পারে
- প্রোভাইডার Payout request করতে পারে
- অ্যাডমিন Payout approve করতে পারে

### 6. রিভিউ
- ক্যাম্পেইন সম্পন্ন হলে ইউজার রিভিউ দিতে পারে
- রিভিউ প্রোভাইডারের রেটিং আপডেট করে

## প্রযুক্তি স্ট্যাক

- **Frontend:** HTML5, CSS3, Tailwind CSS, JavaScript (Vanilla)
- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Icons:** Font Awesome 6.4.0
- **Fonts:** Noto Sans Bengali

## ফাইল স্ট্রাকচার

```
New folder/
├── api/
│   ├── auth.php
│   ├── providers.php
│   ├── campaigns.php
│   ├── messages.php
│   ├── payments.php
│   ├── reviews.php
│   ├── notifications.php
│   ├── support.php
│   └── admin.php
├── config/
│   ├── config.php
│   └── database.php
├── user/
│   ├── dashboard.php
│   ├── campaigns.php
│   ├── providers.php
│   ├── messages.php
│   ├── payments.php
│   ├── notifications.php
│   └── support.php
├── provider/
│   ├── dashboard.php
│   ├── campaigns.php
│   ├── messages.php
│   ├── payouts.php
│   ├── notifications.php
│   └── support.php
├── admin/
│   ├── dashboard.php
│   └── approvals.php
├── uploads/
│   ├── campaigns/
│   └── messages/
├── database.sql
├── index.php
└── README.md
```

## নিরাপত্তা

- ✅ Password hashing (bcrypt)
- ✅ SQL Injection prevention (Prepared Statements)
- ✅ XSS prevention (Input sanitization)
- ✅ Session management
- ✅ Role-based access control

## রিয়েলটাইম আপডেট

- Dashboard stats প্রতি 30 সেকেন্ডে আপডেট হয়
- Messages প্রতি 5 সেকেন্ডে আপডেট হয়
- Notifications realtime দেখানো হয়

## সমর্থন

কোন সমস্যা হলে সাপোর্ট টিকেট তৈরি করুন অথবা ইমেইল করুন।

## লাইসেন্স

এই প্রজেক্টটি শিক্ষামূলক উদ্দেশ্যে তৈরি করা হয়েছে।

---

**নির্মিত:** SME Digital Marketing Platform Team  
**তারিখ:** 2024

