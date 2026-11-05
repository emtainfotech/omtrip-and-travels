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

## 🚀 Hostinger Web Hosting Deployment Guide (via GitHub)

This section provides the complete step-by-step workflow to deploy **OM Trip and Travels** to Hostinger Shared Hosting using GitHub and Hostinger's native Git auto-deploy integration.

### Step 1: Push Code to GitHub
1. Open your terminal in this project folder:
   ```bash
   git branch -M main
   ```
2. Create a new repository on [GitHub](https://github.com/new) (e.g. `omtrip-and-travels`). It can be **Public** or **Private**.
3. Add the remote and push your code:
   ```bash
   git remote add origin https://github.com/YOUR_GITHUB_USERNAME/omtrip-and-travels.git
   git push -u origin main
   ```
   *(Note: `.env`, local sqlite databases, and temporary logs are safely excluded via `.gitignore`)*

---

### Step 2: Create MySQL Database on Hostinger
1. Log in to [Hostinger hPanel](https://hpanel.hostinger.com).
2. Go to **Databases** &rarr; **MySQL Databases**.
3. Under **Create a New MySQL Database and Database User**:
   - **Database Name**: e.g., `omtrip` (full name will be like `u123456789_omtrip`)
   - **Username**: e.g., `omtripuser` (full username will be like `u123456789_omtripuser`)
   - **Password**: Choose a strong password (copy it down).
4. Click **Create**.

---

### Step 3: Import Database Tables via phpMyAdmin
1. On the same **MySQL Databases** page, scroll down to **List of Current MySQL Databases And Users**.
2. Click **Enter phpMyAdmin** next to your new database.
3. In phpMyAdmin, click the **Import** tab at the top.
4. Click **Choose File** and select `database/schema.sql` from your local machine, then click **Go** (or **Import** at the bottom).
5. Next, click **Import** again, choose `database/seed.sql`, and click **Go**.
   > *All tables, initial categories, sample fleet (Urbania, Tempo Traveller, Fortuner, Innova), tour packages, FAQs, and the pre-configured Super Admin account are now loaded!*

---

### Step 4: Connect GitHub in Hostinger hPanel
1. In Hostinger hPanel, search for or click **Git** (under the **Advanced** section).
2. Fill in the deployment details:
   - **Repository**: Paste your GitHub repository URL:
     - For **Public** repos: `https://github.com/YOUR_GITHUB_USERNAME/omtrip-and-travels.git`
     - For **Private** repos: `git@github.com:YOUR_GITHUB_USERNAME/omtrip-and-travels.git`
   - **Branch**: `main`
   - **Install Path**: `public_html` (leave default or select your domain root)
3. If using a **Private Repository**:
   - Hostinger will display an **SSH Public Key**.
   - Copy this key, go to your repository on **GitHub** &rarr; **Settings** &rarr; **Deploy keys** &rarr; **Add deploy key**.
   - Paste the key, name it "Hostinger", and click **Add key**.
4. Click **Create** in Hostinger. Hostinger will clone and pull your code directly into `public_html`.

#### Enable Automatic Deployment (Continuous Deployment)
- On the Git page in hPanel, find your deployed repository and click **Auto Deployment** or copy the **Webhook URL**.
- In GitHub, go to your repo &rarr; **Settings** &rarr; **Webhooks** &rarr; **Add webhook**:
  - **Payload URL**: Paste the Hostinger Webhook URL.
  - **Content type**: `application/json`
  - Select **Just the push event**.
  - Click **Add webhook**.
- *Now, every time you `git push` from your local machine, Hostinger will automatically update your live site within seconds!*

---

### Step 5: Create Production `.env` File on Hostinger
Since `.env` contains confidential database and mail credentials, it is not pushed to GitHub. You must create it once on the server:
1. In hPanel, go to **Files** &rarr; **File Manager** &rarr; enter `public_html/`.
2. Click **New File**, name it `.env`.
3. Paste the following configuration, replacing the values with your actual Hostinger credentials:

```ini
APP_NAME="OM Trip and Travels"
APP_URL=https://yourdomain.com
APP_ENV=production
APP_DEBUG=false

# Hostinger MySQL Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=u123456789_omtrip
DB_USER=u123456789_omtripuser
DB_PASS=YourStrongDatabasePassword123!

# Hostinger Webmail / SMTP (hPanel -> Emails -> Email Accounts)
SMTP_HOST=smtp.hostinger.com
SMTP_PORT=465
SMTP_USER=info@yourdomain.com
SMTP_PASS=YourEmailPassword123!
SMTP_FROM=info@yourdomain.com
ADMIN_NOTIFY_EMAIL=admin@yourdomain.com

# Business Contact details for Call & WhatsApp CTAs
WHATSAPP_NUMBER=919826012345
PHONE_NUMBER="+91 98260 12345"

# Optional Cloudflare Turnstile (leave blank to disable)
TURNSTILE_SITE_KEY=
TURNSTILE_SECRET_KEY=
```
4. Click **Save & Close**.

---

### Step 6: Verify Permissions & Document Root
1. **Directory Permissions**:
   In File Manager, ensure the following folders have write permissions (`755` or `775`):
   - `storage/logs/`
   - `storage/rate_limits/`
   - `public/uploads/`
2. **Document Root / .htaccess**:
   - The repository includes a production-ready root `.htaccess` file inside `public_html/`.
   - It automatically routes web traffic to `/public` and blocks browser access to `app/`, `database/`, `storage/`, and `.env`.
   - Alternatively, if your hPanel allows changing the Document Root under **Websites &rarr; Manage &rarr; Website Settings**, you can point Document Root directly to `public_html/public`.

---

### Step 7: SSL & PHP Configuration
1. In hPanel, navigate to **Security** &rarr; **SSL**:
   - Ensure the free **Let's Encrypt SSL** is active for your domain.
   - Enable **Force HTTPS**.
2. In hPanel, go to **Advanced** &rarr; **PHP Configuration**:
   - Select **PHP 8.1**, **PHP 8.2**, or **PHP 8.3**.
   - Under **PHP Extensions**, ensure `pdo_mysql`, `gd`, `curl`, and `mbstring` are enabled (default on Hostinger).

---

### Step 8: Login to Admin Panel
1. Visit your live site: `https://yourdomain.com`
2. Open the Admin Panel: `https://yourdomain.com/admin/login`
3. Log in with the pre-seeded Super Admin account:
   - **Email**: `admin@omtripandtravels.com`
   - **Password**: `Admin@123456`
4. Once logged in:
   - Go to your profile / **Change Password** to set your personal secure password.
   - Go to **Settings** to update your business address, phone, WhatsApp number, and social links.
   - Test the **"Test SMTP Connection"** button to confirm Hostinger mail delivery.

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

