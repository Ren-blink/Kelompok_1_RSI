<?php

class TokoController extends Controller
{
    private $tokoModel;
    private $produkModel;
    private $orderModel;
    private $pengirimanModel;

    public function __construct()
    {
        // Portal Toko hanya untuk penjual dan admin
        $this->requireRole(['penjual', 'admin']);
        $this->tokoModel = $this->model('TokoModel');
        $this->produkModel = $this->model('ProdukModel');
        $this->orderModel = $this->model('OrderModel');
        $this->pengirimanModel = $this->model('PengirimanModel');
    }

    private function getActiveTokoId()
    {
        $userId = $_SESSION['user']['id_user'];
        $toko = $this->tokoModel->getByUserId($userId);
        return $toko ? $toko['id_toko'] : 1;
    }

    /**
     * Dashboard Penjual / Merchant
     */
    public function index()
    {
        $idToko = $this->getActiveTokoId();
        $toko = $this->tokoModel->getById($idToko);
        $produkList = $this->produkModel->getProdukByToko($idToko);
        $pesananList = $this->orderModel->getPesananByToko($idToko);
        $laporan = $this->tokoModel->getLaporanPenjualan($idToko);

        $data = [
            'title' => 'Dashboard Toko - ' . ($toko['nama_toko'] ?? 'Toko Saya'),
            'toko' => $toko,
            'total_produk' => count($produkList),
            'total_pesanan' => count($pesananList),
            'laporan' => $laporan
        ];

        $this->view('toko/dashboard', $data);
    }

    /**
     * Sesuai UC 13: Kelola Produk CRUD Toko - Tampilkan Daftar Produk
     */
    public function produk()
    {
        $idToko = $this->getActiveTokoId();
        $toko = $this->tokoModel->getById($idToko);
        $produkList = $this->produkModel->getProdukByToko($idToko);

        $data = [
            'title' => 'Kelola Produk Toko',
            'toko' => $toko,
            'produk' => $produkList
        ];

        $this->view('toko/produk_list', $data);
    }

    /**
     * Sesuai UC 13: Form Tambah Produk
     */
    public function tambah_produk()
    {
        $idToko = $this->getActiveTokoId();
        $toko = $this->tokoModel->getById($idToko);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_produk' => trim($_POST['nama_produk'] ?? ''),
                'kategori' => trim($_POST['kategori'] ?? 'Umum'),
                'harga' => (float)($_POST['harga'] ?? 0),
                'stok' => (int)($_POST['stok'] ?? 0),
                'deskripsi' => trim($_POST['deskripsi'] ?? ''),
                'gambar' => trim($_POST['gambar'] ?? 'https://images.unsplash.com/photo-1526738549149-8e07eca6c147?w=600&auto=format&fit=crop&q=80')
            ];

            if (empty($data['nama_produk']) || $data['harga'] <= 0) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['flash_error'] = 'Nama produk dan harga valid wajib diisi.';
                header('Location: ' . BASE_URL . '/toko/tambah_produk');
                exit;
            }

            $success = $this->produkModel->tambahProduk($idToko, $data);
            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($success) {
                $_SESSION['flash_success'] = 'Produk baru berhasil ditambahkan!';
                header('Location: ' . BASE_URL . '/toko/produk');
            } else {
                $_SESSION['flash_error'] = 'Gagal menambahkan produk.';
                header('Location: ' . BASE_URL . '/toko/tambah_produk');
            }
            exit;
        }

        $data = [
            'title' => 'Tambah Produk Baru',
            'toko' => $toko
        ];

        $this->view('toko/produk_form', $data);
    }

    /**
     * Sesuai UC 13: Edit Produk
     */
    public function edit_produk($idProduk)
    {
        $idToko = $this->getActiveTokoId();
        $toko = $this->tokoModel->getById($idToko);
        $produk = $this->produkModel->getDetailProduk($idProduk);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_produk' => trim($_POST['nama_produk'] ?? ''),
                'kategori' => trim($_POST['kategori'] ?? 'Umum'),
                'harga' => (float)($_POST['harga'] ?? 0),
                'stok' => (int)($_POST['stok'] ?? 0),
                'deskripsi' => trim($_POST['deskripsi'] ?? ''),
                'gambar' => trim($_POST['gambar'] ?? '')
            ];

            $success = $this->produkModel->updateProduk($idProduk, $idToko, $data);
            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($success) {
                $_SESSION['flash_success'] = 'Data produk berhasil diperbarui!';
            } else {
                $_SESSION['flash_error'] = 'Gagal memperbarui produk.';
            }
            header('Location: ' . BASE_URL . '/toko/produk');
            exit;
        }

        $data = [
            'title' => 'Edit Produk #' . $idProduk,
            'toko' => $toko,
            'produk' => $produk
        ];

        $this->view('toko/produk_form', $data);
    }

    /**
     * Sesuai UC 13: Hapus Produk
     */
    public function hapus_produk($idProduk)
    {
        $idToko = $this->getActiveTokoId();
        $success = $this->produkModel->hapusProduk($idProduk, $idToko);

        if (session_status() === PHP_SESSION_NONE) session_start();
        if ($success) {
            $_SESSION['flash_success'] = 'Produk berhasil dihapus dari toko Anda.';
        } else {
            $_SESSION['flash_error'] = 'Gagal menghapus produk.';
        }

        header('Location: ' . BASE_URL . '/toko/produk');
        exit;
    }

    /**
     * Sesuai UC 14: Kelola Profil Toko
     */
    public function profil()
    {
        $idToko = $this->getActiveTokoId();
        $toko = $this->tokoModel->getById($idToko);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $namaToko = trim($_POST['nama_toko'] ?? '');
            $deskripsi = trim($_POST['deskripsi'] ?? '');

            $success = $this->tokoModel->updateProfil($idToko, $namaToko, $deskripsi);
            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($success) {
                $_SESSION['flash_success'] = 'Profil toko berhasil diperbarui!';
            } else {
                $_SESSION['flash_error'] = 'Gagal memperbarui profil toko.';
            }
            header('Location: ' . BASE_URL . '/toko/profil');
            exit;
        }

        $data = [
            'title' => 'Pengaturan Profil Toko',
            'toko' => $toko
        ];

        $this->view('toko/profil', $data);
    }

    /**
     * Sesuai UC 15: Kelola Order Masuk Toko
     */
    public function pesanan()
    {
        $idToko = $this->getActiveTokoId();
        $toko = $this->tokoModel->getById($idToko);
        $pesananList = $this->orderModel->getPesananByToko($idToko);

        $data = [
            'title' => 'Kelola Pesanan Masuk',
            'toko' => $toko,
            'pesanan' => $pesananList
        ];

        $this->view('toko/pesanan_masuk', $data);
    }

    /**
     * Sesuai UC 15: Memproses Pesanan
     */
    public function proses_pesanan($idPesanan)
    {
        $this->orderModel->updateStatusPesanan($idPesanan, 'diproses');
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['flash_success'] = 'Pesanan #' . $idPesanan . ' berhasil diproses!';
        header('Location: ' . BASE_URL . '/toko/pesanan');
        exit;
    }

    /**
     * Sesuai UC 16: Mengajukan / Atur Pengiriman Toko
     * Penjual menginput resi kurir pengiriman
     */
    public function kirim_pesanan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idToko = $this->getActiveTokoId();
            $idPesanan = (int)($_POST['id_pesanan'] ?? 0);
            $noResi = trim($_POST['no_resi'] ?? '');

            if (empty($noResi)) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['flash_error'] = 'Nomor Resi kurir wajib diisi.';
                header('Location: ' . BASE_URL . '/toko/pesanan');
                exit;
            }

            $success = $this->pengirimanModel->setPengiriman($idPesanan, $idToko, $noResi);

            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($success) {
                $_SESSION['flash_success'] = 'Nomor resi ' . htmlspecialchars($noResi) . ' berhasil disimpan. Pesanan #' . $idPesanan . ' kini berstatus "Dikirim"!';
            } else {
                $_SESSION['flash_error'] = 'Gagal menyimpan nomor resi pengiriman.';
            }

            header('Location: ' . BASE_URL . '/toko/pesanan');
            exit;
        }

        header('Location: ' . BASE_URL . '/toko/pesanan');
        exit;
    }

    /**
     * Sesuai UC 17: Melihat Laporan Penjualan
     */
    public function laporan()
    {
        $idToko = $this->getActiveTokoId();
        $toko = $this->tokoModel->getById($idToko);

        $startDate = $_GET['start'] ?? '';
        $endDate = $_GET['end'] ?? '';

        $laporan = $this->tokoModel->getLaporanPenjualan($idToko, $startDate, $endDate);

        $data = [
            'title' => 'Laporan Penjualan Toko',
            'toko' => $toko,
            'laporan' => $laporan,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        $this->view('toko/laporan', $data);
    }

    /**
     * Sesuai UC 18: Melihat Saldo Toko & Riwayat Mutasi
     */
    public function saldo()
    {
        $idToko = $this->getActiveTokoId();
        $toko = $this->tokoModel->getById($idToko);
        $mutasi = $this->tokoModel->getMutasiSaldo($idToko);

        $data = [
            'title' => 'Saldo & Keuangan Toko',
            'toko' => $toko,
            'mutasi' => $mutasi
        ];

        $this->view('toko/saldo', $data);
    }

    /**
     * Sesuai UC 19: Tarik Saldo Toko
     */
    public function tarik_saldo()
    {
        $idToko = $this->getActiveTokoId();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jumlah = (float)($_POST['jumlah'] ?? 0);
            $bank = trim($_POST['bank'] ?? 'BCA');
            $noRekening = trim($_POST['no_rekening'] ?? '');

            if (empty($noRekening) || $jumlah <= 0) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['flash_error'] = 'Jumlah penarikan dan nomor rekening wajib diisi dengan benar.';
                header('Location: ' . BASE_URL . '/toko/saldo');
                exit;
            }

            $res = $this->tokoModel->tarikSaldo($idToko, $jumlah, $bank, $noRekening);

            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($res['status']) {
                $_SESSION['flash_success'] = $res['message'];
            } else {
                $_SESSION['flash_error'] = $res['message'];
            }

            header('Location: ' . BASE_URL . '/toko/saldo');
            exit;
        }

        header('Location: ' . BASE_URL . '/toko/saldo');
        exit;
    }
}
