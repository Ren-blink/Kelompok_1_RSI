<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill text-primary fs-1"></i>
                        <h4 class="fw-bold mt-2">Daftar Akun Baru</h4>
                        <p class="text-muted small">Buat akun untuk mulai berbelanja atau membuka toko</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger py-2 small" role="alert">
                            <i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success py-2 small" role="alert">
                            <i class="bi bi-check-circle me-1"></i><?= htmlspecialchars($success) ?>
                            <a href="<?= BASE_URL ?>/auth/login" class="fw-bold alert-link ms-1">Login di sini</a>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/auth/register" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" required placeholder="Contoh: Rayhan Nauvan">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small">Alamat Email</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="nama@email.com">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold small">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Minimal 8 karakter">
                        </div>
                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold small">Daftar Sebagai</label>
                            <select class="form-select" id="role" name="role">
                                <option value="pembeli" selected>Pembeli (Customer)</option>
                                <option value="penjual">Penjual (Buka Toko)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            Daftar Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-3 small">
                        Sudah punya akun? <a href="<?= BASE_URL ?>/auth/login" class="fw-semibold">Masuk di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
