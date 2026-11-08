<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-inbox-fill me-2 text-primary"></i>Pesanan Masuk Toko</h3>
            <p class="text-muted mb-0">Kelola pesanan dari pembeli, proses transaksi, dan input resi logistik.</p>
        </div>
        <a href="<?= BASE_URL ?>/toko" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Dashboard Toko
        </a>
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
                            <th>No. Order</th>
                            <th>Tanggal</th>
                            <th>Pembeli & Alamat</th>
                            <th>Barang Dipesan</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 230px;">Aksi / Pengiriman</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['pesanan'])): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Belum ada pesanan masuk saat ini.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['pesanan'] as $pesanan): ?>
                                <?php
                                    $status = $pesanan['status_pesanan'];
                                    $badgeClass = 'bg-secondary';
                                    if ($status === 'menunggu_pembayaran') $badgeClass = 'bg-warning text-dark';
                                    elseif ($status === 'diproses') $badgeClass = 'bg-info text-dark';
                                    elseif ($status === 'dikirim') $badgeClass = 'bg-primary';
                                    elseif ($status === 'selesai') $badgeClass = 'bg-success';
                                ?>
                                <tr>
                                    <td class="fw-bold">#<?= htmlspecialchars($pesanan['id_pesanan']) ?></td>
                                    <td><small class="text-muted"><?= htmlspecialchars($pesanan['tgl_pesanan']) ?></small></td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($pesanan['nama_pembeli'] ?? 'Pembeli') ?></div>
                                        <small class="text-muted d-inline-block text-truncate" style="max-width: 200px;">
                                            <?= htmlspecialchars($pesanan['alamat_pengiriman']) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div><?= htmlspecialchars($pesanan['nama_produk']) ?></div>
                                        <small class="badge bg-light text-dark border"><?= htmlspecialchars($pesanan['jumlah']) ?> pcs @ Rp <?= number_format($pesanan['harga_satuan'], 0, ',', '.') ?></small>
                                    </td>
                                    <td class="text-end fw-bold text-primary">
                                        Rp <?= number_format($pesanan['jumlah'] * $pesanan['harga_satuan'], 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?= $badgeClass ?> text-uppercase"><?= str_replace('_', ' ', htmlspecialchars($status)) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($status === 'menunggu_pembayaran'): ?>
                                            <span class="badge bg-light text-muted border">Menunggu Bayar</span>
                                        <?php elseif ($status === 'diproses'): ?>
                                            <!-- Form Langsung Input Resi Pengiriman (UC 16) -->
                                            <form action="<?= BASE_URL ?>/toko/kirim_pesanan" method="POST" class="d-flex gap-1">
                                                <input type="hidden" name="id_pesanan" value="<?= htmlspecialchars($pesanan['id_pesanan']) ?>">
                                                <input type="text" name="no_resi" class="form-control form-control-sm" placeholder="No Resi Kurir..." required>
                                                <button type="submit" class="btn btn-sm btn-primary text-nowrap fw-bold" title="Input Resi & Kirim">
                                                    <i class="bi bi-send-fill me-1"></i>Kirim
                                                </button>
                                            </form>
                                        <?php elseif ($status === 'dikirim'): ?>
                                            <div class="small">
                                                <i class="bi bi-truck text-primary me-1"></i>Resi: <strong><?= htmlspecialchars($pesanan['no_resi'] ?? '-') ?></strong>
                                            </div>
                                            <span class="badge bg-light text-success border">Sedang Dikirim</span>
                                        <?php elseif ($status === 'selesai'): ?>
                                            <span class="badge bg-success"><i class="bi bi-check-all me-1"></i>Selesai & Dana Cair</span>
                                        <?php endif; ?>
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
