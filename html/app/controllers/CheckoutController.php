<?php

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->requireLogin('Silakan daftar atau masuk (login) terlebih dahulu untuk melakukan checkout pemesanan.');
    }

    public function index()
    {
        $this->doCheckout();
    }

    /**
     * Sesuai Sequence Diagram 2: doCheckout()
     * Memvalidasi stok barang via ProductModel, lalu redirectCheckout ke LamanCheckout
     */
    public function doCheckout()
    {
        $userId = $_SESSION['user']['id_user'];
        $cartModel = $this->model('CartModel');
        $productModel = $this->model('ProductModel');

        $items = $cartModel->getItemsByUser($userId);

        if (empty($items)) {
            $_SESSION['cart_error'] = 'Keranjang Anda kosong! Silakan pilih produk terlebih dahulu.';
            header('Location: ' . BASE_URL . '/keranjang/getIsiKeranjang');
            exit;
        }

        // 1. Validasi Stok Produk
        foreach ($items as $item) {
            $isStokCukup = $productModel->validasiStok($item['id_produk'], $item['jumlah']);
            if (!$isStokCukup) {
                $_SESSION['cart_error'] = 'Stok untuk produk "' . htmlspecialchars($item['nama_produk']) . '" tidak mencukupi!';
                header('Location: ' . BASE_URL . '/keranjang/getIsiKeranjang');
                exit;
            }
        }

        $total = 0;
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }

        $data = [
            'title' => 'Laman Checkout - Kelompok 1 RSI',
            'items' => $items,
            'total' => $total,
            'alamat' => $_SESSION['checkout_alamat'] ?? '',
            'errorAlamat' => $_SESSION['checkout_error_alamat'] ?? null
        ];
        unset($_SESSION['checkout_error_alamat']);

        // Tampilkan Laman Checkout
        $this->view('templates/header', $data);
        $this->view('checkout/index', $data);
        $this->view('templates/footer');
    }

    /**
     * Sesuai Sequence Diagram 2: inputAlamat()
     * Menerima dan memvalidasi input alamat pengiriman
     */
    public function inputAlamat()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alamat = trim($_POST['alamat_pengiriman'] ?? '');

            // Validasi alamat (Minimal 10 karakter agar lengkap)
            if (empty($alamat) || strlen($alamat) < 10) {
                // Sesuai Sequence Diagram alt: returnErrorAlamat("Tidak Valid / Tidak Tersedia")
                $_SESSION['checkout_error_alamat'] = 'Alamat pengiriman tidak valid atau belum terisi lengkap (minimal 10 karakter)!';
                header('Location: ' . BASE_URL . '/checkout/doCheckout');
                exit;
            }

            $_SESSION['checkout_alamat'] = $alamat;
            $_SESSION['checkout_step'] = 'alamat_valid';
        }

        header('Location: ' . BASE_URL . '/checkout/doCheckout');
        exit;
    }

    /**
     * Sesuai Sequence Diagram 2: confirmPesanan()
     * Melakukan konfirmasi pesanan ke OrderModel dan menghasilkan status AWAITING PAYMENT
     */
    public function confirmPesanan()
    {
        $userId = $_SESSION['user']['id_user'];
        $alamat = $_SESSION['checkout_alamat'] ?? '';

        if (empty($alamat)) {
            $_SESSION['checkout_error_alamat'] = 'Silakan isi dan simpan alamat pengiriman terlebih dahulu!';
            header('Location: ' . BASE_URL . '/checkout/doCheckout');
            exit;
        }

        $cartModel = $this->model('CartModel');
        $items = $cartModel->getItemsByUser($userId);

        if (empty($items)) {
            // Sesuai Sequence Diagram alt: Otomatis Redirect ke Laman Keranjang
            header('Location: ' . BASE_URL . '/keranjang/getIsiKeranjang');
            exit;
        }

        $total = 0;
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }

        $orderModel = $this->model('OrderModel');
        // Panggil OrderModel::setOrder()
        $resultOrder = $orderModel->setOrder($userId, $alamat, $items, $total);

        // Bersihkan keranjang belanja setelah checkout
        $cartModel->hapusProduk(null, $userId);
        unset($_SESSION['checkout_alamat'], $_SESSION['checkout_step']);

        // Tampilkan konfirmasi order (AWAITING PAYMENT)
        $data = [
            'title' => 'Konfirmasi Pesanan - Awaiting Payment',
            'order' => $resultOrder,
            'items' => $items,
            'total' => $total
        ];

        $this->view('templates/header', $data);
        $this->view('checkout/confirm', $data);
        $this->view('templates/footer');
    }
}
