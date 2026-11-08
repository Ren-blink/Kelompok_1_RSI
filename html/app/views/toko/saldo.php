<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-wallet2 me-2 text-primary"></i>Saldo & Keuangan Toko</h3>
            <p class="text-muted mb-0">Kelola pendapatan toko, riwayat mutasi kredit/debit, dan penarikan saldo.</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/toko" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Dashboard Toko
            </a>
        </div>
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

    <div class="row g-4 mb-4">
        <!-- Kartu Saldo Aktif -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0 bg-primary text-white h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <span class="badge bg-light text-primary mb-2">Dompet Toko</span>
                        <h6 class="opacity-75 text-uppercase fw-bold mb-1">Saldo Tersedia untuk Ditarik</h6>
                        <h2 class="fw-bold mb-0">Rp <?= number_format($data['toko']['saldo'] ?? 0, 0, ',', '.') ?></h2>
                    </div>

                    <div class="mt-4 pt-3 border-top border-white-50">
                        <small class="opacity-75 d-block mb-3">Penarikan saldo otomatis ditransfer ke rekening bank terdaftar dalam 1x24 jam kerja.</small>
                        <button type="button" class="btn btn-warning text-dark fw-bold w-100 py-2" data-bs-toggle="modal" data-bs-target="#modalTarikSaldo">
                            <i class="bi bi-cash-coin me-2"></i>Ajukan Tarik Saldo
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rangkuman Kebijakan Penarikan -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-shield-check text-primary me-2"></i>Ketentuan Penarikan Saldo (UC 19)</h5>
                    <ul class="text-secondary small mb-3">
                        <li class="mb-2">Saldo diperoleh dari pesanan pembeli yang telah terkonfirmasi <strong>Selesai</strong>.</li>
                        <li class="mb-2">Minimal penarikan saldo adalah <strong>Rp 10.000</strong> per transaksi.</li>
                        <li class="mb-2">Mendukung transfer ke Bank BCA, Bank Mandiri, BNI, BRI, dan Bank Jago tanpa biaya admin.</li>
                        <li class="mb-2">Semua mutasi tercatat secara transparan dan terenkripsi dalam sistem buku besar toko.</li>
                    </ul>

                    <div class="alert alert-info border-0 mb-0 small">
                        <i class="bi bi-info-circle me-1"></i>Nama rekening bank tujuan harus sesuai dengan identitas pemilik toko yang terverifikasi.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Mutasi Saldo -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Mutasi Saldo Toko</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu Mutasi</th>
                            <th>Keterangan Transaksi</th>
                            <th class="text-center">Tipe</th>
                            <th class="text-end">Nominal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['mutasi'])): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">Belum ada riwayat mutasi saldo.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['mutasi'] as $m): ?>
                                <tr>
                                    <td><small class="text-muted"><?= htmlspecialchars($m['tgl']) ?></small></td>
                                    <td><?= htmlspecialchars($m['keterangan']) ?></td>
                                    <td class="text-center">
                                        <?php if ($m['tipe'] === 'masuk'): ?>
                                            <span class="badge bg-success-subtle text-success border border-success"><i class="bi bi-arrow-down-left me-1"></i>Masuk</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger"><i class="bi bi-arrow-up-right me-1"></i>Keluar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end fw-bold <?= ($m['tipe'] === 'masuk') ? 'text-success' : 'text-danger' ?>">
                                        <?= ($m['tipe'] === 'masuk') ? '+' : '-' ?> Rp <?= number_format($m['jumlah'], 0, ',', '.') ?>
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

<!-- Modal Tarik Saldo (UC 19) -->
<div class="modal fade" id="modalTarikSaldo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>/toko/tarik_saldo" method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-cash-coin me-2"></i>Form Penarikan Saldo Toko</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Saldo Tersedia:</label>
                        <div class="form-control bg-light fw-bold text-success">
                            Rp <?= number_format($data['toko']['saldo'] ?? 0, 0, ',', '.') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nominal Penarikan (Rp):</label>
                        <input type="number" name="jumlah" class="form-control" min="10000" max="<?= (float)($data['toko']['saldo'] ?? 0) ?>" step="1000" placeholder="Minimal Rp 10.000" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Bank Tujuan:</label>
                        <select name="bank" class="form-select">
                            <option value="BCA">Bank BCA</option>
                            <option value="Mandiri">Bank Mandiri</option>
                            <option value="BNI">Bank BNI</option>
                            <option value="BRI">Bank BRI</option>
                            <option value="Jago">Bank Jago</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nomor Rekening Tujuan:</label>
                        <input type="text" name="no_rekening" class="form-control" placeholder="Contoh: 1234567890" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Konfirmasi Penarikan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
