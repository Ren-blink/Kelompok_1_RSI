<?php

class KeranjangController extends Controller
{
    public function __construct()
    {
        $this->requireLogin('Silakan daftar atau masuk (login) terlebih dahulu untuk mengakses keranjang belanja.');
    }

    public function index()
    {
        $this->getIsiKeranjang();
    }

    /**
     * Sesuai Sequence Diagram 5: getIsiKeranjang()
     * Menampilkan isi keranjang belanja ke LamanKeranjang
     */
    public function getIsiKeranjang()
    {
        $userId = $_SESSION['user']['id_user'];
        $keranjangModel = $this->model('KeranjangModel');
        $items = $keranjangModel->getIsiKeranjang($userId);

        $isKosong = empty($items);
        $errorKeranjangKosong = null;
        if ($isKosong) {
            // Sesuai Sequence Diagram alt: returnErrorKeranjangKosong()
            $errorKeranjangKosong = "Keranjang Belanja Anda saat ini masih kosong.";
        }

        $total = 0;
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }

        $data = [
            'title' => 'Laman Keranjang - Kelompok 1 RSI',
            'items' => $items,
            'total' => $total,
            'errorKeranjangKosong' => $errorKeranjangKosong,
            'message' => $_SESSION['cart_message'] ?? null,
            'error' => $_SESSION['cart_error'] ?? null
        ];
        unset($_SESSION['cart_message'], $_SESSION['cart_error']);

        // Tampilkan View LamanKeranjang
        $this->view('templates/header', $data);
        $this->view('keranjang/index', $data);
        $this->view('templates/footer');
    }

    /**
     * Sesuai Sequence Diagram 3: setKeranjang() & setJumlahBarang()
     * Memasukkan produk ke keranjang dari DetailProduk
     */
    public function setKeranjang()
    {
        $userId = $_SESSION['user']['id_user'];
        $idProduk = $_POST['id_produk'] ?? null;
        $jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 1;

        if ($idProduk) {
            $keranjangModel = $this->model('KeranjangModel');

            // Cek apakah produk sudah ada di keranjang
            if ($keranjangModel->cekItemAda($userId, $idProduk)) {
                // Sesuai SD 3 Frame 2: setJumlahBarang() & renewKeranjang()
                $keranjangModel->setJumlahBarang($userId, $idProduk, $jumlah);
                $_SESSION['cart_message'] = 'Jumlah produk di keranjang berhasil diperbarui (renewKeranjang)!';
            } else {
                // Sesuai SD 3 Frame 1: setKeranjang() & returnStatusKeranjang(success)
                $keranjangModel->setKeranjang($userId, $idProduk, $jumlah);
                $_SESSION['cart_message'] = 'Produk berhasil ditambahkan ke keranjang (success)!';
            }
        }

        header('Location: ' . BASE_URL . '/keranjang/getIsiKeranjang');
        exit;
    }

    /**
     * Sesuai Sequence Diagram 5: setJumlahProduk()
     * Mengubah jumlah produk dari LamanKeranjang
     */
    public function setJumlahProduk()
    {
        $userId = $_SESSION['user']['id_user'];
        $idKeranjang = $_POST['id_keranjang'] ?? null;
        $newQty = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 0;

        if ($idKeranjang) {
            if ($newQty < 1) {
                // Sesuai SD 5 alt: returnErrorJumlahProduk()
                $_SESSION['cart_error'] = 'Jumlah produk tidak valid! Minimal 1.';
            } else {
                $keranjangModel = $this->model('KeranjangModel');
                $keranjangModel->setJumlahProduk($idKeranjang, $userId, $newQty);
                $_SESSION['cart_message'] = 'Jumlah produk berhasil diubah!';
            }
        }

        header('Location: ' . BASE_URL . '/keranjang/getIsiKeranjang');
        exit;
    }

    /**
     * Sesuai Sequence Diagram 5: hapusProduk()
     * Menghapus produk tertentu atau semua produk
     */
    public function hapusProduk($idKeranjang = null)
    {
        $userId = $_SESSION['user']['id_user'];
        $keranjangModel = $this->model('KeranjangModel');

        $keranjangModel->hapusProduk($idKeranjang, $userId);
        $_SESSION['cart_message'] = 'Produk berhasil dihapus dari keranjang (returnHapusProduk: sukses)!';

        header('Location: ' . BASE_URL . '/keranjang/getIsiKeranjang');
        exit;
    }

    // Alias untuk backwards compatibility
    public function add($productId = null)
    {
        $_POST['id_produk'] = $productId;
        $this->setKeranjang();
    }

    public function remove($cartId = null)
    {
        $this->hapusProduk($cartId);
    }
}
