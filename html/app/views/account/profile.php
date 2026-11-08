<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <!-- Sesuai Sequence Diagram 1: Laman Profile -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-person-lines-fill me-2"></i>Laman Profile Pengguna</h5>
                    <span class="badge bg-primary"><?= htmlspecialchars($profile['role'] ?? 'Pengguna') ?></span>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success py-2 small" role="alert">
                            <i class="bi bi-check-circle me-1"></i><?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Form Mengubah Profile (setProfile) -->
                    <form action="<?= BASE_URL ?>/account/setProfile" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($profile['nama'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Alamat Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($profile['email'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Password Baru (Kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-muted">Tanggal Pendaftaran</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($profile['tgl_daftar'] ?? '-') ?>" readonly>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan
                            </button>
                            
                            <!-- Logout Button (Sesuai SD 1 Frame 3) -->
                            <a href="<?= BASE_URL ?>/account/logout" class="btn btn-outline-secondary">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </a>
                        </div>
                    </form>

                    <!-- Hapus Akun / hapusProfile (Sesuai SD 1 Frame 4) -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-danger fw-bold mb-0">Hapus Akun Pengguna</h6>
                                <small class="text-muted">Aksi ini permanen dan akan menghapus semua data Anda.</small>
                            </div>
                            <a href="<?= BASE_URL ?>/account/hapusProfile" class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen?')">
                                <i class="bi bi-trash me-1"></i>Hapus Akun
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
