<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-box-seam me-2 text-primary"></i>Kelola Produk Toko</h3>
            <p class="text-muted mb-0">Daftar produk yang dijual di <?= htmlspecialchars($data['toko']['nama_toko'] ?? 'Toko Anda') ?></p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/toko" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i>Dashboard
            </a>
            <a href="<?= BASE_URL ?>/toko/tambah_produk" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Tambah Produk Baru
            </a>
        </div>
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
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['produk'])): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Belum ada produk di toko ini. Klik "Tambah Produk Baru" untuk mulai berjualan.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['produk'] as $p): ?>
                                <tr>
                                    <td>
                                        <img src="<?= htmlspecialchars($p['gambar'] ?? 'https://images.unsplash.com/photo-1526738549149-8e07eca6c147?w=100&auto=format&fit=crop&q=80') ?>" class="rounded" style="width: 60px; height: 60px; object-fit: cover;" alt="">
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($p['nama_produk']) ?></div>
                                        <small class="text-muted text-truncate d-inline-block" style="max-width: 300px;">
                                            <?= htmlspecialchars($p['deskripsi'] ?? '-') ?>
                                        </small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['kategori']) ?></span></td>
                                    <td class="text-end fw-bold text-primary">Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <span class="badge <?= ($p['stok'] > 5) ? 'bg-success' : (($p['stok'] > 0) ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                            <?= htmlspecialchars($p['stok']) ?> Unit
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= BASE_URL ?>/toko/edit_produk/<?= $p['id_produk'] ?>" class="btn btn-outline-primary" title="Edit Produk">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>/toko/hapus_produk/<?= $p['id_produk'] ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')" title="Hapus Produk">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
