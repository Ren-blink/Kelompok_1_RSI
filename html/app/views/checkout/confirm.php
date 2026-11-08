<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7 text-center">
            <!-- Sesuai Sequence Diagram 2: returnConfirmOrder (AWAITING PAYMENT) -->
            <div class="card shadow-sm border-0 p-4 p-md-5">
                <div class="mb-3">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-6">
                        <i class="bi bi-clock-history me-1"></i>STATUS: <?= htmlspecialchars($order['status'] ?? 'AWAITING PAYMENT') ?>
                    </span>
                </div>

                <h3 class="fw-bold text-dark mt-2">Pesanan Berhasil Dikonfirmasi!</h3>
                <p class="text-muted">Nomor Pesanan: <strong>#ORD-<?= htmlspecialchars($order['id_pesanan'] ?? '001') ?></strong></p>

                <div class="card bg-light border-0 my-4 text-start p-3">
                    <h6 class="fw-bold mb-2">Instruksi Pembayaran:</h6>
                    <p class="small text-muted mb-2">Silakan selesaikan pembayaran tagihan belanja Anda melalui transfer:</p>
                    <div class="d-flex justify-content-between align-items-center p-2 bg-white rounded border mb-2">
                        <span><strong>BCA Virtual Account</strong><br><code class="fs-6">88012398472910</code></span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="alert('Nomor VA disalin!')">Salin</button>
                    </div>
                    <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                        <span class="fw-semibold">Total yang Harus Dibayar:</span>
                        <span class="fw-bold text-primary fs-5">Rp <?= number_format($total, 0, ',', '.') ?></span>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-2">
                    <a href="<?= BASE_URL ?>/produk/getProduk" class="btn btn-primary px-4">
                        <i class="bi bi-bag-plus me-1"></i>Belanja Lagi
                    </a>
                    <a href="<?= BASE_URL ?>/account/getProfile" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-person me-1"></i>Lihat Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
