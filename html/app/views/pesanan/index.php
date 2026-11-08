<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-box-seam text-primary me-2"></i>Riwayat Pesanan Saya</h3>
            <p class="text-muted mb-0">Pantau proses pembayaran, pengiriman, dan riwayat pesanan Anda.</p>
        </div>
        <a href="<?= BASE_URL ?>/produk/getProduk" class="btn btn-outline-primary">
            <i class="bi bi-bag-plus me-1"></i>Belanja Lagi
        </a>
    </div>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <?php if (empty($data['pesanan'])): ?>
        <div class="card shadow-sm border-0 text-center py-5">
            <div class="card-body">
                <i class="bi bi-bag-x text-muted" style="font-size: 4rem;"></i>
                <h5 class="fw-bold mt-3">Belum Ada Pesanan</h5>
                <p class="text-muted">Anda belum melakukan pemesanan produk apa pun.</p>
                <a href="<?= BASE_URL ?>/produk/getProduk" class="btn btn-primary mt-2">
                    <i class="bi bi-grid me-1"></i>Lihat Katalog Produk
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($data['pesanan'] as $p): ?>
                <?php
                    $badgeClass = 'bg-secondary';
                    $statusText = $p['status_pesanan'];
                    if ($statusText === 'menunggu_pembayaran') { $badgeClass = 'bg-warning text-dark'; }
                    elseif ($statusText === 'diproses') { $badgeClass = 'bg-info text-dark'; }
                    elseif ($statusText === 'dikirim') { $badgeClass = 'bg-primary'; }
                    elseif ($statusText === 'selesai') { $badgeClass = 'bg-success'; }
                    elseif ($statusText === 'dibatalkan') { $badgeClass = 'bg-danger'; }
                ?>
                <div class="col-12 mb-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom">
                            <div>
                                <span class="fw-bold me-2">Pesanan #<?= htmlspecialchars($p['id_pesanan']) ?></span>
                                <span class="text-muted small me-2"><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($p['tgl_pesanan']) ?></span>
                                <span class="badge <?= $badgeClass ?> text-uppercase"><?= str_replace('_', ' ', htmlspecialchars($statusText)) ?></span>
                            </div>
                            <div>
                                <span class="text-muted small">Total Tagihan: </span>
                                <span class="fw-bold text-primary fs-6">Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-7 mb-3 mb-md-0">
                                    <div class="small text-muted mb-1"><i class="bi bi-geo-alt me-1"></i>Alamat Pengiriman:</div>
                                    <p class="mb-2 text-secondary small"><?= htmlspecialchars($p['alamat_pengiriman']) ?></p>

                                    <?php if (!empty($p['no_resi'])): ?>
                                        <div class="small text-muted">
                                            <i class="bi bi-truck me-1"></i>No. Resi: <strong class="text-dark"><?= htmlspecialchars($p['no_resi']) ?></strong>
                                            (Status: <span class="badge bg-light text-dark border"><?= htmlspecialchars($p['status_kirim']) ?></span>)
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-5 text-md-end d-flex flex-wrap justify-content-md-end gap-2">
                                    <a href="<?= BASE_URL ?>/pesanan/lacak/<?= htmlspecialchars($p['id_pesanan']) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-geo me-1"></i>Lacak Pesanan
                                    </a>

                                    <?php if ($statusText === 'menunggu_pembayaran'): ?>
                                        <a href="<?= BASE_URL ?>/pembayaran/index/<?= htmlspecialchars($p['id_pesanan']) ?>" class="btn btn-sm btn-warning fw-bold">
                                            <i class="bi bi-credit-card me-1"></i>Bayar Sekarang
                                        </a>
                                    <?php elseif ($statusText === 'dikirim'): ?>
                                        <a href="<?= BASE_URL ?>/pesanan/konfirmasi/<?= htmlspecialchars($p['id_pesanan']) ?>" class="btn btn-sm btn-success fw-bold" onclick="return confirm('Apakah Anda yakin paket telah diterima dengan baik?')">
                                            <i class="bi bi-check2-circle me-1"></i>Konfirmasi Terima
                                        </a>
                                    <?php elseif ($statusText === 'selesai'): ?>
                                        <a href="<?= BASE_URL ?>/ulasan/index/<?= htmlspecialchars($p['id_pesanan']) ?>" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-star me-1"></i>Beri Ulasan
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
