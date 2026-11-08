<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm">
                    <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Pelacakan Pembayaran #<?= htmlspecialchars($data['id_pesanan']) ?></h5>
                    <?php 
                        $statusBayar = $data['pembayaran']['status_bayar'] ?? 'pending';
                        $badgeClass = ($statusBayar === 'berhasil') ? 'bg-success' : (($statusBayar === 'gagal') ? 'bg-danger' : 'bg-warning text-dark');
                    ?>
                    <span class="badge <?= $badgeClass ?> fs-6 px-3 py-2 text-uppercase">
                        <?= htmlspecialchars($statusBayar) ?>
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="text-center py-3 mb-4">
                        <?php if ($statusBayar === 'berhasil'): ?>
                            <i class="bi bi-patch-check-fill text-success" style="font-size: 4rem;"></i>
                            <h4 class="fw-bold mt-2 text-success">Pembayaran Berhasil Diverifikasi</h4>
                            <p class="text-muted">Pesanan Anda saat ini sedang dipersiapkan oleh pihak toko.</p>
                        <?php else: ?>
                            <i class="bi bi-hourglass-split text-warning" style="font-size: 4rem;"></i>
                            <h4 class="fw-bold mt-2 text-warning">Menunggu Konfirmasi Pembayaran</h4>
                            <p class="text-muted">Sistem sedang menunggu sinyal dari Payment Gateway.</p>
                        <?php endif; ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-light w-40">Nomor Pesanan</th>
                                    <td>#<?= htmlspecialchars($data['id_pesanan']) ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">ID Transaksi Gateway</th>
                                    <td><code><?= htmlspecialchars($data['pembayaran']['id_transaksi_gateway'] ?? 'BELUM-TERBIT') ?></code></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Metode Pembayaran</th>
                                    <td><?= htmlspecialchars($data['pembayaran']['metode'] ?? 'Bank Transfer') ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Waktu Transaksi</th>
                                    <td><?= htmlspecialchars($data['pembayaran']['tgl_bayar'] ?? date('Y-m-d H:i:s')) ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Total Pembayaran</th>
                                    <td class="fw-bold text-primary">Rp <?= number_format($data['pesanan']['total_harga'] ?? 0, 0, ',', '.') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex gap-2 justify-content-between mt-4">
                        <a href="<?= BASE_URL ?>/pesanan/lacak/<?= htmlspecialchars($data['id_pesanan']) ?>" class="btn btn-primary">
                            <i class="bi bi-truck me-1"></i>Lacak Pengiriman Pesanan
                        </a>
                        <a href="<?= BASE_URL ?>/pesanan" class="btn btn-outline-secondary">
                            <i class="bi bi-card-checklist me-1"></i>Daftar Pesanan Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
