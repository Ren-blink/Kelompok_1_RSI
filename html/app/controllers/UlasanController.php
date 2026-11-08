<?php

class UlasanController extends Controller
{
    private $ulasanModel;
    private $orderModel;

    public function __construct()
    {
        $this->requireLogin('Silakan masuk (login) terlebih dahulu untuk memberi ulasan dan rating produk.');
        $this->ulasanModel = $this->model('UlasanModel');
        $this->orderModel = $this->model('OrderModel');
    }

    /**
     * Sesuai UC 10: Memberi Ulasan / Rating
     * Menampilkan form ulasan untuk produk-produk di dalam pesanan
     */
    public function index($idPesanan = 1)
    {
        $userId = $_SESSION['user']['id_user'];

        $pesanan = $this->orderModel->getPesananById($idPesanan);
        $ulasanTerdahulu = $this->ulasanModel->getUlasanByPesanan($idPesanan, $userId);

        $data = [
            'title' => 'Beri Ulasan Produk - Pesanan #' . $idPesanan,
            'pesanan' => $pesanan,
            'ulasanTerdahulu' => $ulasanTerdahulu,
            'id_pesanan' => $idPesanan
        ];

        $this->view('ulasan/form', $data);
    }

    /**
     * Sesuai UC 10: Simpan data ulasan
     */
    public function simpan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id_user'];

            $idPesanan = $_POST['id_pesanan'] ?? 1;
            $idProduk = $_POST['id_produk'] ?? 1;
            $rating = (int)($_POST['rating'] ?? 5);
            $komentar = trim($_POST['komentar'] ?? '');

            if (empty($komentar)) {
                $_SESSION['flash_error'] = 'Komentar ulasan wajib diisi.';
                header('Location: ' . BASE_URL . '/ulasan/index/' . $idPesanan);
                exit;
            }

            $success = $this->ulasanModel->tambahUlasan($idPesanan, $idProduk, $userId, $rating, $komentar);

            if ($success) {
                $_SESSION['flash_success'] = 'Ulasan dan rating Anda berhasil disimpan. Terima kasih!';
            } else {
                $_SESSION['flash_error'] = 'Terjadi kesalahan saat menyimpan ulasan.';
            }

            header('Location: ' . BASE_URL . '/ulasan/index/' . $idPesanan);
            exit;
        }

        header('Location: ' . BASE_URL . '/pesanan');
        exit;
    }
}
