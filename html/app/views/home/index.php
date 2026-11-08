<div class="container">
    <!-- Hero Banner (Homepage) -->
    <div class="p-5 mb-4 bg-white rounded-3 shadow-sm border">
        <div class="container-fluid py-2">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill mb-3">
                <i class="bi bi-stars me-1"></i>Sistem E-Commerce Kelompok 1 RSI
            </span>
            <h1 class="display-6 fw-bold text-dark">Platform Belanja Online Modern & Cepat</h1>
            <p class="col-md-9 fs-6 text-muted">
                Didesain menggunakan arsitektur MVC sesuai pemodelan Sequence Diagram: <strong>AccountController</strong>, <strong>ProdukController</strong>, <strong>KeranjangController</strong>, dan <strong>CheckoutController</strong>.
            </p>

            <!-- Sesuai SD 4: Form inputKeyword() di Homepage -->
            <form action="<?= BASE_URL ?>/produk/getProduk" method="GET" class="row g-2 col-md-8 mt-2">
                <div class="col-8 col-sm-9">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari laptop, keyboard, monitor, dll...">
                </div>
                <div class="col-4 col-sm-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><i class="bi bi-fire text-danger me-2"></i>Produk Pilihan</h4>
        <a href="<?= BASE_URL ?>/produk/getProduk" class="text-decoration-none fw-semibold">Lihat Semua <i class="bi bi-arrow-right"></i></a>
    </div>

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

    <div class="row g-4">
        <?php if (!empty($featuredProducts)): ?>
            <?php foreach ($featuredProducts as $product): ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 product-card">
                        <img src="<?= htmlspecialchars($product['gambar']) ?>" class="card-img-top object-fit-cover" alt="<?= htmlspecialchars($product['nama_produk']) ?>" style="height: 180px;">
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-secondary-subtle text-secondary w-auto mb-2 align-self-start">
                                <?= htmlspecialchars($product['kategori']) ?>
                            </span>
                            <h6 class="card-title fw-bold text-truncate mb-1" title="<?= htmlspecialchars($product['nama_produk']) ?>">
                                <?= htmlspecialchars($product['nama_produk']) ?>
                            </h6>
                            <small class="text-muted mb-2"><i class="bi bi-shop me-1"></i><?= htmlspecialchars($product['nama_toko'] ?? 'Official Store') ?></small>
                            <h6 class="text-primary fw-bold mb-3 mt-auto">
                                Rp <?= number_format($product['harga'], 0, ',', '.') ?>
                            </h6>
                            <!-- Sesuai SD 4: getDetailProduk() — hanya untuk user yang login -->
                            <?php if (isset($_SESSION['user'])): ?>
                            <a href="<?= BASE_URL ?>/produk/getDetailProduk/<?= $product['id_produk'] ?>" class="btn btn-outline-primary btn-sm w-100">
                                Detail Produk
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
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">Belum ada produk yang tersedia.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
