<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-shield-shaded me-2 text-danger"></i>Moderasi Pelanggaran (UC 20)</h3>
            <p class="text-muted mb-0">Tinjau laporan dugaan pelanggaran dari pembeli dan tentukan tindakan sanksi.</p>
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

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Pelapor</th>
                            <th>Target Laporan (Produk/Toko)</th>
                            <th>Deskripsi Bukti Pelanggaran</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 220px;">Tindakan Administrator</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['laporan'])): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Belum ada laporan pelanggaran yang masuk.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['laporan'] as $l): ?>
                                <?php
                                    $st = $l['status'];
                                    $badge = 'bg-warning text-dark';
                                    if ($st === 'disetujui') $badge = 'bg-success';
                                    elseif ($st === 'ditolak') $badge = 'bg-danger';
                                ?>
                                <tr>
                                    <td class="fw-bold">#<?= htmlspecialchars($l['id_pelanggaran']) ?></td>
                                    <td><small class="text-muted"><?= htmlspecialchars($l['tgl_lapor']) ?></small></td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($l['nama_pelapor'] ?? 'User') ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($l['email_pelapor'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <?php if (!empty($l['nama_produk'])): ?>
                                            <div class="small fw-bold text-primary"><i class="bi bi-box me-1"></i><?= htmlspecialchars($l['nama_produk']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($l['nama_toko'])): ?>
                                            <div class="small text-muted"><i class="bi bi-shop me-1"></i><?= htmlspecialchars($l['nama_toko']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <p class="small text-secondary mb-0"><?= htmlspecialchars($l['deskripsi']) ?></p>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?= $badge ?> text-uppercase"><?= str_replace('_', ' ', htmlspecialchars($st)) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($st === 'menunggu_review'): ?>
                                            <div class="d-flex gap-1 justify-content-center">
                                                <form action="<?= BASE_URL ?>/admin/tindakan_pelanggaran" method="POST">
                                                    <input type="hidden" name="id_pelanggaran" value="<?= htmlspecialchars($l['id_pelanggaran']) ?>">
                                                    <input type="hidden" name="status" value="disetujui">
                                                    <button type="submit" class="btn btn-sm btn-success fw-bold" onclick="return confirm('Setujui laporan dan beri sanksi pada target?')">
                                                        <i class="bi bi-check-lg me-1"></i>Setujui
                                                    </button>
                                                </form>

                                                <form action="<?= BASE_URL ?>/admin/tindakan_pelanggaran" method="POST">
                                                    <input type="hidden" name="id_pelanggaran" value="<?= htmlspecialchars($l['id_pelanggaran']) ?>">
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tolak laporan ini sebagai tidak valid?')">
                                                        <i class="bi bi-x-lg me-1"></i>Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <small class="text-muted d-block text-center"><i class="bi bi-lock me-1"></i>Telah Ditindaklanjuti</small>
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
