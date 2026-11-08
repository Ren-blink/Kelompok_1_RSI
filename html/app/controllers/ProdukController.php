<?php

class ProdukController extends Controller
{
    public function index()
    {
        $this->getProduk();
    }

    /**
     * Sesuai Sequence Diagram 4: getProduk(keyword)
     * Mencari produk berdasarkan kata kunci atau menampilkan seluruh produk
     */
    public function getProduk($keyword = null)
    {
        // Tangkap keyword baik dari parameter URL maupun dari query $_GET['keyword']
        if ($keyword === null && isset($_GET['keyword'])) {
            $keyword = trim($_GET['keyword']);
        }

        $produkModel = $this->model('ProdukModel');
        $hasilProduk = $produkModel->getProduk($keyword);

        $errorMessage = null;
        if (empty($hasilProduk) && !empty($keyword)) {
            // Sesuai Sequence Diagram alt: returnError("Produk Tidak Ditemukan")
            $errorMessage = "Produk Tidak Ditemukan";
        }

        $data = [
            'title' => !empty($keyword) ? 'Hasil Pencarian: "' . htmlspecialchars($keyword) . '"' : 'Daftar Produk',
            'products' => $hasilProduk,
            'keyword' => $keyword,
            'errorMessage' => $errorMessage
        ];

        // Tampilkan View DaftarProduk
        $this->view('templates/header', $data);
        $this->view('produk/daftar_produk', $data);
        $this->view('templates/footer');
    }

    /**
     * Sesuai Sequence Diagram 4: getDetailProduk(id)
     * Mengambil dan menampilkan detail produk ke view DetailProduk
     * Dibatasi: Hanya pengguna yang sudah terdaftar & login yang dapat mengakses detail produk
     */
    public function getDetailProduk($id = null)
    {
        // Guard: Wajib login untuk mengecek detail produk
        $this->requireLogin('Silakan daftar atau masuk (login) terlebih dahulu untuk mengecek detail dan memesan produk.');

        if ($id === null && isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        if (!$id) {
            header('Location: ' . BASE_URL . '/produk/getProduk');
            exit;
        }

        $produkModel = $this->model('ProdukModel');
        $produk = $produkModel->getDetailProduk($id);

        if (!$produk) {
            $data = [
                'title' => 'Produk Tidak Ditemukan',
                'id' => $id
            ];
            $this->view('templates/header', $data);
            $this->view('product/not_found', $data);
            $this->view('templates/footer');
            return;
        }

        $ulasanModel = $this->model('UlasanModel');
        $ulasan = $ulasanModel->getUlasanByProduk($id);

        $data = [
            'title' => $produk['nama_produk'] . ' - Detail Produk',
            'product' => $produk,
            'ulasan' => $ulasan
        ];

        // Tampilkan View DetailProduk
        $this->view('templates/header', $data);
        $this->view('produk/detail_produk', $data);
        $this->view('templates/footer');
    }

    // Alias untuk backwards compatibility
    public function detail($id = null)
    {
        $this->getDetailProduk($id);
    }
}
