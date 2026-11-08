<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="<?= BASE_URL ?>/toko/produk" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Produk
                </a>
            </div>

            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?php $isEdit = !empty($data['produk']); ?>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi <?= $isEdit ? 'bi-pencil-square' : 'bi-plus-circle' ?> me-2"></i>
                        <?= $isEdit ? 'Edit Data Produk #' . htmlspecialchars($data['produk']['id_produk']) : 'Tambah Produk Baru ke Toko' ?>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= $isEdit ? BASE_URL . '/toko/edit_produk/' . $data['produk']['id_produk'] : BASE_URL . '/toko/tambah_produk' ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Produk:</label>
                            <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($data['produk']['nama_produk'] ?? '') ?>" placeholder="Misal: Keyboard Mekanikal Wireless" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kategori:</label>
                                <select name="kategori" class="form-select">
                                    <?php 
                                        $katCurrent = $data['produk']['kategori'] ?? 'Aksesoris';
                                        $kategoriList = ['Laptop', 'Aksesoris', 'Monitor', 'Audio', 'Komponen PC', 'Handphone', 'Lainnya'];
                                    ?>
                                    <?php foreach ($kategoriList as $k): ?>
                                        <option value="<?= $k ?>" <?= ($katCurrent === $k) ? 'selected' : '' ?>><?= $k ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Stok Barang:</label>
                                <input type="number" name="stok" class="form-control" min="0" value="<?= htmlspecialchars($data['produk']['stok'] ?? 10) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Harga Produk (Rp):</label>
                            <input type="number" name="harga" class="form-control" min="1000" step="1000" value="<?= htmlspecialchars($data['produk']['harga'] ?? '') ?>" placeholder="Misal: 1500000" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">URL Gambar Produk:</label>
                            <input type="url" name="gambar" class="form-control" value="<?= htmlspecialchars($data['produk']['gambar'] ?? 'https://images.unsplash.com/photo-1526738549149-8e07eca6c147?w=600&auto=format&fit=crop&q=80') ?>" placeholder="https://...">
                            <div class="form-text">Masukkan link gambar produk (bisa gunakan link Unsplash atau image hosting).</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Deskripsi Produk:</label>
                            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Tuliskan spesifikasi, garansi, kelengkapan isi box, dll..."><?= htmlspecialchars($data['produk']['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>/toko/produk" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="bi bi-save me-1"></i><?= $isEdit ? 'Simpan Perubahan' : 'Tambahkan Produk' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
