<div class="container">
    <h3 class="fw-bold mb-4"><i class="bi bi-cart3 me-2 text-primary"></i>Keranjang Belanja</h3>

    <?php if (!empty($items)): ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-3">Produk</th>
                                    <th scope="col">Harga Satuan</th>
                                    <th scope="col" class="text-center">Jumlah</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col" class="text-end pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= htmlspecialchars($item['gambar']) ?>" alt="" class="rounded me-3 object-fit-cover" width="50" height="50">
                                                <div>
                                                    <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($item['nama_produk']) ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border px-3 py-2"><?= $item['jumlah'] ?></span>
                                        </td>
                                        <td class="fw-bold text-primary">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                        <td class="text-end pe-3">
                                            <a href="<?= BASE_URL ?>/cart/remove/<?= $item['id_keranjang'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus produk dari keranjang?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 p-4">
                    <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Barang</span>
                        <span class="fw-semibold"><?= count($items) ?> Item</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Biaya Pengiriman</span>
                        <span class="text-success fw-semibold">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold">Total Tagihan</span>
                        <span class="fs-5 fw-bold text-primary">Rp <?= number_format($total, 0, ',', '.') ?></span>
                    </div>
                    <button class="btn btn-primary w-100 py-2 fw-semibold" onclick="alert('Fitur proses Checkout Pesanan & Pembayaran terintegrasi ke database!')">
                        <i class="bi bi-credit-card me-2"></i>Lanjut ke Checkout
                    </button>
                    <a href="<?= BASE_URL ?>/product" class="btn btn-outline-secondary w-100 mt-2">
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0 p-5 text-center">
            <div class="py-4">
                <i class="bi bi-cart-x text-muted display-2"></i>
                <h4 class="fw-bold mt-3">Keranjang Anda Masih Kosong</h4>
                <p class="text-muted">Jelajahi produk berkualitas kami dan temukan barang impian Anda.</p>
                <a href="<?= BASE_URL ?>/product" class="btn btn-primary px-4 mt-2">
                    <i class="bi bi-bag-plus me-2"></i>Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>
