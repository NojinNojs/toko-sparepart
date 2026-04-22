# 🛠️ Toko Sparepart Otomotif (Mellblue)

A premium, production-grade e-commerce application for automotive spare parts built with **Laravel 12 (Blade)** and **Tailwind CSS 4**.

![Admin Dashboard](https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&q=80&w=1200)

## ✨ Features

- **Admin Dashboard**: Comprehensive management of products, brands, and categories.
- **Product Catalog**: Beautifully designed responsive grid with filtering and search.
- **Image Cropper**: Integrated Cropper.js for uniform 1:1 square product images.
- **Image Optimization**: Automatic resizing and web optimization (GD Library).
- **Responsive Design**: Mobile-first design using Tailwind CSS 4.
- **Role-Based Access**: Secure separation between Admin and Customer roles.
- **Transaction System**: Simple and intuitive checkout flow.

## 🚀 Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates, Alpine.js
- **Styling**: Tailwind CSS 4 (Vanguard)
- **Database**: MySQL / PostgreSQL
- **Tools**: Cropper.js, PHP GD Library

## 📦 Installation

Follow these steps to set up the project locally:

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd toko-sparepart
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   # Update your DB_DATABASE, DB_USERNAME, and DB_PASSWORD in .env
   php artisan key:generate
   ```

4. **Prepare Storage**
   ```bash
   php artisan storage:link
   ```

5. **Run Migrations & Seeders**
   ```bash
   # This will populate categories, brands, and products with real images
   php artisan migrate --seed
   ```

6. **Start the server**
   ```bash
   php artisan serve
   npm run dev
   ```

## 🔐 Credentials (Seeder)

- **Admin**: `admin@tokosparepart.com` / `password`
- **Customer**: `budi@example.com` / `password`

## 📸 Image Management

The system enforces a **1:1 aspect ratio** for all product images using an interactive cropper during the upload process. This ensures that the storefront remains consistent and professional.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
