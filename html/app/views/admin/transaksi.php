<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-cpu-fill me-2 text-primary"></i>Monitor Gateway & Simulasi Webhook (UC 22)</h3>
            <p class="text-muted mb-0">Pemantauan transaksi payment gateway dan tool simulator callback notifikasi transaksi.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Dashboard Admin
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

    <!-- Tool Simulator Webhook Callback -->
    <div class="card shadow-sm border-0 mb-4 bg-light border-start border-primary border-4">
        <div class="card-body p-4">
            <h5 class="fw-bold text-dark mb-2"><i class="bi bi-broadcast me-2 text-primary"></i>Simulasi Callback Payment Gateway Eksternal</h5>
            <p class="text-muted small mb-3">Gunakan form di bawah ini untuk menguji bagaimana sistem menerima sinyal HTTP Webhook notifikasi status dari server payment gateway (misal Midtrans/Xendit) ke endpoint sistem e-commerce.</p>

            <form action="<?= BASE_URL ?>/admin/simulasi_callback" method="POST" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold">ID Transaksi Gateway:</label>
                    <input type="text" name="id_transaksi_gateway" class="form-control" placeholder="Contoh: GW-20261108-001" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Status Callback:</label>
                    <select name="status_bayar" class="form-select">
                        <option value="berhasil">berhasil (Settlement / Success)</option>
                        <option value="gagal">gagal (Expired / Cancel / Deny)</option>
                        <option value="pending">pending (Waiting Payment)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="bi bi-send-check me-1"></i>Kirim Webhook
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Pembayaran -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-table me-2 text-primary"></i>Daftar Transaksi Pembayaran</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID Bayar</th>
                            <th>No. Pesanan</th>
                            <th>ID Gateway</th>
                            <th>Metode Pembayaran</th>
                            <th>Pembeli</th>
                            <th class="text-end">Total Nominal</th>
                            <th class="text-center">Status Bayar</th>
                            <th>Waktu Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['transaksi'])): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">Belum ada transaksi pembayaran.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['transaksi'] as $trx): ?>
                                <?php
                                    $st = $trx['status_bayar'];
                                    $badge = ($st === 'berhasil') ? 'bg-success' : (($st === 'gagal') ? 'bg-danger' : 'bg-warning text-dark');
                                ?>
                                <tr>
                                    <td class="fw-bold">#<?= htmlspecialchars($trx['id_pembayaran']) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/pesanan/lacak/<?= htmlspecialchars($trx['id_pesanan']) ?>" class="fw-bold text-decoration-none">
                                            #<?= htmlspecialchars($trx['id_pesanan']) ?>
                                        </a>
                                    </td>
                                    <td><code><?= htmlspecialchars($trx['id_transaksi_gateway'] ?? 'Manual Transfer') ?></code></td>
                                    <td><?= htmlspecialchars($trx['metode']) ?></td>
                                    <td><?= htmlspecialchars($trx['nama_pembeli'] ?? '-') ?></td>
                                    <td class="text-end fw-bold text-primary">Rp <?= number_format($trx['total_harga'] ?? 0, 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <span class="badge <?= $badge ?> text-uppercase"><?= htmlspecialchars($st) ?></span>
                                    </td>
                                    <td><small class="text-muted"><?= htmlspecialchars($trx['tgl_bayar']) ?></small></td>
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
