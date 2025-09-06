<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

# Manajemen Inventori Gudang

![GitHub repo size](https://img.shields.io/github/repo-size/rzranz/manajemen-inventori-gudang)
![GitHub last commit](https://img.shields.io/github/last-commit/rzranz/manajemen-inventori-gudang)
![GitHub license](https://img.shields.io/github/license/rzranz/manajemen-inventori-gudang)

Sistem **Manajemen Inventori Gudang** menggunakan **Laravel** (backend) dan **Vue 3 + Tailwind CSS** (frontend).  
Aplikasi ini membantu mengelola produk, stok, dan transaksi secara efisien.

---

## 📌 Daftar Isi

- [Fitur](#-fitur)
- [Struktur Project](#-struktur-project)
- [Instalasi](#-instalasi)
- [Catatan](#-catatan)
- [Instalasi](#-Instalasi)
- [Lisensi](#-lisensi)

---

## ✨ Fitur

- **Manajemen Produk**
  - Tambah, lihat, update produk
  - Hapus produk hanya oleh admin melalui **Filament Admin**
- **Manajemen Transaksi**
  - Melacak transaksi masuk/keluar
- **Kategori Produk**
  - Produk dikelompokkan berdasarkan kategori
- **Dashboard Interaktif**
  - Total produk, total stok, total transaksi
  - Chart stok produk & tren transaksi
  - Daftar produk stok rendah
- **Responsif & Modern**
  - Mobile dan desktop friendly (Tailwind CSS)
- **Dark/Light Mode (opsional)**

---

## 🗂 Struktur Project

manajemen-inventori-gudang/

├── app/ # Backend Laravel

├── database/ # Migrations & seeders

├── public/ # Aset publik

├── resources/js/ # Frontend Vue 3

│ ├── components/

│ ├── layouts/

│ └── router/

├── routes/

├── tailwind.config.js

└── README.md


## 💻 Instalasi

### 1. Clone repository

git clone https://github.com/rzranz/manajemen-inventori-gudang.git
cd manajemen-inventori-gudang
If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

### 1. Clone repository
git clone https://github.com/rzranz/manajemen-inventori-gudang.git
cd manajemen-inventori-gudang

### 2. Install dependencies Laravel (backend)
composer install

### 3. Install dependencies frontend (Vue 3 + TailwindCSS)
npm install
npm run dev

### 4. Konfigurasi environment
cp .env.example .env   # Linux/Mac
copy .env.example .env # Windows CMD

php artisan key:generate

### 5. Migrasi database
php artisan migrate
php artisan db:seed

### 6. Jalankan project
npm run dev
php artisan serve

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
