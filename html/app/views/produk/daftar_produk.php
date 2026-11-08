<div class="container">
    <!-- Sesuai Sequence Diagram 4: Boundary DaftarProduk -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <h3 class="fw-bold mb-0"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Daftar Produk</h3>
            <?php if (!empty($keyword)): ?>
                <small class="text-muted">Hasil penelusuran untuk: <strong>"<?= htmlspecialchars($keyword) ?>"</strong></small>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <!-- Form Input Keyword (Sesuai SD 4: inputKeyword) -->
            <form action="<?= BASE_URL ?>/produk/getProduk" method="GET" class="d-flex">
                <input type="text" name="keyword" class="form-control me-2" placeholder="Cari nama produk / kategori..." value="<?= htmlspecialchars($keyword ?? '') ?>">
                <button type="submit" class="btn btn-primary px-3">
                    <i class="bi bi-search"></i>
                </button>
                <?php if (!empty($keyword)): ?>
                    <a href="<?= BASE_URL ?>/produk/getProduk" class="btn btn-outline-secondary ms-2" title="Reset Pencarian">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Error Produk Tidak Ditemukan (Sesuai SD 4 alt: returnError("Produk Tidak Ditemukan")) -->
    <?php if (!isset($_SESSION['user'])): ?>
    <div class="alert alert-info d-flex align-items-center mb-4 shadow-sm" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div>
            <strong>Daftar atau masuk</strong> untuk melihat detail produk, menambahkan ke keranjang, dan melakukan pemesanan.
            <a href="<?= BASE_URL ?>/account/login" class="alert-link ms-1">Masuk sekarang</a> atau
            <a href="<?= BASE_URL ?>/account/register" class="alert-link">daftar gratis</a>.
        </div>
    </div>
    <?php endif; ?>
    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-warning py-4 text-center shadow-sm">
            <i class="bi bi-exclamation-triangle fs-2 d-block mb-2 text-warning"></i>
            <h5 class="fw-bold"><?= htmlspecialchars($errorMessage) ?></h5>
            <p class="text-muted mb-0">Maaf, kami tidak menemukan barang yang sesuai dengan kata kunci "<strong><?= htmlspecialchars($keyword) ?></strong>".</p>
            <a href="<?= BASE_URL ?>/produk/getProduk" class="btn btn-outline-primary btn-sm mt-3">Tampilkan Semua Produk</a>
        </div>
    <?php elseif (!empty($products)): ?>
        <div class="row g-4">
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
                            <!-- Sesuai SD 4: getDetailProduk() — hanya untuk user yang login -->
                            <?php if (isset($_SESSION['user'])): ?>
                            <a href="<?= BASE_URL ?>/produk/getDetailProduk/<?= $product['id_produk'] ?>" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-eye me-1"></i>Lihat Detail Produk
                            </a>
                            <?php else: ?>
                            <a href="<?= BASE_URL ?>/account/login" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="bi bi-lock me-1"></i>Masuk untuk Lihat
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center py-4">Belum ada produk yang tersedia saat ini.</div>
    <?php endif; ?>
</div>
