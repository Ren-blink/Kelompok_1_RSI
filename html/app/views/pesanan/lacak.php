<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="<?= BASE_URL ?>/pesanan" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Riwayat Pesanan
                </a>
                <span class="text-muted small">Status Tracking Real-time</span>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-geo-alt-fill me-2"></i>Lacak Pesanan #<?= htmlspecialchars($data['pesanan']['id_pesanan']) ?></h5>
                    <span class="badge bg-light text-primary text-uppercase px-3 py-2">
                        <?= str_replace('_', ' ', htmlspecialchars($data['pesanan']['status_pesanan'] ?? 'menunggu_pembayaran')) ?>
                    </span>
                </div>
                <div class="card-body p-4">
                    <!-- Progress Timeline Visual -->
                    <?php
                        $status = $data['pesanan']['status_pesanan'] ?? 'menunggu_pembayaran';
                        $step = 1;
                        if ($status === 'diproses') $step = 2;
                        elseif ($status === 'dikirim') $step = 3;
                        elseif ($status === 'selesai') $step = 4;
                    ?>

                    <div class="row text-center mb-4 g-2">
                        <div class="col-3">
                            <div class="p-2 rounded <?= ($step >= 1) ? 'bg-primary text-white' : 'bg-light text-muted' ?>">
                                <i class="bi bi-receipt fs-4 d-block"></i>
                                <small class="fw-bold d-block mt-1">Dipesan</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 rounded <?= ($step >= 2) ? 'bg-primary text-white' : 'bg-light text-muted' ?>">
                                <i class="bi bi-box-seam fs-4 d-block"></i>
                                <small class="fw-bold d-block mt-1">Diproses</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 rounded <?= ($step >= 3) ? 'bg-primary text-white' : 'bg-light text-muted' ?>">
                                <i class="bi bi-truck fs-4 d-block"></i>
                                <small class="fw-bold d-block mt-1">Dikirim</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 rounded <?= ($step >= 4) ? 'bg-success text-white' : 'bg-light text-muted' ?>">
                                <i class="bi bi-check-circle fs-4 d-block"></i>
                                <small class="fw-bold d-block mt-1">Selesai</small>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pengiriman & Resi -->
                    <div class="border rounded p-3 mb-4 bg-light">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Rincian Pengiriman & Kurir</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <small class="text-muted d-block">Nomor Resi</small>
                                <span class="fw-bold fs-6 text-primary">
                                    <?= htmlspecialchars($data['pengiriman']['no_resi'] ?? 'Belum diterbitkan oleh toko') ?>
                                </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <small class="text-muted d-block">Status Kurir</small>
                                <span class="badge bg-secondary"><?= htmlspecialchars($data['pengiriman']['status_kirim'] ?? 'menunggu_pickup') ?></span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <small class="text-muted d-block">Toko Pengirim</small>
                                <span><?= htmlspecialchars($data['pengiriman']['nama_toko'] ?? 'Budi Tech Store') ?></span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <small class="text-muted d-block">Waktu Kirim</small>
                                <span><?= htmlspecialchars($data['pengiriman']['tgl_kirim'] ?? '-') ?></span>
                            </div>
                            <div class="col-12 mt-2">
                                <small class="text-muted d-block">Alamat Tujuan Pengiriman</small>
                                <span><?= htmlspecialchars($data['pesanan']['alamat_pengiriman'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Rincian Produk Pesanan -->
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-cart-check me-2 text-primary"></i>Daftar Barang Pesanan</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Toko</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['pesanan']['items'])): ?>
                                    <?php foreach ($data['pesanan']['items'] as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold"><?= htmlspecialchars($item['nama_produk']) ?></div>
                                                <a href="<?= BASE_URL ?>/pelanggaran/index/<?= $item['id_produk'] ?>" class="text-danger small text-decoration-none">
                                                    <i class="bi bi-flag me-1"></i>Laporkan Produk
                                                </a>
                                            </td>
                                            <td><small class="badge bg-light text-dark border"><?= htmlspecialchars($item['nama_toko'] ?? '-') ?></small></td>
                                            <td class="text-center"><?= htmlspecialchars($item['jumlah']) ?></td>
                                            <td class="text-end">Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                                            <td class="text-end fw-bold">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total Pembayaran:</th>
                                    <th class="text-end text-primary fs-5">Rp <?= number_format($data['pesanan']['total_harga'] ?? 0, 0, ',', '.') ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Aksi Berdasarkan Status -->
                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                        <?php if ($status === 'menunggu_pembayaran'): ?>
                            <a href="<?= BASE_URL ?>/pembayaran/index/<?= htmlspecialchars($data['pesanan']['id_pesanan']) ?>" class="btn btn-warning fw-bold">
                                <i class="bi bi-credit-card me-1"></i>Bayar Tagihan Sekarang
                            </a>
                        <?php elseif ($status === 'dikirim'): ?>
                            <a href="<?= BASE_URL ?>/pesanan/konfirmasi/<?= htmlspecialchars($data['pesanan']['id_pesanan']) ?>" class="btn btn-success fw-bold" onclick="return confirm('Apakah paket pesanan telah sampai dan Anda terima dengan baik?')">
                                <i class="bi bi-check2-circle me-1"></i>Konfirmasi Paket Diterima
                            </a>
                        <?php elseif ($status === 'selesai'): ?>
                            <a href="<?= BASE_URL ?>/ulasan/index/<?= htmlspecialchars($data['pesanan']['id_pesanan']) ?>" class="btn btn-outline-success">
                                <i class="bi bi-star me-1"></i>Beri Penilaian & Ulasan
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
