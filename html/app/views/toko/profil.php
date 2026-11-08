<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="<?= BASE_URL ?>/toko" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard Toko
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

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-shop-window me-2"></i>Pengaturan Profil Toko</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>/toko/profil" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Toko:</label>
                            <input type="text" name="nama_toko" class="form-control" value="<?= htmlspecialchars($data['toko']['nama_toko'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi Toko:</label>
                            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan mengenai toko Anda, produk unggulan, dan kebijakan operasional..."><?= htmlspecialchars($data['toko']['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Saldo Toko Saat Ini:</label>
                            <div class="form-control bg-light fw-bold text-success">
                                Rp <?= number_format($data['toko']['saldo'] ?? 0, 0, ',', '.') ?>
                            </div>
                            <div class="form-text">Untuk melakukan pencairan dana ke rekening bank, buka menu <a href="<?= BASE_URL ?>/toko/saldo">Saldo Toko</a>.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>/toko" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
