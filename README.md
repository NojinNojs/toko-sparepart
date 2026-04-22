# 🛠️ Toko Sparepart Otomotif (Production Ready)

A premium, production-grade e-commerce application for automotive spare parts built with **Laravel 12 (Blade)** and **Tailwind CSS 4**.

## ✨ Overview & Features

A robust application crafted specifically for high-performance and a premium user experience.

- **Admin Dashboard**: Comprehensive management of products, brands, and categories.
- **Product Catalog**: Beautifully designed responsive grid with filtering and search.
- **Image Cropper**: Integrated Cropper.js for uniform 1:1 square product images.
- **Image Optimization**: Automatic resizing and web compression using PHP GD Library.
- **Responsive Design**: Mobile-first architecture using Tailwind CSS 4.
- **Role-Based Access**: Secure separation between Admin and Customer roles.
- **Transaction System**: Clean, intuitive, and secure checkout flow.

## 🚀 Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates, Alpine.js
- **Styling**: Tailwind CSS 4 (Vite pipeline)
- **Database**: MySQL / MariaDB / PostgreSQL
- **Tools**: Cropper.js, PHP GD Library

---

## 💻 1. Local Development Setup

If you are developing or testing the app on your local machine (using Laragon, XAMPP, or Valet):

### Prerequisites
- PHP >= 8.2 (With `gd` extension enabled)
- Composer
- Node.js & npm
- MySQL / MariaDB

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/NojinNojs/toko-sparepart.git
   cd toko-sparepart
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   > **Note:** Configure your database details (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) in the `.env` file. Adjust `APP_URL` to match your local setup (e.g., `http://localhost/toko-sparepart/public` if using raw apache/laragon).

4. **Prepare Storage & Database**
   ```bash
   php artisan storage:link
   php artisan migrate:fresh --seed
   ```
   > **Note:** The seeder is "Production Grade". It will automatically generate professional fallback images and copy local seed images — **no internet connection required** for seeding!

5. **Start Development Servers**
   ```bash
   # Terminal 1 - For serving the app (Option A: If not using virtual host)
   php artisan serve
   
   # Terminal 2 - To compile Tailwind CSS on the fly
   npm run dev
   ```

---

## 🌐 2. Production Deployment (Server / Hosting)

The application is **100% Production-Ready**. Follow these exact steps when deploying to a live server (VPS / Shared Hosting / cPanel).

### Deployment Checklist

1. **Clone & Install**
   Pull the code to your server `public_html` or `/var/www/` directory. Run:
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install
   ```

2. **Build Public Assets (Crucial)**
   Because `.gitignore` ignores the `public/build` directory by default, you **MUST** compile the assets on your production server so the website styles load:
   ```bash
   npm run build
   ```

3. **Configure `.env` for Production**
   Open your `.env` on the server and ensure these critical values are set:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domain-anda.com

   # Configure your real production database:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_db_produksi
   DB_USERNAME=user_db
   DB_PASSWORD=password_db
   ```
   > ⚠️ **CRITICAL:** `APP_DEBUG=false` ensures your system errors are securely hidden from public visitors.

4. **Database & Storage**
   ```bash
   php artisan migrate --force
   # Run the seeder ONLY if this is a brand new fresh installation:
   # php artisan db:seed --force 

   php artisan storage:link
   ```

5. **Optimize System Cache**
   Run these commands to drastically boost Laravel's performance in production:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## 🔐 Credentials (Seeder generated)

If you ran the seeder, use these accounts to login:

- **Admin Account**: 
  - Email: `admin@tokosparepart.com`
  - Password: `password`
- **Customer Account**: 
  - Email: `budi@example.com`
  - Password: `password`

## 📄 Built In Testing

This repository is strictly tested. We maintain 100% passing tests.
To run the automated test suite locally:
```bash
php artisan test
```

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
