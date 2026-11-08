<?php

class PembayaranController extends Controller
{
    private $pembayaranModel;
    private $orderModel;

    public function __construct()
    {
        $this->requireLogin('Silakan masuk (login) terlebih dahulu untuk mengakses halaman pembayaran.');
        $this->pembayaranModel = $this->model('PembayaranModel');
        $this->orderModel = $this->model('OrderModel');
    }

    /**
     * Sesuai UC 07: Melakukan Pembayaran
     * Menampilkan form pembayaran untuk suatu pesanan
     */
    public function index($idPesanan = null)
    {
        if (empty($idPesanan)) {
            // Ambil pesanan terakhir milik user yang menunggu pembayaran
            $userId = $_SESSION['user']['id_user'];
            $pesananList = $this->orderModel->getPesananByUser($userId);
            if (!empty($pesananList)) {
                $idPesanan = $pesananList[0]['id_pesanan'];
            } else {
                $idPesanan = 1;
            }
        }

        $pesanan = $this->orderModel->getPesananById($idPesanan);
        $pembayaran = $this->pembayaranModel->getPembayaranByPesanan($idPesanan);

        $data = [
            'title' => 'Pembayaran Pesanan #' . $idPesanan,
            'pesanan' => $pesanan,
            'pembayaran' => $pembayaran,
            'id_pesanan' => $idPesanan
        ];

        $this->view('pembayaran/index', $data);
    }

    /**
     * Sesuai UC 07: Memproses submit pembayaran
     */
    public function bayar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idPesanan = $_POST['id_pesanan'] ?? 1;
            $metode = $_POST['metode'] ?? 'Bank Transfer BCA';

            $result = $this->pembayaranModel->bayarPesanan($idPesanan, $metode);

            if ($result && $result['status']) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['flash_success'] = 'Pembayaran berhasil dikonfirmasi via ' . htmlspecialchars($metode) . '! Kode Referensi Gateway: ' . $result['id_transaksi_gateway'];
                header('Location: ' . BASE_URL . '/pembayaran/lacak/' . $idPesanan);
                exit;
            } else {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['flash_error'] = 'Gagal memproses pembayaran: ' . ($result['message'] ?? 'Terjadi kesalahan sistem.');
                header('Location: ' . BASE_URL . '/pembayaran/index/' . $idPesanan);
                exit;
            }
        }

        header('Location: ' . BASE_URL . '/pembayaran');
        exit;
    }

    /**
     * Sesuai UC 08: Melacak Pembayaran
     * Menampilkan status verifikasi pembayaran gateway
     */
    public function lacak($idPesanan = null)
    {
        if (empty($idPesanan)) {
            $idPesanan = 1;
        }

        $pesanan = $this->orderModel->getPesananById($idPesanan);
        $pembayaran = $this->pembayaranModel->getPembayaranByPesanan($idPesanan);

        $data = [
            'title' => 'Pelacakan Status Pembayaran #' . $idPesanan,
            'pesanan' => $pesanan,
            'pembayaran' => $pembayaran,
            'id_pesanan' => $idPesanan
        ];

        $this->view('pembayaran/lacak', $data);
    }
}
