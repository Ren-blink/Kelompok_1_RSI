<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Laporan Penjualan Toko</h3>
            <p class="text-muted mb-0">Statistik performa penjualan, rincian omset, dan volume barang terjual.</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/toko" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Dashboard Toko
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="<?= BASE_URL ?>/toko/laporan" method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted mb-1">Mulai Tanggal:</label>
                    <input type="date" name="start" class="form-control form-control-sm" value="<?= htmlspecialchars($data['startDate'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted mb-1">Sampai Tanggal:</label>
                    <input type="date" name="end" class="form-control form-control-sm" value="<?= htmlspecialchars($data['endDate'] ?? '') ?>">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1 fw-bold">
                        <i class="bi bi-funnel me-1"></i>Filter Laporan
                    </button>
                    <a href="<?= BASE_URL ?>/toko/laporan" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Kartu Ringkasan Omset -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body p-3">
                    <div class="small text-uppercase opacity-75 fw-bold">Total Omset Pendapatan</div>
                    <h3 class="fw-bold mb-0 mt-1">Rp <?= number_format($data['laporan']['total_omset'] ?? 0, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body p-3">
                    <div class="small text-uppercase opacity-75 fw-bold">Transaksi Sukses</div>
                    <h3 class="fw-bold mb-0 mt-1"><?= htmlspecialchars($data['laporan']['total_transaksi'] ?? 0) ?> Pesanan</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-info text-white">
                <div class="card-body p-3">
                    <div class="small text-uppercase opacity-75 fw-bold">Volume Barang Terjual</div>
                    <h3 class="fw-bold mb-0 mt-1"><?= htmlspecialchars($data['laporan']['total_barang_terjual'] ?? 0) ?> Unit</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Rincian Transaksi Penjualan</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Order</th>
                            <th>Tanggal</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal Omset</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['laporan']['rincian'])): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Tidak ada data transaksi pada rentang periode ini.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['laporan']['rincian'] as $row): ?>
                                <tr>
                                    <td class="fw-bold">#<?= htmlspecialchars($row['id_pesanan']) ?></td>
                                    <td><small class="text-muted"><?= htmlspecialchars($row['tgl_pesanan']) ?></small></td>
                                    <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['jumlah']) ?></td>
                                    <td class="text-end">Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
                                    <td class="text-end fw-bold text-success">Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border"><?= htmlspecialchars($row['status_pesanan']) ?></span>
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
