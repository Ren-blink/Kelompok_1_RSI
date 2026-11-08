<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="<?= BASE_URL ?>/pesanan" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Riwayat Pesanan
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

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-star-fill me-2"></i>Beri Ulasan Produk (Pesanan #<?= htmlspecialchars($data['id_pesanan']) ?>)</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Bantu pembeli lain dengan membagikan pengalaman Anda terkait kualitas barang dan pelayanan penjual.</p>

                    <?php if (!empty($data['pesanan']['items'])): ?>
                        <?php foreach ($data['pesanan']['items'] as $item): ?>
                            <div class="card mb-4 border bg-light">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($item['nama_produk']) ?></h6>
                                            <small class="text-muted">Toko: <?= htmlspecialchars($item['nama_toko'] ?? 'Toko Mitra') ?></small>
                                        </div>
                                    </div>

                                    <form action="<?= BASE_URL ?>/ulasan/simpan" method="POST">
                                        <input type="hidden" name="id_pesanan" value="<?= htmlspecialchars($data['id_pesanan']) ?>">
                                        <input type="hidden" name="id_produk" value="<?= htmlspecialchars($item['id_produk']) ?>">

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Rating Kepuasan (1 - 5 Bintang):</label>
                                            <select name="rating" class="form-select w-auto">
                                                <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Puas)</option>
                                                <option value="4">⭐⭐⭐⭐ (4 - Puas)</option>
                                                <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                                <option value="2">⭐⭐ (2 - Kurang Puas)</option>
                                                <option value="1">⭐ (1 - Kecewa)</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Ulasan & Catatan Pengalaman:</label>
                                            <textarea name="komentar" class="form-control" rows="3" placeholder="Ceritakan bagaimana kondisi produk, kecepatan pengiriman, dan respon penjual..." required></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-success btn-sm px-4 fw-bold">
                                            <i class="bi bi-send-fill me-1"></i>Kirim Ulasan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($data['ulasanTerdahulu'])): ?>
                        <div class="mt-4 border-top pt-3">
                            <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-1"></i>Ulasan yang Sudah Anda Berikan:</h6>
                            <div class="list-group">
                                <?php foreach ($data['ulasanTerdahulu'] as $ul): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <strong class="text-dark"><?= htmlspecialchars($ul['nama_produk'] ?? 'Produk') ?></strong>
                                            <span class="text-warning">
                                                <?= str_repeat('⭐', (int)$ul['rating']) ?>
                                            </span>
                                        </div>
                                        <p class="mb-1 text-secondary small"><?= htmlspecialchars($ul['komentar']) ?></p>
                                        <small class="text-muted"><?= htmlspecialchars($ul['tgl_ulasan']) ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
