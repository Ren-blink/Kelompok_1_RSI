<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'E-Commerce Kelompok 1 RSI') ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<!-- Navbar Sesuai Integrasi Kelompok 1 RSI -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="<?= BASE_URL ?>">
            <i class="bi bi-shop me-2 text-primary"></i>Kelompok 1 <span class="text-white">RSI Store</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>"><i class="bi bi-house-door me-1"></i>Homepage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/produk/getProduk"><i class="bi bi-grid me-1"></i>Daftar Produk</a>
                </li>
                <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/pesanan"><i class="bi bi-box-seam me-1"></i>Pesanan Saya</a>
                </li>
                <?php endif; ?>

                <!-- Menu Penjual / Toko (Dicky & Salomo) — hanya penjual & admin -->
                <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['penjual', 'admin'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-shop me-1 text-info"></i>Portal Toko
                    </a>
                    <ul class="dropdown-menu shadow">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/toko"><i class="bi bi-speedometer2 me-2"></i>Dashboard Toko</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/toko/produk"><i class="bi bi-box-seam me-2"></i>Kelola Produk (CRUD)</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/toko/pesanan"><i class="bi bi-truck me-2"></i>Pesanan & Pengiriman</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/toko/laporan"><i class="bi bi-graph-up me-2"></i>Laporan Penjualan</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/toko/saldo"><i class="bi bi-wallet2 me-2"></i>Saldo & Tarik Dana</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/toko/profil"><i class="bi bi-gear me-2"></i>Profil Toko</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Menu Admin (Rajwa) — hanya admin -->
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-warning" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-shield-lock me-1"></i>Admin
                    </a>
                    <ul class="dropdown-menu shadow">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin"><i class="bi bi-pie-chart me-2"></i>Dashboard Admin</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/users"><i class="bi bi-people me-2"></i>Kelola Pengguna</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/pelanggaran"><i class="bi bi-flag me-2 text-danger"></i>Moderasi Pelanggaran</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/transaksi"><i class="bi bi-cpu me-2 text-success"></i>Gateway Simulator</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item me-3">
                    <a class="btn btn-outline-light position-relative" href="<?= BASE_URL ?>/keranjang/getIsiKeranjang">
                        <i class="bi bi-cart3 me-1"></i>Keranjang
                    </a>
                </li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5 me-1 text-primary"></i> <?= htmlspecialchars($_SESSION['user']['nama']) ?>
                            <span class="badge bg-secondary ms-2"><?= htmlspecialchars($_SESSION['user']['role']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>/account/getProfile">
                                    <i class="bi bi-person me-2 text-primary"></i>Laman Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>/pelanggaran">
                                    <i class="bi bi-flag me-2 text-danger"></i>Laporkan Pelanggaran
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>/account/logout">
                                    <i class="bi bi-box-arrow-right me-2 text-danger"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm px-3" href="<?= BASE_URL ?>/account/login">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-outline-secondary btn-sm px-3 text-white" href="<?= BASE_URL ?>/account/register">
                            Daftar
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="flex-grow-1 py-4">
