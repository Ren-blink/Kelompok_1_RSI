<div class="container">
    <!-- Sesuai Sequence Diagram 3 & 4: Boundary DetailProduk -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Homepage</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/produk/getProduk">Daftar Produk</a></li>
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
                        <div class="row align-items-center mb-3">
                            <div class="col-auto">
                                <span class="badge bg-success-subtle text-success p-2">
                                    <i class="bi bi-check-circle me-1"></i>Stok Tersedia: <?= $product['stok'] ?> Unit
                                </span>
                            </div>
                        </div>

                        <!-- Form Memasukkan Produk ke Keranjang (Sesuai SD 3: setKeranjang / setJumlahBarang) -->
                        <form action="<?= BASE_URL ?>/keranjang/setKeranjang" method="POST" class="row g-2 align-items-center mb-3">
                            <input type="hidden" name="id_produk" value="<?= $product['id_produk'] ?>">
                            <div class="col-auto">
                                <label for="jumlah" class="col-form-label fw-semibold">Jumlah:</label>
                            </div>
                            <div class="col-auto">
                                <input type="number" id="jumlah" name="jumlah" class="form-control text-center" value="1" min="1" max="<?= $product['stok'] ?>" style="width: 80px;">
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-cart-plus me-2"></i>Masukkan ke Keranjang
                                </button>
                            </div>
                        </form>

                        <div>
                            <a href="<?= BASE_URL ?>/pelanggaran/index/<?= $product['id_produk'] ?>" class="text-danger small text-decoration-none">
                                <i class="bi bi-flag me-1"></i>Laporkan indikasi pelanggaran produk ini
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Ulasan Produk (UC 10) -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-star-half text-warning me-2"></i>Ulasan & Penilaian Pembeli</h5>
        </div>
        <div class="card-body p-4">
            <?php if (empty($ulasan)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-chat-square-dots fs-3 d-block mb-2"></i>
                    Belum ada ulasan untuk produk ini. Jadilah pembeli pertama yang mengulas setelah pesanan selesai!
                </div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($ulasan as $u): ?>
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark"><?= htmlspecialchars($u['nama_user'] ?? 'Pembeli') ?></span>
                                <span class="text-warning small"><?= str_repeat('⭐', (int)$u['rating']) ?></span>
                            </div>
                            <p class="text-secondary small mb-1"><?= htmlspecialchars($u['komentar']) ?></p>
                            <small class="text-muted"><?= htmlspecialchars($u['tgl_ulasan']) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
