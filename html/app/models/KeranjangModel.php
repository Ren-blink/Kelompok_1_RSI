<?php

class KeranjangModel
{
    protected $db;
    protected $table = 'keranjang';

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Sesuai Sequence Diagram: getIsiKeranjang()
     * Mengambil daftar seluruh item produk di keranjang user
     */
    public function getIsiKeranjang($userId)
    {
        if (!$this->db->isConnected()) {
            return [
                [
                    'id_keranjang' => 1,
                    'id_produk' => 1,
                    'nama_produk' => 'Laptop Gaming Asus ROG Strix G16',
                    'harga' => 22500000.00,
                    'jumlah' => 1,
                    'stok' => 7,
                    'subtotal' => 22500000.00,
                    'gambar' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=600&auto=format&fit=crop&q=80'
                ]
            ];
        }

        $query = "SELECT k.id_keranjang, k.jumlah, k.tgl_tambah, 
                         p.id_produk, p.nama_produk, p.harga, p.gambar, p.stok,
                         (k.jumlah * p.harga) AS subtotal
                  FROM {$this->table} k
                  JOIN produk p ON k.id_produk = p.id_produk
                  WHERE k.id_user = :id_user
                  ORDER BY k.tgl_tambah DESC";
        $this->db->query($query);
        $this->db->bind('id_user', $userId);
        return $this->db->resultSet();
    }

    /**
     * Sesuai Sequence Diagram: setKeranjang()
     * Menambahkan produk baru ke dalam keranjang belanja
     */
    public function setKeranjang($userId, $productId, $qty = 1)
    {
        if (!$this->db->isConnected()) return true;

        $this->db->query("INSERT INTO {$this->table} (id_user, id_produk, jumlah) VALUES (:id_user, :id_produk, :jumlah)");
        $this->db->bind('id_user', $userId);
        $this->db->bind('id_produk', $productId);
        $this->db->bind('jumlah', $qty);
        return $this->db->execute();
    }

    /**
     * Sesuai Sequence Diagram: setJumlahBarang()
     * Menambahkan jumlah barang jika produk SUDAH ADA di dalam keranjang
     */
    public function setJumlahBarang($userId, $productId, $additionalQty = 1)
    {
        if (!$this->db->isConnected()) return true;

        $this->db->query("SELECT id_keranjang, jumlah FROM {$this->table} WHERE id_user = :id_user AND id_produk = :id_produk");
        $this->db->bind('id_user', $userId);
        $this->db->bind('id_produk', $productId);
        $existing = $this->db->single();

        if ($existing) {
            $newQty = $existing['jumlah'] + $additionalQty;
            $this->db->query("UPDATE {$this->table} SET jumlah = :jumlah WHERE id_keranjang = :id");
            $this->db->bind('jumlah', $newQty);
            $this->db->bind('id', $existing['id_keranjang']);
            return $this->db->execute();
        } else {
            return $this->setKeranjang($userId, $productId, $additionalQty);
        }
    }

    /**
     * Sesuai Sequence Diagram: setJumlahProduk()
     * Mengubah/mengganti langsung jumlah produk dari LamanKeranjang
     */
    public function setJumlahProduk($cartId, $userId, $newQty)
    {
        if (!$this->db->isConnected()) return true;

        if ($newQty <= 0) {
            return $this->hapusProduk($cartId, $userId);
        }

        $this->db->query("UPDATE {$this->table} SET jumlah = :jumlah WHERE id_keranjang = :id AND id_user = :id_user");
        $this->db->bind('jumlah', $newQty);
        $this->db->bind('id', $cartId);
        $this->db->bind('id_user', $userId);
        return $this->db->execute();
    }

    /**
     * Sesuai Sequence Diagram: hapusProduk()
     * Menghapus produk tertentu atau mengosongkan keranjang jika cartId null
     */
    public function hapusProduk($cartId = null, $userId = null)
    {
        if (!$this->db->isConnected()) return true;

        if ($cartId !== null) {
            $this->db->query("DELETE FROM {$this->table} WHERE id_keranjang = :id AND id_user = :id_user");
            $this->db->bind('id', $cartId);
            $this->db->bind('id_user', $userId);
        } else {
            $this->db->query("DELETE FROM {$this->table} WHERE id_user = :id_user");
            $this->db->bind('id_user', $userId);
        }
        return $this->db->execute();
    }

    // Cek apakah item sudah ada di keranjang
    public function cekItemAda($userId, $productId)
    {
        if (!$this->db->isConnected()) return false;

        $this->db->query("SELECT id_keranjang FROM {$this->table} WHERE id_user = :id_user AND id_produk = :id_produk");
        $this->db->bind('id_user', $userId);
        $this->db->bind('id_produk', $productId);
        return ($this->db->single() !== null);
    }
}
