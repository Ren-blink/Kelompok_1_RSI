<?php

class ProdukModel
{
    protected $db;
    protected $table = 'produk';

    // Fallback data jika belum terkoneksi ke DB MySQL
    protected $fallbackProducts = [
        [
            'id_produk' => 1,
            'id_toko' => 1,
            'nama_toko' => 'Budi Tech Store',
            'nama_produk' => 'Laptop Gaming Asus ROG Strix G16',
            'kategori' => 'Laptop',
            'harga' => 22500000.00,
            'stok' => 7,
            'deskripsi' => 'Intel Core i7 Gen 13, RTX 4060, 16GB RAM DDR5, 1TB NVMe SSD, Layar FHD+ 165Hz.',
            'gambar' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=600&auto=format&fit=crop&q=80'
        ],
        [
            'id_produk' => 2,
            'id_toko' => 1,
            'nama_toko' => 'Budi Tech Store',
            'nama_produk' => 'Keychron K2 V2 Wireless Keyboard',
            'kategori' => 'Aksesoris',
            'harga' => 1350000.00,
            'stok' => 15,
            'deskripsi' => 'Keyboard mekanikal compact 75% Bluetooth & USB-C, RGB Backlight, Gateron Brown Switch.',
            'gambar' => 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=600&auto=format&fit=crop&q=80'
        ],
        [
            'id_produk' => 3,
            'id_toko' => 1,
            'nama_toko' => 'Budi Tech Store',
            'nama_produk' => 'Logitech MX Master 3S Wireless Mouse',
            'kategori' => 'Aksesoris',
            'harga' => 1650000.00,
            'stok' => 10,
            'deskripsi' => 'Sensor 8K DPI Darkfield, Quiet Clicks, Ergonomic Hand Rest, MagSpeed Scrolling.',
            'gambar' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=600&auto=format&fit=crop&q=80'
        ],
        [
            'id_produk' => 4,
            'id_toko' => 1,
            'nama_toko' => 'Budi Tech Store',
            'nama_produk' => 'Monitor Xiaomi Curved Gaming 34 Inch',
            'kategori' => 'Monitor',
            'harga' => 4899000.00,
            'stok' => 4,
            'deskripsi' => 'Resolusi WQHD 3440 x 1440, Kelengkungan 1500R, Refresh Rate 144Hz, AMD FreeSync.',
            'gambar' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=600&auto=format&fit=crop&q=80'
        ]
    ];

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Sesuai Sequence Diagram: getProduk(keyword)
     * Mengambil daftar produk baik dengan kata kunci pencarian maupun tanpa kata kunci
     */
    public function getProduk($keyword = null)
    {
        if ($this->db->isConnected()) {
            try {
                if (!empty($keyword)) {
                    $query = "SELECT p.*, t.nama_toko 
                              FROM {$this->table} p 
                              LEFT JOIN toko t ON p.id_toko = t.id_toko 
                              WHERE p.nama_produk LIKE :keyword OR p.kategori LIKE :keyword 
                              ORDER BY p.id_produk DESC";
                    $this->db->query($query);
                    $this->db->bind('keyword', '%' . $keyword . '%');
                } else {
                    $query = "SELECT p.*, t.nama_toko 
                              FROM {$this->table} p 
                              LEFT JOIN toko t ON p.id_toko = t.id_toko 
                              ORDER BY p.id_produk DESC";
                    $this->db->query($query);
                }
                return $this->db->resultSet();
            } catch (Exception $e) {
                // Fallback
            }
        }

        if (!empty($keyword)) {
            $filtered = array_filter($this->fallbackProducts, function ($item) use ($keyword) {
                return stripos($item['nama_produk'], $keyword) !== false || stripos($item['kategori'], $keyword) !== false;
            });
            return array_values($filtered);
        }

        return $this->fallbackProducts;
    }

    /**
     * Sesuai Sequence Diagram: getDetailProduk(id)
     */
    public function getDetailProduk($id)
    {
        if ($this->db->isConnected()) {
            try {
                $query = "SELECT p.*, t.nama_toko 
                          FROM {$this->table} p 
                          LEFT JOIN toko t ON p.id_toko = t.id_toko 
                          WHERE p.id_produk = :id";
                $this->db->query($query);
                $this->db->bind('id', $id);
                $result = $this->db->single();
                if ($result) {
                    return $result;
                }
            } catch (Exception $e) {
                // Fallback
            }
        }

        foreach ($this->fallbackProducts as $item) {
            if ($item['id_produk'] == $id) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Sesuai Sequence Diagram: validasiStok(idProduk, jumlah)
     * Digunakan saat Checkout untuk memastikan ketersediaan barang
     */
    public function validasiStok($idProduk, $jumlah = 1)
    {
        $produk = $this->getDetailProduk($idProduk);
        if (!$produk) {
            return false;
        }
        return ($produk['stok'] >= $jumlah);
    }

    /**
     * Sesuai UC 13: Kelola Produk CRUD Toko - Mengambil produk milik toko tertentu
     */
    public function getProdukByToko($idToko)
    {
        if ($this->db->isConnected()) {
            try {
                $this->db->query("SELECT * FROM {$this->table} WHERE id_toko = :id ORDER BY id_produk DESC");
                $this->db->bind('id', $idToko);
                return $this->db->resultSet();
            } catch (Exception $e) {
                return [];
            }
        }

        return array_values(array_filter($this->fallbackProducts, function ($p) use ($idToko) {
            return $p['id_toko'] == $idToko;
        }));
    }

    /**
     * Sesuai UC 13: Tambah Produk Toko
     */
    public function tambahProduk($idToko, $data)
    {
        if (!$this->db->isConnected()) return true;

        try {
            $this->db->query("INSERT INTO {$this->table} (id_toko, nama_produk, kategori, harga, stok, deskripsi, gambar) 
                              VALUES (:id_toko, :nama, :kategori, :harga, :stok, :deskripsi, :gambar)");
            $this->db->bind('id_toko', $idToko);
            $this->db->bind('nama', $data['nama_produk']);
            $this->db->bind('kategori', $data['kategori']);
            $this->db->bind('harga', $data['harga']);
            $this->db->bind('stok', $data['stok']);
            $this->db->bind('deskripsi', $data['deskripsi'] ?? '');
            $this->db->bind('gambar', $data['gambar'] ?? 'https://images.unsplash.com/photo-1526738549149-8e07eca6c147?w=600&auto=format&fit=crop&q=80');
            return $this->db->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Sesuai UC 13: Edit Produk Toko
     */
    public function updateProduk($idProduk, $idToko, $data)
    {
        if (!$this->db->isConnected()) return true;

        try {
            $this->db->query("UPDATE {$this->table} 
                              SET nama_produk = :nama, kategori = :kategori, harga = :harga, 
                                  stok = :stok, deskripsi = :deskripsi, gambar = :gambar 
                              WHERE id_produk = :id_produk AND id_toko = :id_toko");
            $this->db->bind('nama', $data['nama_produk']);
            $this->db->bind('kategori', $data['kategori']);
            $this->db->bind('harga', $data['harga']);
            $this->db->bind('stok', $data['stok']);
            $this->db->bind('deskripsi', $data['deskripsi'] ?? '');
            $this->db->bind('gambar', $data['gambar'] ?? '');
            $this->db->bind('id_produk', $idProduk);
            $this->db->bind('id_toko', $idToko);
            return $this->db->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Sesuai UC 13: Hapus Produk Toko
     */
    public function hapusProduk($idProduk, $idToko)
    {
        if (!$this->db->isConnected()) return true;

        try {
            $this->db->query("DELETE FROM {$this->table} WHERE id_produk = :id_produk AND id_toko = :id_toko");
            $this->db->bind('id_produk', $idProduk);
            $this->db->bind('id_toko', $idToko);
            return $this->db->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Alias methods untuk backwards compatibility
    public function getAll()
    {
        return $this->getProduk();
    }

    public function getById($id)
    {
        return $this->getDetailProduk($id);
    }
}
