<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
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
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-shield-fill-exclamation me-2"></i>Form Pelaporan Pelanggaran</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Laporkan produk, penjual, atau konten yang terindikasi penipuan, barang palsu, atau melanggar syarat ketentuan platform kami. Laporan Anda akan ditindaklanjuti oleh Administrator.</p>

                    <form action="<?= BASE_URL ?>/pelanggaran/simpan" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Produk yang Dilaporkan (Opsional):</label>
                            <select name="id_produk" class="form-select">
                                <option value="">-- Pilih Produk Terkait (Atau kosongkan jika melaporkan umum/toko) --</option>
                                <?php if (!empty($data['semuaProduk'])): ?>
                                    <?php foreach ($data['semuaProduk'] as $prod): ?>
                                        <option value="<?= $prod['id_produk'] ?>" <?= (!empty($data['produkTerpilih']) && $data['produkTerpilih']['id_produk'] == $prod['id_produk']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($prod['nama_produk']) ?> (Toko: <?= htmlspecialchars($prod['nama_toko'] ?? 'Mitra') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Deskripsi / Bukti Pelanggaran:</label>
                            <textarea name="deskripsi" class="form-control" rows="5" placeholder="Jelaskan secara detail indikasi pelanggaran, deskripsi palsu, ketidaksesuaian barang, atau perilaku penjual yang mencurigakan..." required></textarea>
                            <div class="form-text">Harap berikan informasi yang jelas dan akurat agar mempermudah tim kami dalam investigasi.</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-danger px-4 fw-bold">
                                <i class="bi bi-flag-fill me-1"></i>Kirim Laporan Pelanggaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
