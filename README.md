# OM Trip and Travels — Website & Admin Panel

Production-ready website and administrative panel for **OM Trip and Travels**, a premier travel and van/car rental business based in **Indore, Madhya Pradesh, India**.

Specialized in **Force Urbania** and **Tempo Traveller** rentals for group travel, family vacations, pilgrimages (Ujjain, Omkareshwar, Maheshwar-Mandu, Shirdi), corporate outings, and airport transfers.

---

## 🌟 Key Business Features

- **No Online Booking / Payment Required**: Seamless conversion-focused experience:
  - **"Call Now"** button on every vehicle and package page (`tel:` link with click tracking)
  - **"WhatsApp"** button (`wa.me` link with dynamic, pre-filled, URL-encoded messages including vehicle/package title and page URL)
  - **Booking Request Form** & **Contact Enquiry Form** saved to MySQL and managed in the admin dashboard.
- **Conversion-Optimized Hero & CTA Strips**: Sticky navbar with instant call button, floating WhatsApp icon with pulse animation, and mobile bottom action bar (`Call` | `WhatsApp` | `Book Now`).
- **Complete Admin Panel (`/admin`)**:
  - Secure authentication with password hashing, CSRF token validation, session regeneration, and failed-login throttling (5 attempts = 15-minute lockout).
  - Role-Based Access: **Super Admin** (full system control) and **Staff** (enquiries & blog only).
  - Enquiries CRM with status tracking (`new`, `contacted`, `followup`, `confirmed`, `closed`, `spam`), internal team notes, quick call/WhatsApp back buttons, and **CSV export**.
  - Vehicle & Category CRUD with multiple image uploads, auto WebP conversion, drag reordering, and features tags.
  - Tour Packages CRUD with day-by-day itineraries, inclusions, exclusions, and linked vehicle fleets.
  - Travel Blog CMS with rich text editor (TinyMCE), categories, tags, and draft/published workflow.
  - Testimonials, Gallery, FAQ, and Hero Banner sliders management.
  - Comprehensive Site Settings (business info, WhatsApp message templates, Google Maps embed, analytics code, header/footer scripts, and **SMTP test tool**).
  - Activity audit log viewer.
- **Local Indore SEO**:
  - Optimized for high-intent keywords: *Urbania on rent in Indore*, *Tempo Traveller on rent in Indore*, *Indore to Ujjain taxi*, *Innova/Fortuner on rent in Indore*, *Indore airport taxi*.
  - Rich JSON-LD Schemas: `TravelAgency` / `LocalBusiness`, `Product`, `TouristTrip`, `FAQPage`, and `BlogPosting`.
  - Auto-generated `sitemap.xml` and `robots.txt`.

---

## 🛠️ Tech Stack & Architecture

- **Backend**: PHP 8.1+ with lightweight custom MVC architecture (no heavy framework overhead, extremely fast execution).
- **Database**: MySQL 5.7+ / 8.0+ via PDO with prepared statements **only**.
- **Security**: CSRF tokens, honeypot spam protection, client IP rate limiting, input sanitization, output HTML escaping (`e()` helper), strict session cookie settings (HttpOnly, SameSite=Lax, Secure when HTTPS).
- **Frontend**: Bootstrap 5.3, Bootstrap Icons, custom responsive CSS & vanilla JavaScript (no jQuery dependency).
- **Image Processing**: Auto-conversion to modern `.webp` with GD library, preserving transparency and aspect ratio.

---

## 📁 Folder Structure

```
OmTripAndTravels/
├── app/
│   ├── Controllers/
│   │   ├── Admin/           # Admin controllers (Auth, Dashboard, Enquiry, Vehicle, etc.)
│   │   ├── ApiController.php
│   │   ├── BlogController.php
│   │   ├── EnquiryController.php
│   │   ├── FleetController.php
│   │   ├── HomeController.php
│   │   ├── PackageController.php
│   │   ├── PageController.php
│   │   └── SeoController.php
│   ├── Core/                # Framework core (Database, Router, Controller, Session, View)
│   ├── Helpers/             # functions.php, upload.php, mailer.php
│   ├── Models/              # Vehicle, TourPackage, Enquiry, BlogPost, Admin, Setting, etc.
│   └── Views/
│       ├── admin/           # Admin panel view templates
│       ├── blog/            # Blog list & article views
│       ├── errors/          # 403, 404, 500 views
│       ├── fleet/           # Vehicle listing and detail views
│       ├── home/            # Homepage view
│       ├── layouts/         # main.php (public) & admin.php (admin dashboard)
│       ├── packages/        # Tour packages listing & detail views
│       └── pages/           # About, Contact, Booking, Gallery, Reviews, FAQ, Airport
├── bin/
│   ├── create-admin.php     # CLI script to create / reset admin credentials
│   └── generate-placeholders.php
├── database/
│   ├── schema.sql           # Database tables, foreign keys, and indexes
│   └── seed.sql             # Demo fleet, tour packages, FAQs, settings, blog posts
├── public/
│   ├── assets/              # CSS, JS, branding images
│   ├── uploads/             # User uploads (vehicles, packages, blog) with .htaccess security
│   ├── .htaccess            # Apache configuration (security headers, gzip, caching)
│   └── index.php            # Front controller entry point
├── storage/
│   └── logs/                # Error logs and audit trails
├── .env.example             # Environment template
├── .htaccess                # Root rewrite rule to /public
├── composer.json            # Composer dependencies (phpdotenv, phpmailer)
└── README.md
```

---

## 💻 Local Development Setup

### 1. Requirements
- PHP 8.1 or higher (with `pdo_mysql`, `gd`, `fileinfo`, `mbstring`, `curl` extensions enabled).
- MySQL 5.7+ or MariaDB 10.3+.
- Composer (optional — a built-in fallback autoloader is already included).

### 2. Configure Environment
Copy `.env.example` to `.env`:
```bash
cp .env.example .env
```
Open `.env` and configure your database settings:
```ini
APP_NAME="OM Trip and Travels"
APP_URL=http://localhost:8000
APP_ENV=local
APP_DEBUG=true

DB_HOST=localhost
DB_PORT=3306
DB_NAME=omtrip_db
DB_USER=root
DB_PASS=
```

### 3. Create Database & Import Tables
In MySQL:
```sql
CREATE DATABASE IF NOT EXISTS `omtrip_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
Import `database/schema.sql` and `database/seed.sql`:
```bash
mysql -u root -p omtrip_db < database/schema.sql
mysql -u root -p omtrip_db < database/seed.sql
```

### 4. Create Your First Admin User
Run the secure CLI script:
```bash
php bin/create-admin.php
```
Follow the interactive prompts to set your admin name, email, and password.

### 5. Start Local PHP Server
```bash
php -S localhost:8000 -t public
```
- Public website: **http://localhost:8000**
- Admin login: **http://localhost:8000/admin/login**

---

## 🚀 Hostinger Shared Hosting Deployment Guide

### Step 1: Upload Files to Hostinger
1. Log in to your **Hostinger hPanel**.
2. Go to **File Manager** (or connect via SFTP/FTP using FileZilla).
3. Upload all project files into your domain's folder (typically `public_html/`).

### Step 2: Configure Document Root OR Use Root `.htaccess`
**Option A (Recommended in hPanel):**
- In hPanel, go to **Websites** &rarr; **Website Configuration** &rarr; **Change Document Root**.
- Point the document root from `public_html` to `public_html/public`.

**Option B (Standard):**
- If your hosting doesn't allow changing the document root, keep the included root `.htaccess` inside `public_html/`. It automatically routes all incoming requests to the `/public` directory securely.

### Step 3: Create MySQL Database & Import Data
1. In hPanel, navigate to **Databases** &rarr; **MySQL Databases**.
2. Create a new database (e.g. `u123456789_omtrip`) and database user with full privileges.
3. Open **phpMyAdmin** for that database.
4. Click **Import**, choose `database/schema.sql`, and execute.
5. Click **Import**, choose `database/seed.sql`, and execute.

### Step 4: Configure Production `.env`
In File Manager, create/edit `.env` inside the root folder:
```ini
APP_NAME="OM Trip and Travels"
APP_URL=https://yourdomain.com
APP_ENV=production
APP_DEBUG=false

DB_HOST=localhost
DB_PORT=3306
DB_NAME=u123456789_omtrip
DB_USER=u123456789_omtripuser
DB_PASS=YourStrongDatabasePassword123!

SMTP_HOST=smtp.hostinger.com
SMTP_PORT=465
SMTP_USER=info@yourdomain.com
SMTP_PASS=YourEmailPassword123!
SMTP_FROM=info@yourdomain.com
ADMIN_NOTIFY_EMAIL=admin@yourdomain.com

WHATSAPP_NUMBER=919876543210
PHONE_NUMBER=+919876543210
```

### Step 5: Set Folder Permissions
Ensure the following directories are writeable by PHP (Permission `755` or `775`):
- `storage/logs/`
- `storage/rate_limits/`
- `public/uploads/` (and its subdirectories `vehicles`, `packages`, `blog`, `gallery`, `banners`, `general`)

> *Note: PHP file execution is strictly blocked inside `public/uploads/` via `public/uploads/.htaccess`.*

### Step 6: Create the Admin User
Open the **SSH Terminal** in Hostinger hPanel or run via SSH:
```bash
php bin/create-admin.php "Super Admin" admin@yourdomain.com "YourSecurePassword" super_admin
```

### Step 7: Enable SSL
In hPanel &rarr; **Security** &rarr; **SSL**, activate the free Let's Encrypt SSL certificate and enable **Force HTTPS**.

---

## ✅ Post-Launch Verification Checklist

1. [ ] **Homepage Verification**: Open your domain in mobile & desktop views. Check banner slides and USP strip.
2. [ ] **Call Button Test**: Tap the "Call Now" buttons and verify your phone dialer opens with `+919876543210`.
3. [ ] **WhatsApp Button Test**: Tap the floating WhatsApp button and vehicle WhatsApp buttons. Verify WhatsApp opens with the pre-filled message including vehicle name and URL.
4. [ ] **Booking Form Submission**: Submit a booking request from `/booking`. Verify:
   - Green confirmation banner appears.
   - Lead appears immediately in `/admin/enquiries`.
   - Notification email arrives at `ADMIN_NOTIFY_EMAIL`.
5. [ ] **Admin Security**: Test `/admin/login` with incorrect credentials 5 times and confirm IP rate limiting activates.
6. [ ] **Admin Management**: Add a vehicle in `/admin/vehicles/create`, upload photos, and verify it displays on `/fleet`.
7. [ ] **SMTP Test**: Go to `/admin/settings` and click **"Test SMTP Connection"** to verify email delivery.
8. [ ] **Sitemap & Search Console**: Check `https://yourdomain.com/sitemap.xml` and submit it to **Google Search Console** for rapid Indore indexing.

---

## 📞 Support & Business Inquiries
**OM Trip and Travels**  
Vijay Nagar, Indore, Madhya Pradesh - 452010  
Phone: +91 98765 43210 | Email: info@omtripandtravels.com

