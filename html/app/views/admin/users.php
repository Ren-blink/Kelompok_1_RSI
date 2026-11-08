<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-people-fill me-2 text-primary"></i>Kelola Akun Pengguna (UC 21)</h3>
            <p class="text-muted mb-0">Manajemen hak akses, role pembeli, penjual (merchant), dan administrator.</p>
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
                            <th>ID User</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Tanggal Terdaftar</th>
                            <th>Role Saat Ini</th>
                            <th class="text-center" style="width: 250px;">Ubah Hak Akses (Role)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['users'])): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Belum ada akun pengguna terdaftar.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['users'] as $u): ?>
                                <?php
                                    $role = $u['role'];
                                    $badge = 'bg-secondary';
                                    if ($role === 'admin') $badge = 'bg-danger';
                                    elseif ($role === 'penjual') $badge = 'bg-primary';
                                    elseif ($role === 'pembeli') $badge = 'bg-success';
                                ?>
                                <tr>
                                    <td class="fw-bold">#<?= htmlspecialchars($u['id_user']) ?></td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($u['nama']) ?></div>
                                    </td>
                                    <td><code><?= htmlspecialchars($u['email']) ?></code></td>
                                    <td><small class="text-muted"><?= htmlspecialchars($u['tgl_daftar']) ?></small></td>
                                    <td>
                                        <span class="badge <?= $badge ?> text-uppercase"><?= htmlspecialchars($role) ?></span>
                                    </td>
                                    <td>
                                        <form action="<?= BASE_URL ?>/admin/ubah_role" method="POST" class="d-flex gap-1 justify-content-center">
                                            <input type="hidden" name="id_user" value="<?= htmlspecialchars($u['id_user']) ?>">
                                            <select name="role" class="form-select form-select-sm w-auto">
                                                <option value="pembeli" <?= ($role === 'pembeli') ? 'selected' : '' ?>>Pembeli</option>
                                                <option value="penjual" <?= ($role === 'penjual') ? 'selected' : '' ?>>Penjual</option>
                                                <option value="admin" <?= ($role === 'admin') ? 'selected' : '' ?>>Admin</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-primary fw-bold" onclick="return confirm('Ubah role pengguna ini?')">
                                                Simpan
                                            </button>
                                        </form>
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
