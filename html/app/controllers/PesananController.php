<?php

class PesananController extends Controller
{
    private $orderModel;
    private $pengirimanModel;

    public function __construct()
    {
        $this->requireLogin('Silakan daftar atau masuk (login) terlebih dahulu untuk mengakses menu Pesanan Saya.');
        $this->orderModel = $this->model('OrderModel');
        $this->pengirimanModel = $this->model('PengirimanModel');
    }

    /**
     * Menampilkan riwayat pesanan milik pembeli yang login
     */
    public function index()
    {
        $userId = $_SESSION['user']['id_user'];
        $daftarPesanan = $this->orderModel->getPesananByUser($userId);

        $data = [
            'title' => 'Riwayat Pesanan Saya',
            'pesanan' => $daftarPesanan
        ];

        $this->view('pesanan/index', $data);
    }

    /**
     * Sesuai UC 09: Melacak Pesanan
     * Menampilkan detail tracking perjalanan paket kurir & status pesanan
     */
    public function lacak($idPesanan = 1)
    {
        $pesanan = $this->orderModel->getPesananById($idPesanan);
        $pengiriman = $this->pengirimanModel->getPengirimanByPesanan($idPesanan);

        $data = [
            'title' => 'Lacak Pesanan #' . $idPesanan,
            'pesanan' => $pesanan,
            'pengiriman' => $pengiriman
        ];

        $this->view('pesanan/lacak', $data);
    }

    /**
     * Sesuai UC 11: Konfirmasi Penerimaan Paket
     * Pembeli mengonfirmasi pesanan telah sampai, status menjadi 'selesai'
     */
    public function konfirmasi($idPesanan)
    {
        $userId = $_SESSION['user']['id_user'];
        $result = $this->orderModel->konfirmasiSelesai($idPesanan, $userId);

        if ($result) {
            $_SESSION['flash_success'] = 'Terima kasih! Pesanan #' . $idPesanan . ' telah selesai. Dana berhasil diteruskan ke toko penjual. Silakan berikan ulasan produk di bawah ini!';
            header('Location: ' . BASE_URL . '/ulasan/index/' . $idPesanan);
            exit;
        } else {
            $_SESSION['flash_error'] = 'Gagal mengonfirmasi penerimaan pesanan.';
            header('Location: ' . BASE_URL . '/pesanan');
            exit;
        }
    }
}
