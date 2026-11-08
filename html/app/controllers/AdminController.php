<?php

class AdminController extends Controller
{
    private $userModel;
    private $pelanggaranModel;
    private $pembayaranModel;
    private $orderModel;

    public function __construct()
    {
        // Dashboard Admin hanya untuk admin
        $this->requireRole('admin');
        $this->userModel = $this->model('UserModel');
        $this->pelanggaranModel = $this->model('PelanggaranModel');
        $this->pembayaranModel = $this->model('PembayaranModel');
        $this->orderModel = $this->model('OrderModel');
    }

    /**
     * Dashboard Administrator
     */
    public function index()
    {
        $users = $this->userModel->getAllUsers();
        $pelanggaran = $this->pelanggaranModel->getAllPelanggaran();
        $transaksi = $this->pembayaranModel->getAllPembayaran();

        $data = [
            'title' => 'Dashboard Administrator',
            'total_users' => count($users),
            'total_pelanggaran' => count($pelanggaran),
            'total_transaksi' => count($transaksi),
            'pelanggaran' => array_slice($pelanggaran, 0, 5),
            'transaksi' => array_slice($transaksi, 0, 5)
        ];

        $this->view('admin/index', $data);
    }

    /**
     * Sesuai UC 21: Kelola Akun Pembeli & Merchant
     */
    public function users()
    {
        $users = $this->userModel->getAllUsers();

        $data = [
            'title' => 'Kelola Akun Pengguna',
            'users' => $users
        ];

        $this->view('admin/users', $data);
    }

    /**
     * Sesuai UC 21: Ubah Role Pengguna
     */
    public function ubah_role()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idUser = (int)($_POST['id_user'] ?? 0);
            $role = $_POST['role'] ?? 'pembeli';

            $success = $this->userModel->updateRole($idUser, $role);
            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($success) {
                $_SESSION['flash_success'] = 'Role akun #' . $idUser . ' berhasil diubah menjadi ' . $role . '.';
            } else {
                $_SESSION['flash_error'] = 'Gagal mengubah role pengguna.';
            }

            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    /**
     * Sesuai UC 20: Kelola Laporan Pelanggaran oleh Admin
     */
    public function pelanggaran()
    {
        $laporan = $this->pelanggaranModel->getAllPelanggaran();

        $data = [
            'title' => 'Moderasi Laporan Pelanggaran',
            'laporan' => $laporan
        ];

        $this->view('admin/pelanggaran', $data);
    }

    /**
     * Sesuai UC 20: Tinjau & Ambil Tindakan atas Laporan Pelanggaran
     */
    public function tindakan_pelanggaran()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idPelanggaran = (int)($_POST['id_pelanggaran'] ?? 0);
            $status = $_POST['status'] ?? 'disetujui'; // 'disetujui' atau 'ditolak'

            $success = $this->pelanggaranModel->updateStatusPelanggaran($idPelanggaran, $status);
            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($success) {
                $_SESSION['flash_success'] = 'Laporan pelanggaran #' . $idPelanggaran . ' telah di-' . $status . '.';
            } else {
                $_SESSION['flash_error'] = 'Gagal memperbarui status laporan.';
            }

            header('Location: ' . BASE_URL . '/admin/pelanggaran');
            exit;
        }

        header('Location: ' . BASE_URL . '/admin/pelanggaran');
        exit;
    }

    /**
     * Sesuai UC 22: Memproses Transaksi Payment Gateway
     * Monitor transaksi dan simulasi callback webhook gateway
     */
    public function transaksi()
    {
        $transaksi = $this->pembayaranModel->getAllPembayaran();

        $data = [
            'title' => 'Monitor Transaksi & Simulasi Payment Gateway',
            'transaksi' => $transaksi
        ];

        $this->view('admin/transaksi', $data);
    }

    /**
     * Sesuai UC 22: Simulasi Callback Payment Gateway
     */
    public function simulasi_callback()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idGateway = trim($_POST['id_transaksi_gateway'] ?? '');
            $status = trim($_POST['status_bayar'] ?? 'berhasil');

            $success = $this->pembayaranModel->prosesGatewayCallback($idGateway, $status);

            if (session_status() === PHP_SESSION_NONE) session_start();
            if ($success) {
                $_SESSION['flash_success'] = 'Callback Payment Gateway untuk transaksi ' . htmlspecialchars($idGateway) . ' dengan status "' . htmlspecialchars($status) . '" berhasil disimulasikan!';
            } else {
                $_SESSION['flash_error'] = 'Gagal memproses callback gateway.';
            }

            header('Location: ' . BASE_URL . '/admin/transaksi');
            exit;
        }

        header('Location: ' . BASE_URL . '/admin/transaksi');
        exit;
    }
}
