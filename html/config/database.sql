-- =====================================================================
-- DDL & SEED DATA SISTEM E-COMMERCE (KELOMPOK 1 RSI)
-- Berdasarkan ERD Class Data Diagram yang telah disempurnakan
-- =====================================================================

DROP DATABASE IF EXISTS `db_ecommerce_rsi`;
CREATE DATABASE `db_ecommerce_rsi` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE `db_ecommerce_rsi`;

-- ---------------------------------------------------------------------
-- 1. Tabel: user
-- Catatan: password VARCHAR(255) untuk menampung Bcrypt/Argon2 hash
-- ---------------------------------------------------------------------
CREATE TABLE `user` (
  `id_user` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('pembeli', 'penjual', 'admin') NOT NULL DEFAULT 'pembeli',
  `tgl_daftar` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 2. Tabel: toko
-- Relasi: 1 User (role penjual) memiliki 1 Toko
-- ---------------------------------------------------------------------
CREATE TABLE `toko` (
  `id_toko` INT AUTO_INCREMENT PRIMARY KEY,
  `id_user` INT NOT NULL,
  `nama_toko` VARCHAR(100) NOT NULL,
  `deskripsi` TEXT DEFAULT NULL,
  `saldo` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_toko_user` FOREIGN KEY (`id_user`) 
    REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 3. Tabel: saldo_toko (Mutasi/Riwayat Keuangan Toko)
-- ---------------------------------------------------------------------
CREATE TABLE `saldo_toko` (
  `id_saldo` INT AUTO_INCREMENT PRIMARY KEY,
  `id_toko` INT NOT NULL,
  `jumlah` DECIMAL(12,2) NOT NULL,
  `keterangan` VARCHAR(255) DEFAULT NULL,
  `tgl` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipe` ENUM('masuk', 'keluar') NOT NULL,
  CONSTRAINT `fk_saldo_toko_toko` FOREIGN KEY (`id_toko`) 
    REFERENCES `toko` (`id_toko`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 4. Tabel: produk
-- Catatan: FK id_keranjang DIHAPUS dari tabel produk untuk mencegah
-- circular reference; hubungan produk ke keranjang ada di tabel keranjang.
-- ---------------------------------------------------------------------
CREATE TABLE `produk` (
  `id_produk` INT AUTO_INCREMENT PRIMARY KEY,
  `id_toko` INT NOT NULL,
  `nama_produk` VARCHAR(150) NOT NULL,
  `kategori` VARCHAR(100) NOT NULL,
  `harga` DECIMAL(12,2) NOT NULL,
  `stok` INT NOT NULL DEFAULT 0,
  `deskripsi` TEXT DEFAULT NULL,
  `gambar` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_produk_toko` FOREIGN KEY (`id_toko`) 
    REFERENCES `toko` (`id_toko`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 5. Tabel: keranjang
-- Menampung item belanja sementara milik user
-- ---------------------------------------------------------------------
CREATE TABLE `keranjang` (
  `id_keranjang` INT AUTO_INCREMENT PRIMARY KEY,
  `id_user` INT NOT NULL,
  `id_produk` INT NOT NULL,
  `jumlah` INT NOT NULL DEFAULT 1,
  `tgl_tambah` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_keranjang_user` FOREIGN KEY (`id_user`) 
    REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_keranjang_produk` FOREIGN KEY (`id_produk`) 
    REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 6. Tabel: pesanan (Header Checkout)
-- ---------------------------------------------------------------------
CREATE TABLE `pesanan` (
  `id_pesanan` INT AUTO_INCREMENT PRIMARY KEY,
  `id_user` INT NOT NULL,
  `alamat_pengiriman` TEXT NOT NULL,
  `status_pesanan` ENUM('menunggu_pembayaran', 'diproses', 'dikirim', 'selesai', 'dibatalkan') 
    NOT NULL DEFAULT 'menunggu_pembayaran',
  `tgl_pesanan` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_harga` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  CONSTRAINT `fk_pesanan_user` FOREIGN KEY (`id_user`) 
    REFERENCES `user` (`id_user`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 7. Tabel: detail_pesanan (Item Produk dalam Pesanan)
-- ---------------------------------------------------------------------
CREATE TABLE `detail_pesanan` (
  `id_detail` INT AUTO_INCREMENT PRIMARY KEY,
  `id_pesanan` INT NOT NULL,
  `id_produk` INT NOT NULL,
  `harga_satuan` DECIMAL(12,2) NOT NULL,
  `jumlah` INT NOT NULL DEFAULT 1,
  CONSTRAINT `fk_detail_pesanan` FOREIGN KEY (`id_pesanan`) 
    REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detail_produk` FOREIGN KEY (`id_produk`) 
    REFERENCES `produk` (`id_produk`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 8. Tabel: pembayaran
-- ---------------------------------------------------------------------
CREATE TABLE `pembayaran` (
  `id_pembayaran` INT AUTO_INCREMENT PRIMARY KEY,
  `id_pesanan` INT NOT NULL,
  `id_transaksi_gateway` VARCHAR(100) DEFAULT NULL,
  `metode` VARCHAR(50) NOT NULL,
  `status_bayar` ENUM('pending', 'berhasil', 'gagal') NOT NULL DEFAULT 'pending',
  `tgl_bayar` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_pembayaran_pesanan` FOREIGN KEY (`id_pesanan`) 
    REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 9. Tabel: pengiriman
-- Mendukung pelacakan resi per toko dalam satu pesanan
-- ---------------------------------------------------------------------
CREATE TABLE `pengiriman` (
  `id_pengiriman` INT AUTO_INCREMENT PRIMARY KEY,
  `id_pesanan` INT NOT NULL,
  `id_toko` INT NOT NULL,
  `no_resi` VARCHAR(100) DEFAULT NULL,
  `status_kirim` ENUM('menunggu_pickup', 'dalam_perjalanan', 'terkirim') 
    NOT NULL DEFAULT 'menunggu_pickup',
  `tgl_kirim` DATETIME DEFAULT NULL,
  CONSTRAINT `fk_pengiriman_pesanan` FOREIGN KEY (`id_pesanan`) 
    REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pengiriman_toko` FOREIGN KEY (`id_toko`) 
    REFERENCES `toko` (`id_toko`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 10. Tabel: ulasan
-- Review pasca pembelian
-- ---------------------------------------------------------------------
CREATE TABLE `ulasan` (
  `id_ulasan` INT AUTO_INCREMENT PRIMARY KEY,
  `id_pesanan` INT NOT NULL,
  `id_produk` INT NOT NULL,
  `id_user` INT NOT NULL,
  `komentar` TEXT DEFAULT NULL,
  `rating` TINYINT NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `tgl_ulasan` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_ulasan_pesanan` FOREIGN KEY (`id_pesanan`) 
    REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ulasan_produk` FOREIGN KEY (`id_produk`) 
    REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ulasan_user` FOREIGN KEY (`id_user`) 
    REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 11. Tabel: pelanggaran
-- Laporan user mengenai produk, toko, atau akun lain (FK nullable)
-- ---------------------------------------------------------------------
CREATE TABLE `pelanggaran` (
  `id_pelanggaran` INT AUTO_INCREMENT PRIMARY KEY,
  `id_produk` INT DEFAULT NULL,
  `id_toko` INT DEFAULT NULL,
  `id_user` INT NOT NULL,
  `deskripsi` TEXT NOT NULL,
  `status` ENUM('menunggu_review', 'disetujui', 'ditolak') 
    NOT NULL DEFAULT 'menunggu_review',
  `tgl_lapor` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_pelanggaran_produk` FOREIGN KEY (`id_produk`) 
    REFERENCES `produk` (`id_produk`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_pelanggaran_toko` FOREIGN KEY (`id_toko`) 
    REFERENCES `toko` (`id_toko`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_pelanggaran_user` FOREIGN KEY (`id_user`) 
    REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- DATA SEEDER AWAL (UNTUK TESTING & DEMO)
-- Password default semua user adalah: password123
-- (Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)
-- =====================================================================

-- 1. Seed User
INSERT INTO `user` (`id_user`, `nama`, `email`, `password`, `role`) VALUES
(1, 'Administrator', 'admin@rsi.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(2, 'Budi Santoso', 'budi@penjual.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'penjual'),
(3, 'Siti Nurhaliza', 'siti@pembeli.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pembeli');

-- 2. Seed Toko
INSERT INTO `toko` (`id_toko`, `id_user`, `nama_toko`, `deskripsi`, `saldo`) VALUES
(1, 2, 'Budi Tech Store', 'Pusat periferal dan perangkat keras komputer terlengkap & terpercaya.', 5000000.00);

-- 3. Seed Saldo Toko (Mutasi Awal)
INSERT INTO `saldo_toko` (`id_toko`, `jumlah`, `keterangan`, `tipe`) VALUES
(1, 5000000.00, 'Saldo awal pembukaan toko', 'masuk');

-- 4. Seed Produk
INSERT INTO `produk` (`id_produk`, `id_toko`, `nama_produk`, `kategori`, `harga`, `stok`, `deskripsi`, `gambar`) VALUES
(1, 1, 'Laptop Gaming Asus ROG Strix G16', 'Laptop', 22500000.00, 7, 'Intel Core i7 Gen 13, RTX 4060, 16GB RAM DDR5, 1TB NVMe SSD, Layar FHD+ 165Hz.', 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=600&auto=format&fit=crop&q=80'),
(2, 1, 'Keychron K2 V2 Wireless Keyboard', 'Aksesoris', 1350000.00, 15, 'Keyboard mekanikal compact 75% Bluetooth & USB-C, RGB Backlight, Gateron Brown Switch.', 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=600&auto=format&fit=crop&q=80'),
(3, 1, 'Logitech MX Master 3S Wireless Mouse', 'Aksesoris', 1650000.00, 10, 'Sensor 8K DPI Darkfield, Quiet Clicks, Ergonomic Hand Rest, MagSpeed Scrolling.', 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=600&auto=format&fit=crop&q=80'),
(4, 1, 'Monitor Xiaomi Curved Gaming 34 Inch', 'Monitor', 4899000.00, 4, 'Resolusi WQHD 3440 x 1440, Kelengkungan 1500R, Refresh Rate 144Hz, AMD FreeSync.', 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=600&auto=format&fit=crop&q=80');

-- 5. Seed Keranjang (Siti memasukkan Keychron K2)
INSERT INTO `keranjang` (`id_user`, `id_produk`, `jumlah`) VALUES
(3, 2, 1);

-- 6. Seed Contoh Pesanan Selesai
INSERT INTO `pesanan` (`id_pesanan`, `id_user`, `alamat_pengiriman`, `status_pesanan`, `total_harga`) VALUES
(1, 3, 'Jl. Telekomunikasi No. 1, Sukapura, Kec. Dayeuhkolot, Bandung, Jawa Barat 40257', 'selesai', 1650000.00);

-- 7. Detail Pesanan
INSERT INTO `detail_pesanan` (`id_pesanan`, `id_produk`, `harga_satuan`, `jumlah`) VALUES
(1, 3, 1650000.00, 1);

-- 8. Pembayaran Pesanan
INSERT INTO `pembayaran` (`id_pesanan`, `id_transaksi_gateway`, `metode`, `status_bayar`) VALUES
(1, 'GW-20261108-001', 'Bank Transfer BCA', 'berhasil');

-- 9. Pengiriman Pesanan
INSERT INTO `pengiriman` (`id_pesanan`, `id_toko`, `no_resi`, `status_kirim`, `tgl_kirim`) VALUES
(1, 1, 'JNE-REG-882910394', 'terkirim', NOW());

-- 10. Ulasan Produk
INSERT INTO `ulasan` (`id_pesanan`, `id_produk`, `id_user`, `komentar`, `rating`) VALUES
(1, 3, 3, 'Barang sangat original, scroll wheel sangat halus dan nyaman untuk kerja seharian.', 5);
