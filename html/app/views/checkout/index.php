<div class="container py-3">
    <!-- Sesuai Sequence Diagram 2: Boundary LamanCheckout -->
    <h3 class="fw-bold mb-4"><i class="bi bi-bag-check text-primary me-2"></i>Laman Checkout</h3>

    <!-- Error Validasi Alamat (Sesuai SD 2 alt: returnErrorAlamat) -->
    <?php if (!empty($errorAlamat)): ?>
        <div class="alert alert-danger py-2 small" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($errorAlamat) ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Kolom Form Alamat Pengiriman (Sesuai SD 2 Frame 2: inputAlamat) -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-geo-alt text-danger me-2"></i>1. Alamat Pengiriman</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>/checkout/inputAlamat" method="POST">
                        <div class="mb-3">
                            <label for="alamat_pengiriman" class="form-label fw-semibold small">Alamat Lengkap Penerima</label>
                            <textarea name="alamat_pengiriman" id="alamat_pengiriman" rows="3" class="form-control" placeholder="Tuliskan nama jalan, no rumah, RT/RW, kelurahan, kecamatan, kota, dan kode pos..." required><?= htmlspecialchars($alamat ?? '') ?></textarea>
                            <div class="form-text small">Pastikan alamat terisi lengkap untuk validasi kurir.</div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                            <i class="bi bi-check2-circle me-1"></i>Simpan Alamat
                        </button>
                    </form>
                </div>
            </div>

            <!-- Rincian Produk Pesanan (returnDetailPesanan) -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-box-seam text-primary me-2"></i>2. Item Pesanan</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($items as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars($item['gambar']) ?>" class="rounded me-3 object-fit-cover" width="45" height="45">
                                    <div>
                                        <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($item['nama_produk']) ?></h6>
                                        <small class="text-muted"><?= $item['jumlah'] ?> pcs &times; Rp <?= number_format($item['harga'], 0, ',', '.') ?></small>
                                    </div>
                                </div>
                                <span class="fw-bold text-dark">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Kolom Ringkasan & Konfirmasi Pesanan (Sesuai SD 2 Frame 3: confirmPesanan) -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 p-4 sticky-top" style="top: 80px;">
                <h5 class="fw-bold mb-3">Ringkasan Pembayaran</h5>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Belanja</span>
                    <span class="fw-semibold">Rp <?= number_format($total, 0, ',', '.') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Ongkos Kirim</span>
                    <span class="text-success fw-semibold">Rp 0 (Gratis)</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Biaya Layanan</span>
                    <span class="text-muted">Rp 0</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="fs-5 fw-bold">Total Tagihan</span>
                    <span class="fs-5 fw-bold text-primary">Rp <?= number_format($total, 0, ',', '.') ?></span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Alamat Tujuan:</small>
                    <?php if (!empty($alamat)): ?>
                        <div class="p-2 bg-light rounded small border">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($alamat) ?>
                        </div>
                    <?php else: ?>
                        <div class="p-2 bg-warning-subtle text-warning-emphasis rounded small border border-warning-subtle">
                            <i class="bi bi-exclamation-triangle me-1"></i>Alamat belum disimpan.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Form Konfirmasi Pesanan (confirmPesanan) -->
                <form action="<?= BASE_URL ?>/checkout/confirmPesanan" method="POST">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" <?= empty($alamat) ? 'disabled' : '' ?>>
                        <i class="bi bi-shield-check me-2"></i>Konfirmasi Pesanan
                    </button>
                </form>

                <a href="<?= BASE_URL ?>/keranjang/getIsiKeranjang" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Keranjang
                </a>
            </div>
        </div>
    </div>
</div>
