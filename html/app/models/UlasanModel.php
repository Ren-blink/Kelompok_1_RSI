<?php

class UlasanModel
{
    private $db;
    private $table = 'ulasan';

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Sesuai UC 10: Memberi Ulasan dan Rating
     */
    public function tambahUlasan($idPesanan, $idProduk, $idUser, $rating, $komentar)
    {
        if (!$this->db->isConnected()) return true;

        try {
            // Validasi apakah user sudah mengulas produk ini di pesanan ini
            if ($this->cekSudahUlas($idPesanan, $idProduk, $idUser)) {
                $this->db->query("UPDATE {$this->table} 
                                  SET rating = :rating, komentar = :komentar, tgl_ulasan = NOW() 
                                  WHERE id_pesanan = :id_pesanan AND id_produk = :id_produk AND id_user = :id_user");
            } else {
                $this->db->query("INSERT INTO {$this->table} (id_pesanan, id_produk, id_user, rating, komentar, tgl_ulasan) 
                                  VALUES (:id_pesanan, :id_produk, :id_user, :rating, :komentar, NOW())");
            }

            $this->db->bind('id_pesanan', $idPesanan);
            $this->db->bind('id_produk', $idProduk);
            $this->db->bind('id_user', $idUser);
            $this->db->bind('rating', max(1, min(5, (int)$rating)));
            $this->db->bind('komentar', $komentar);
            return $this->db->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Cek apakah sudah pernah mengulas
     */
    public function cekSudahUlas($idPesanan, $idProduk, $idUser)
    {
        if (!$this->db->isConnected()) return false;

        $this->db->query("SELECT id_ulasan FROM {$this->table} 
                          WHERE id_pesanan = :id_pesanan AND id_produk = :id_produk AND id_user = :id_user");
        $this->db->bind('id_pesanan', $idPesanan);
        $this->db->bind('id_produk', $idProduk);
        $this->db->bind('id_user', $idUser);
        return (bool)$this->db->single();
    }

    /**
     * Ambil ulasan produk untuk ditampilkan di detail produk
     */
    public function getUlasanByProduk($idProduk)
    {
        if (!$this->db->isConnected()) {
            return [
                [
                    'id_ulasan' => 1,
                    'rating' => 5,
                    'komentar' => 'Barang sangat bagus, pengiriman super cepat!',
                    'nama_user' => 'Siti Nurhaliza',
                    'tgl_ulasan' => date('Y-m-d H:i:s')
                ]
            ];
        }

        try {
            $this->db->query("SELECT u.*, us.nama AS nama_user 
                              FROM {$this->table} u 
                              JOIN user us ON u.id_user = us.id_user 
                              WHERE u.id_produk = :id 
                              ORDER BY u.id_ulasan DESC");
            $this->db->bind('id', $idProduk);
            return $this->db->resultSet();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Ambil ulasan berdasarkan pesanan
     */
    public function getUlasanByPesanan($idPesanan, $idUser)
    {
        if (!$this->db->isConnected()) return [];

        try {
            $this->db->query("SELECT u.*, pr.nama_produk 
                              FROM {$this->table} u 
                              JOIN produk pr ON u.id_produk = pr.id_produk 
                              WHERE u.id_pesanan = :id_pesanan AND u.id_user = :id_user");
            $this->db->bind('id_pesanan', $idPesanan);
            $this->db->bind('id_user', $idUser);
            return $this->db->resultSet();
        } catch (Exception $e) {
            return [];
        }
    }
}
