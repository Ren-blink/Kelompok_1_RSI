<div class="container">
    <!-- Sesuai Sequence Diagram 2 & 5: Boundary LamanKeranjang -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="bi bi-cart3 me-2 text-primary"></i>Laman Keranjang Belanja</h3>
        <?php if (!empty($items)): ?>
            <!-- Hapus Semua Produk (Sesuai SD 5 Frame 2) -->
            <a href="<?= BASE_URL ?>/keranjang/hapusProduk" class="btn btn-outline-danger btn-sm" onclick="return confirm('Kosongkan semua isi keranjang?')">
                <i class="bi bi-trash3 me-1"></i>Kosongkan Keranjang
            </a>
        <?php endif; ?>
    </div>

    <!-- Alert Notifikasi / Feedback Sukses -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-success py-2 small" role="alert">
            <i class="bi bi-check-circle me-1"></i><?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Error Jumlah Produk Tidak Valid (Sesuai SD 5 alt: returnErrorJumlahProduk) -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2 small" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i><?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

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
                                    <th scope="col" class="text-center" style="width: 170px;">Jumlah</th>
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
                                            <!-- Form Ubah Jumlah Produk (Sesuai SD 5 Frame 3: setJumlahProduk) -->
                                            <form action="<?= BASE_URL ?>/keranjang/setJumlahProduk" method="POST" class="d-flex justify-content-center align-items-center gap-1">
                                                <input type="hidden" name="id_keranjang" value="<?= $item['id_keranjang'] ?>">
                                                <input type="number" name="jumlah" value="<?= $item['jumlah'] ?>" min="1" max="<?= $item['stok'] ?>" class="form-control form-control-sm text-center" style="width: 65px;">
                                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Update Jumlah">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="fw-bold text-primary">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                        <td class="text-end pe-3">
                                            <!-- Hapus Produk Tertentu (Sesuai SD 5 Frame 2: hapusProduk) -->
                                            <a href="<?= BASE_URL ?>/keranjang/hapusProduk/<?= $item['id_keranjang'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus item ini?')">
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
                    <h5 class="fw-bold mb-3">Ringkasan Keranjang</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Barang</span>
                        <span class="fw-semibold"><?= count($items) ?> Produk</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Estimasi Ongkir</span>
                        <span class="text-success fw-semibold">Gratis (Promo)</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold">Total Harga</span>
                        <span class="fs-5 fw-bold text-primary">Rp <?= number_format($total, 0, ',', '.') ?></span>
                    </div>
                    <!-- Melakukan Checkout (Sesuai SD 2: doCheckout) -->
                    <a href="<?= BASE_URL ?>/checkout/doCheckout" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-credit-card me-2"></i>Lanjut ke Checkout
                    </a>
                    <a href="<?= BASE_URL ?>/produk/getProduk" class="btn btn-outline-secondary w-100 mt-2">
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Error Keranjang Kosong (Sesuai SD 5 alt: returnErrorKeranjangKosong) -->
        <div class="card shadow-sm border-0 p-5 text-center">
            <div class="py-4">
                <i class="bi bi-cart-x text-muted display-2"></i>
                <h4 class="fw-bold mt-3">Keranjang Belanja Masih Kosong</h4>
                <p class="text-muted"><?= htmlspecialchars($errorKeranjangKosong ?? 'Belum ada produk yang dimasukkan.') ?></p>
                <a href="<?= BASE_URL ?>/produk/getProduk" class="btn btn-primary px-4 mt-2">
                    <i class="bi bi-bag-plus me-2"></i>Mulai Cari Produk
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>
