<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <!-- Header Admin -->
    <div class="card shadow-sm border-0 mb-4 bg-dark text-white">
        <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <span class="badge bg-danger mb-2"><i class="bi bi-shield-lock me-1"></i>Portal Administrator</span>
                <h3 class="fw-bold mb-1">Panel Kendali Sistem E-Commerce</h3>
                <p class="mb-0 text-white-50">Pengawasan pengguna, moderasi laporan pelanggaran, dan simulasi payment gateway.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-people me-1"></i>Kelola User
                </a>
                <a href="<?= BASE_URL ?>/admin/pelanggaran" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-flag me-1"></i>Laporan Pelanggaran
                </a>
                <a href="<?= BASE_URL ?>/admin/transaksi" class="btn btn-primary btn-sm">
                    <i class="bi bi-cpu me-1"></i>Gateway Simulator
                </a>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Total Pengguna Terdaftar</div>
                    <h3 class="fw-bold text-primary mb-2"><?= htmlspecialchars($data['total_users']) ?> Akun</h3>
                    <a href="<?= BASE_URL ?>/admin/users" class="small text-decoration-none">Kelola Pengguna (UC 21) &rarr;</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 border-start border-danger border-4">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Laporan Pelanggaran</div>
                    <h3 class="fw-bold text-danger mb-2"><?= htmlspecialchars($data['total_pelanggaran']) ?> Kasus</h3>
                    <a href="<?= BASE_URL ?>/admin/pelanggaran" class="small text-decoration-none">Moderasi Laporan (UC 20) &rarr;</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Transaksi Pembayaran</div>
                    <h3 class="fw-bold text-success mb-2"><?= htmlspecialchars($data['total_transaksi']) ?> Transaksi</h3>
                    <a href="<?= BASE_URL ?>/admin/transaksi" class="small text-decoration-none">Monitor Gateway (UC 22) &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Pelanggaran Terbaru -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-danger"><i class="bi bi-shield-exclamation me-2"></i>Laporan Pelanggaran Menunggu Tindakan</h6>
                    <a href="<?= BASE_URL ?>/admin/pelanggaran" class="small text-decoration-none">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php if (empty($data['pelanggaran'])): ?>
                            <li class="list-group-item text-center py-4 text-muted">Tidak ada laporan pelanggaran aktif.</li>
                        <?php else: ?>
                            <?php foreach ($data['pelanggaran'] as $p): ?>
                                <li class="list-group-item p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-dark">Laporan #<?= htmlspecialchars($p['id_pelanggaran']) ?></strong>
                                        <span class="badge bg-warning text-dark"><?= htmlspecialchars($p['status']) ?></span>
                                    </div>
                                    <p class="mb-1 small text-secondary"><?= htmlspecialchars($p['deskripsi']) ?></p>
                                    <small class="text-muted">Pelapor: <?= htmlspecialchars($p['nama_pelapor'] ?? 'User') ?></small>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-success"><i class="bi bi-activity me-2"></i>Aktivitas Transaksi Gateway</h6>
                    <a href="<?= BASE_URL ?>/admin/transaksi" class="small text-decoration-none">Monitor Webhook</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php if (empty($data['transaksi'])): ?>
                            <li class="list-group-item text-center py-4 text-muted">Belum ada aktivitas transaksi.</li>
                        <?php else: ?>
                            <?php foreach ($data['transaksi'] as $t): ?>
                                <li class="list-group-item p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-primary">Pesanan #<?= htmlspecialchars($t['id_pesanan']) ?></span>
                                        <span class="badge bg-success-subtle text-success border border-success"><?= htmlspecialchars($t['status_bayar']) ?></span>
                                    </div>
                                    <div class="small text-secondary">
                                        <code><?= htmlspecialchars($t['id_transaksi_gateway'] ?? 'Manual Transfer') ?></code> | <?= htmlspecialchars($t['metode']) ?>
                                    </div>
                                    <small class="text-muted">Pembeli: <?= htmlspecialchars($t['nama_pembeli'] ?? 'User') ?></small>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
