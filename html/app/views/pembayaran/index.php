<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-credit-card me-2"></i>Pembayaran Pesanan #<?= htmlspecialchars($data['id_pesanan']) ?></h5>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($_SESSION['flash_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($_SESSION['flash_error']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['flash_error']); ?>
                    <?php endif; ?>

                    <div class="border rounded p-3 mb-4 bg-light">
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted d-block">ID Pesanan</small>
                                <span class="fw-bold">#<?= htmlspecialchars($data['id_pesanan']) ?></span>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted d-block">Total Tagihan</small>
                                <span class="fw-bold text-primary fs-5">Rp <?= number_format($data['pesanan']['total_harga'] ?? 0, 0, ',', '.') ?></span>
                            </div>
                            <div class="col-12 mt-2">
                                <small class="text-muted d-block">Alamat Pengiriman</small>
                                <span><?= htmlspecialchars($data['pesanan']['alamat_pengiriman'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3"><i class="bi bi-wallet2 me-2 text-primary"></i>Pilih Metode Pembayaran</h6>
                    
                    <form action="<?= BASE_URL ?>/pembayaran/bayar" method="POST">
                        <input type="hidden" name="id_pesanan" value="<?= htmlspecialchars($data['id_pesanan']) ?>">

                        <div class="list-group mb-4">
                            <label class="list-group-item list-group-item-action d-flex align-items-center p-3">
                                <input class="form-check-input me-3" type="radio" name="metode" value="Bank Transfer BCA" checked>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Transfer Bank BCA (Virtual Account)</div>
                                    <small class="text-muted">Verifikasi instan via Payment Gateway</small>
                                </div>
                                <span class="badge bg-primary">Otomatis</span>
                            </label>

                            <label class="list-group-item list-group-item-action d-flex align-items-center p-3">
                                <input class="form-check-input me-3" type="radio" name="metode" value="QRIS / GoPay / OVO">
                                <div class="flex-grow-1">
                                    <div class="fw-bold">QRIS Universal (GoPay, OVO, Dana, ShopeePay)</div>
                                    <small class="text-muted">Scan kode QR langsung dari aplikasi e-wallet</small>
                                </div>
                                <span class="badge bg-success">Instant</span>
                            </label>

                            <label class="list-group-item list-group-item-action d-flex align-items-center p-3">
                                <input class="form-check-input me-3" type="radio" name="metode" value="Bank Mandiri / BNI">
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Transfer Bank Mandiri / BNI</div>
                                    <small class="text-muted">ATM, Mobile Banking, Internet Banking</small>
                                </div>
                            </label>

                            <label class="list-group-item list-group-item-action d-flex align-items-center p-3">
                                <input class="form-check-input me-3" type="radio" name="metode" value="COD (Cash on Delivery)">
                                <div class="flex-grow-1">
                                    <div class="fw-bold">COD (Bayar di Tempat)</div>
                                    <small class="text-muted">Bayar tunai kepada kurir saat pesanan tiba</small>
                                </div>
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                <i class="bi bi-shield-check me-2"></i>Konfirmasi & Bayar Sekarang
                            </button>
                            <a href="<?= BASE_URL ?>/pesanan" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Riwayat Pesanan
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
