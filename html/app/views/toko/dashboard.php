<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <!-- Header Toko -->
    <div class="card shadow-sm border-0 mb-4 bg-primary text-white">
        <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <span class="badge bg-light text-primary mb-2"><i class="bi bi-shop me-1"></i>Portal Merchant / Penjual</span>
                <h3 class="fw-bold mb-1"><?= htmlspecialchars($data['toko']['nama_toko'] ?? 'Toko Saya') ?></h3>
                <p class="mb-0 opacity-75"><?= htmlspecialchars($data['toko']['deskripsi'] ?? 'Kelola katalog produk, pesanan pembeli, dan keuangan toko.') ?></p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL ?>/toko/profil" class="btn btn-light text-primary fw-bold">
                    <i class="bi bi-gear-fill me-1"></i>Pengaturan Toko
                </a>
                <a href="<?= BASE_URL ?>/toko/tambah_produk" class="btn btn-warning text-dark fw-bold">
                    <i class="bi bi-plus-circle-fill me-1"></i>Tambah Produk
                </a>
            </div>
        </div>
    </div>

    <!-- Statistik Ringkas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Saldo Aktif Toko</div>
                    <h4 class="fw-bold text-primary mb-2">Rp <?= number_format($data['toko']['saldo'] ?? 0, 0, ',', '.') ?></h4>
                    <a href="<?= BASE_URL ?>/toko/saldo" class="small text-decoration-none">Kelola Saldo &rarr;</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Total Omset Penjualan</div>
                    <h4 class="fw-bold text-success mb-2">Rp <?= number_format($data['laporan']['total_omset'] ?? 0, 0, ',', '.') ?></h4>
                    <a href="<?= BASE_URL ?>/toko/laporan" class="small text-decoration-none">Lihat Laporan &rarr;</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 h-100 border-start border-warning border-4">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Pesanan Masuk</div>
                    <h4 class="fw-bold text-warning mb-2"><?= htmlspecialchars($data['total_pesanan']) ?></h4>
                    <a href="<?= BASE_URL ?>/toko/pesanan" class="small text-decoration-none">Proses Pesanan &rarr;</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 h-100 border-start border-info border-4">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Katalog Produk</div>
                    <h4 class="fw-bold text-info mb-2"><?= htmlspecialchars($data['total_produk']) ?> Item</h4>
                    <a href="<?= BASE_URL ?>/toko/produk" class="small text-decoration-none">Kelola Produk &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigasi Menu Cepat -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-box-seam text-primary fs-1 mb-2"></i>
                    <h5 class="fw-bold">Manajemen Produk (CRUD)</h5>
                    <p class="text-muted small">Tambah produk baru, ubah rincian harga/stok, atau hapus produk dari katalog toko.</p>
                    <a href="<?= BASE_URL ?>/toko/produk" class="btn btn-outline-primary btn-sm">Buka Daftar Produk</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-truck text-success fs-1 mb-2"></i>
                    <h5 class="fw-bold">Pesanan & Pengiriman</h5>
                    <p class="text-muted small">Terima pesanan masuk pembeli, input nomor resi pengiriman, dan atur kurir logistik.</p>
                    <a href="<?= BASE_URL ?>/toko/pesanan" class="btn btn-outline-success btn-sm">Buka Pesanan Masuk</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-cash-stack text-warning fs-1 mb-2"></i>
                    <h5 class="fw-bold">Keuangan & Tarik Saldo</h5>
                    <p class="text-muted small">Pantau mutasi pemasukan toko dan ajukan penarikan saldo pendapatan ke rekening bank Anda.</p>
                    <a href="<?= BASE_URL ?>/toko/saldo" class="btn btn-outline-warning btn-sm">Kelola Keuangan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
