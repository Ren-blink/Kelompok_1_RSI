# Sistem Informasi E-Commerce - Kelompok 1 RSI

Aplikasi Web E-Commerce berbasis arsitektur **Native MVC (Model-View-Controller)** dengan PHP 8.x dan basis data MySQL, dikembangkan untuk memenuhi syarat praktikum **Rekayasa Sistem Integrasi (RSI)** - Program Studi Sistem Informasi, Fakultas Ilmu Komputer, Universitas Brawijaya.

Aplikasi ini menyatukan seluruh **22 Use Case (UC)** yang dirancang oleh 5 anggota Kelompok 1:
1. **[RN] Rayhan Nauvan Pratama** (UC 02 - UC 06: Kelola Akun, Katalog & Cari Produk, Keranjang Belanja, Checkout Pesanan).
2. **[MS] Moch Syabil Ar Rasyid** (UC 07 - UC 11: Pembayaran, Lacak Pembayaran, Lacak Pesanan, Ulasan & Rating, Konfirmasi Selesai).
3. **[DR] Dicky Rinaldi** (UC 12 - UC 15: Laporkan Pelanggaran, CRUD Produk Toko, Profil Toko, Pesanan Masuk Toko).
4. **[WR] Salomo Wira Arta Manik** (UC 16 - UC 19: Pengiriman & Resi, Laporan Penjualan, Saldo Toko, Tarik Saldo Toko).
5. **[RS] Rajwa Salsabila Putri** (UC 01, UC 20 - UC 22: Autentikasi Pengguna, Moderasi Pelanggaran Admin, Kelola Akun Pengguna, Transaksi Gateway Webhook).

---

## 📁 Struktur Folder Proyek

```text
Kelompok_1_RSI/
├── Dockerfile                  # Image PHP 8.2 + Apache + PDO MySQL + mod_rewrite
├── docker-compose.yml          # Konfigurasi container Web (8090), phpMyAdmin (8091), MySQL (3310)
├── .dockerignore
├── README.md
└── html/
    ├── app/
    │   ├── controllers/        # Pengendali Alur (BCE Control)
    │   │   ├── AccountController.php     # [RN] UC 02: Profil & Kelola Akun
    │   │   ├── AdminController.php       # [RS] UC 20, 21, 22: Panel Administrator & Webhook
    │   │   ├── AuthController.php        # [RS] UC 01: Login / Register alias
    │   │   ├── CartController.php        # [RN] UC 04, 05: Cart alias
    │   │   ├── CheckoutController.php    # [RN] UC 06: Form Alamat & Konfirmasi Checkout
    │   │   ├── HomeController.php        # Beranda & showcase katalog
    │   │   ├── KeranjangController.php   # [RN] UC 04, 05: Keranjang Belanja
    │   │   ├── OrderController.php       # [MS] UC 09, 11: Order controller alias
    │   │   ├── PelanggaranController.php # [DR] UC 12: Form Lapor Pelanggaran
    │   │   ├── PembayaranController.php  # [MS] UC 07, 08: Pembayaran & Pelacakan Status
    │   │   ├── PesananController.php     # [MS] UC 09, 11: Lacak Pengiriman & Konfirmasi Terima
    │   │   ├── ProductController.php     # [RN] UC 03: Product alias
    │   │   ├── ProdukController.php      # [RN] UC 03: Cari & Detail Produk
    │   │   ├── TokoController.php        # [DR & WR] UC 13-19: Portal Merchant Lengkap
    │   │   └── UlasanController.php      # [MS] UC 10: Ulasan & Rating Produk
    │   ├── core/               # Framework Inti (No external heavy frameworks)
    │   │   ├── App.php                   # Dual-mode URL Router (Clean URL & Query String)
    │   │   ├── Controller.php            # Base Controller dengan loader view & model
    │   │   └── Database.php              # PDO Database Wrapper dengan Prepared Statements
    │   ├── models/             # Akses Data & Logika Bisnis (BCE Entity)
    │   │   ├── CartModel.php             # [RN] CartModel alias
    │   │   ├── KeranjangModel.php        # [RN] Keranjang Entity
    │   │   ├── OrderModel.php            # [RN & MS] Pesanan Header & Item Entity
    │   │   ├── PelanggaranModel.php      # [DR & RS] Laporan Pelanggaran Entity
    │   │   ├── PembayaranModel.php       # [MS & RS] Pembayaran & Gateway Callback Entity
    │   │   ├── PengirimanModel.php       # [MS & WR] Resi & Kurir Pengiriman Entity
    │   │   ├── PesananModel.php          # [MS] PesananModel alias
    │   │   ├── ProductModel.php          # [RN] ProductModel alias
    │   │   ├── ProdukModel.php           # [RN & DR] Produk Catalog & Store CRUD
    │   │   ├── TokoModel.php             # [DR & WR] Profil Toko, Mutasi Saldo & Omset
    │   │   ├── UlasanModel.php           # [MS] Ulasan & Rating Entity
    │   │   └── UserModel.php             # [RS & RN] User Auth & Admin Management
    │   └── views/              # Tampilan Antarmuka (BCE Boundary)
    │       ├── account/                  # Login, Register, Profil Pengguna
    │       ├── admin/                    # Dashboard Admin, Users, Pelanggaran, Gateway Simulator
    │       ├── checkout/                 # Formulir Alamat & Konfirmasi
    │       ├── home/                     # Beranda
    │       ├── keranjang/                # Daftar Keranjang Belanja
    │       ├── pelanggaran/              # Formulir Pelaporan Pelanggaran
    │       ├── pembayaran/               # Pembayaran Tagihan & Pelacakan Status
    │       ├── pesanan/                  # Riwayat Pesanan & Timeline Lacak Pengiriman
    │       ├── produk/                   # Daftar Produk & Detail Produk + Ulasan
    │       ├── templates/                # Header (Navbar Komprehensif) & Footer
    │       ├── toko/                     # Dashboard Penjual, CRUD Produk, Order Masuk, Laporan, Saldo
    │       └── ulasan/                   # Formulir Rating Bintang & Ulasan
    ├── config/
    │   ├── config.php          # Deteksi otomatis BASE_URL & Env DB (Host, Port, User, Pass)
    │   └── database.sql        # Skema DDL 11 Entitas yang Disempurnakan + Data Seeder
    └── public/
        ├── .htaccess           # URL Rewrite Engine
        ├── index.php           # Front Controller / Entry Point
        └── css/
            └── style.css       # Desain UI Responsif Bootstrap 5
```

---

## 🚀 Cara Menjalankan Aplikasi

### Opsi A: Menggunakan Docker Compose (Direkomendasikan)
Port yang digunakan telah diisolasi agar tidak bertabrakan dengan server lokal yang sudah ada:
- **Web App**: `http://localhost:8090`
- **phpMyAdmin**: `http://localhost:8091`
- **MySQL**: `localhost:3310` (Database: `db_ecommerce_rsi`, User: `user_rsi`, Pass: `password_rsi_123`)

Jalankan perintah:
```bash
docker compose up -d --build
```
Basis data MySQL dan seeder data `html/config/database.sql` akan otomatis diimpor saat container pertama kali dibuat.

### Opsi B: Menggunakan PHP Built-in Server (Tanpa Docker)
Jika ingin menjalankan langsung menggunakan PHP CLI lokal:
```bash
cd d:\Kel1_RSI\Kelompok_1_RSI\html
php -S localhost:8000 -t public public/index.php
```
Akses melalui browser di `http://localhost:8000`.

---

## 👤 Akun Demo & Data Uji Coba

Semua akun default menggunakan kata sandi: **`password123`**

| Role Pengguna | Email | Nama | Hak Akses Utama |
|---|---|---|---|
| **Administrator** | `admin@rsi.com` | Administrator | Panel Admin, Kelola Akun, Moderasi Pelanggaran, Gateway Webhook Simulator |
| **Penjual (Merchant)** | `budi@penjual.com` | Budi Santoso | Portal Toko "Budi Tech Store", CRUD Produk, Input Resi, Laporan Omset, Tarik Saldo |
| **Pembeli** | `siti@pembeli.com` | Siti Nurhaliza | Cari Produk, Keranjang, Checkout, Bayar Tagihan, Lacak Pengiriman, Beri Ulasan |

---

## 📋 Ringkasan 22 Use Case & Integrasi BCE

| No | Use Case | Perancang | Controller (Control) | Model (Entity) | View (Boundary) |
|---|---|---|---|---|---|
| 01 | Melakukan Autentikasi Pengguna | Rajwa | `AccountController` | `UserModel` | `account/login.php`, `account/register.php` |
| 02 | Kelola Akun / Profil | Rayhan | `AccountController` | `UserModel` | `account/profile.php` |
| 03 | Mencari Produk | Rayhan | `ProdukController` | `ProdukModel` | `produk/daftar_produk.php`, `produk/detail_produk.php` |
| 04 | Memasukkan Produk ke Keranjang | Rayhan | `KeranjangController` | `KeranjangModel` | `produk/detail_produk.php` |
| 05 | Kelola Produk di Keranjang | Rayhan | `KeranjangController` | `KeranjangModel` | `keranjang/index.php` |
| 06 | Melakukan Checkout | Rayhan | `CheckoutController` | `OrderModel` | `checkout/index.php`, `checkout/confirm.php` |
| 07 | Melakukan Pembayaran | Syabil | `PembayaranController` | `PembayaranModel` | `pembayaran/index.php` |
| 08 | Melacak Pembayaran | Syabil | `PembayaranController` | `PembayaranModel` | `pembayaran/lacak.php` |
| 09 | Melacak Pesanan | Syabil | `PesananController` | `PengirimanModel` | `pesanan/lacak.php` |
| 10 | Memberi Ulasan / Rating | Syabil | `UlasanController` | `UlasanModel` | `ulasan/form.php` |
| 11 | Konfirmasi Penerimaan Paket | Syabil | `PesananController` | `OrderModel`, `TokoModel` | `pesanan/index.php`, `pesanan/lacak.php` |
| 12 | Melaporkan Pelanggaran | Dicky | `PelanggaranController` | `PelanggaranModel` | `pelanggaran/buat.php` |
| 13 | Kelola Produk Toko (CRUD) | Dicky | `TokoController` | `ProdukModel` | `toko/produk_list.php`, `toko/produk_form.php` |
| 14 | Kelola Profil Toko | Dicky | `TokoController` | `TokoModel` | `toko/profil.php` |
| 15 | Kelola Order Masuk Toko | Dicky | `TokoController` | `OrderModel` | `toko/pesanan_masuk.php` |
| 16 | Mengajukan / Atur Pengiriman | Salomo | `TokoController` | `PengirimanModel` | `toko/pesanan_masuk.php` |
| 17 | Melihat Laporan Penjualan | Salomo | `TokoController` | `TokoModel` | `toko/laporan.php` |
| 18 | Melihat Saldo Toko | Salomo | `TokoController` | `TokoModel` | `toko/saldo.php` |
| 19 | Tarik Saldo Toko | Salomo | `TokoController` | `TokoModel` | `toko/saldo.php` |
| 20 | Kelola Pelanggaran (Admin) | Rajwa | `AdminController` | `PelanggaranModel` | `admin/pelanggaran.php` |
| 21 | Kelola Akun Pembeli & Merchant | Rajwa | `AdminController` | `UserModel` | `admin/users.php` |
| 22 | Memproses Transaksi Payment Gateway | Rajwa | `AdminController` | `PembayaranModel` | `admin/transaksi.php` |
