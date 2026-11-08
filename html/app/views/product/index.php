<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold mb-3"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Katalog Produk</h3>
            <!-- Category Pills Filter -->
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= BASE_URL ?>/product" class="btn btn-sm <?= empty($currentCategory) ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    Semua
                </a>
                <a href="<?= BASE_URL ?>/product/index/Laptop" class="btn btn-sm <?= ($currentCategory === 'Laptop') ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    Laptop
                </a>
                <a href="<?= BASE_URL ?>/product/index/Aksesoris" class="btn btn-sm <?= ($currentCategory === 'Aksesoris') ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    Aksesoris
                </a>
                <a href="<?= BASE_URL ?>/product/index/Monitor" class="btn btn-sm <?= ($currentCategory === 'Monitor') ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    Monitor
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 product-card">
                        <img src="<?= htmlspecialchars($product['gambar']) ?>" class="card-img-top object-fit-cover" alt="<?= htmlspecialchars($product['nama_produk']) ?>" style="height: 190px;">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-light text-dark border"><?= htmlspecialchars($product['kategori']) ?></span>
                                <small class="text-muted">Stok: <?= $product['stok'] ?></small>
                            </div>
                            <h6 class="card-title fw-bold text-truncate mt-1 mb-1" title="<?= htmlspecialchars($product['nama_produk']) ?>">
                                <?= htmlspecialchars($product['nama_produk']) ?>
                            </h6>
                            <small class="text-muted mb-2"><i class="bi bi-shop me-1"></i><?= htmlspecialchars($product['nama_toko'] ?? 'Toko') ?></small>
                            <h6 class="text-primary fw-bold mb-3 mt-auto">
                                Rp <?= number_format($product['harga'], 0, ',', '.') ?>
                            </h6>
                            <a href="<?= BASE_URL ?>/product/detail/<?= $product['id_produk'] ?>" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center py-4">
                    <i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i>
                    Tidak ada produk dalam kategori ini.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
