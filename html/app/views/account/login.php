<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <!-- Sesuai Sequence Diagram 1: Laman Login -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-lock-fill text-primary fs-1"></i>
                        <h4 class="fw-bold mt-2">Laman Login</h4>
                        <p class="text-muted small">Silakan masuk menggunakan akun terdaftar</p>
                    </div>

                    <?php if (!empty($_SESSION['flash_error'])): ?>
                        <div class="alert alert-warning py-2 small" role="alert">
                            <i class="bi bi-lock me-1"></i><?= htmlspecialchars($_SESSION['flash_error']) ?>
                        </div>
                        <?php unset($_SESSION['flash_error']); ?>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger py-2 small" role="alert">
                            <i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/account/login" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small">Alamat Email</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="nama@email.com" value="admin@rsi.com">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold small">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••" value="password123">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            Masuk ke Akun
                        </button>
                    </form>

                    <div class="alert alert-info py-2 px-3 mt-4 small mb-0">
                        <strong><i class="bi bi-info-circle me-1"></i>Akun Uji Coba:</strong><br>
                        Email: <code>admin@rsi.com</code> / <code>siti@pembeli.com</code><br>
                        Password: <code>password123</code>
                    </div>

                    <div class="text-center mt-3 small">
                        Belum punya akun? <a href="<?= BASE_URL ?>/account/register" class="fw-semibold">Daftar sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
