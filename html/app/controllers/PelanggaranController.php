<?php

class PelanggaranController extends Controller
{
    private $pelanggaranModel;
    private $produkModel;

    public function __construct()
    {
        $this->requireLogin('Silakan masuk (login) terlebih dahulu untuk melaporkan pelanggaran.');
        $this->pelanggaranModel = $this->model('PelanggaranModel');
        $this->produkModel = $this->model('ProdukModel');
    }

    /**
     * Sesuai UC 12: Melaporkan Pelanggaran
     * Menampilkan form pelaporan pelanggaran
     */
    public function index($idProduk = null)
    {
        $produk = null;
        if (!empty($idProduk)) {
            $produk = $this->produkModel->getDetailProduk($idProduk);
        }

        $semuaProduk = $this->produkModel->getProduk();

        $data = [
            'title' => 'Laporkan Pelanggaran',
            'produkTerpilih' => $produk,
            'semuaProduk' => $semuaProduk
        ];

        $this->view('pelanggaran/buat', $data);
    }

    /**
     * Simpan laporan pelanggaran ke database
     */
    public function simpan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $userId = $_SESSION['user']['id_user'] ?? 3;

            $idProduk = !empty($_POST['id_produk']) ? (int)$_POST['id_produk'] : null;
            $deskripsi = trim($_POST['deskripsi'] ?? '');

            if (empty($deskripsi)) {
                $_SESSION['flash_error'] = 'Deskripsi pelanggaran tidak boleh kosong.';
                header('Location: ' . BASE_URL . '/pelanggaran');
                exit;
            }

            // Ambil id_toko dari produk jika ada
            $idToko = null;
            if ($idProduk) {
                $p = $this->produkModel->getDetailProduk($idProduk);
                if ($p) $idToko = $p['id_toko'];
            }

            $success = $this->pelanggaranModel->laporkanPelanggaran($userId, $deskripsi, $idProduk, $idToko);

            if ($success) {
                $_SESSION['flash_success'] = 'Laporan pelanggaran Anda berhasil dikirim dan akan segera ditinjau oleh Admin.';
            } else {
                $_SESSION['flash_error'] = 'Gagal mengirim laporan pelanggaran.';
            }

            header('Location: ' . BASE_URL . '/pelanggaran');
            exit;
        }

        header('Location: ' . BASE_URL);
        exit;
    }
}
