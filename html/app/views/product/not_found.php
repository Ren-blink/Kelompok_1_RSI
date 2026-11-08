<div class="container text-center py-5">
    <div class="py-5">
        <i class="bi bi-search text-muted display-1"></i>
        <h2 class="fw-bold mt-3">Produk Tidak Ditemukan</h2>
        <p class="text-muted">ID Produk #<?= htmlspecialchars($id ?? '') ?> tidak tersedia dalam katalog kami.</p>
        <a href="<?= BASE_URL ?>/product" class="btn btn-primary mt-2">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Katalog
        </a>
    </div>
</div>
