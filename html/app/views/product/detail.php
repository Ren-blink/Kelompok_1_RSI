<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Beranda</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/product">Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['nama_produk']) ?></li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="row g-0">
            <div class="col-md-5">
                <img src="<?= htmlspecialchars($product['gambar']) ?>" class="img-fluid h-100 w-100 object-fit-cover" alt="<?= htmlspecialchars($product['nama_produk']) ?>" style="min-height: 350px;">
            </div>
            <div class="col-md-7">
                <div class="card-body p-4 d-flex flex-column h-100">
                    <div class="mb-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill">
                            <?= htmlspecialchars($product['kategori']) ?>
                        </span>
                    </div>

                    <h3 class="fw-bold text-dark mb-2"><?= htmlspecialchars($product['nama_produk']) ?></h3>

                    <p class="text-muted small mb-3">
                        <i class="bi bi-shop me-1 text-primary"></i>Dijual oleh: <strong><?= htmlspecialchars($product['nama_toko'] ?? 'Official Store') ?></strong>
                    </p>

                    <h2 class="text-primary fw-bold mb-4">
                        Rp <?= number_format($product['harga'], 0, ',', '.') ?>
                    </h2>

                    <div class="mb-4">
                        <h6 class="fw-bold text-secondary">Deskripsi Produk:</h6>
                        <p class="text-muted leading-relaxed">
                            <?= nl2br(htmlspecialchars($product['deskripsi'])) ?>
                        </p>
                    </div>

                    <div class="mt-auto pt-3 border-top">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="badge bg-success-subtle text-success p-2">
                                    <i class="bi bi-check-circle me-1"></i>Stok Tersedia: <?= $product['stok'] ?>
                                </span>
                            </div>
                        </div>

                        <form action="<?= BASE_URL ?>/cart/add/<?= $product['id_produk'] ?>" method="POST" class="row g-2 mt-2 align-items-center">
                            <div class="col-auto">
                                <label for="jumlah" class="col-form-label fw-semibold">Jumlah:</label>
                            </div>
                            <div class="col-auto">
                                <input type="number" id="jumlah" name="jumlah" class="form-control text-center" value="1" min="1" max="<?= $product['stok'] ?>" style="width: 80px;">
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
