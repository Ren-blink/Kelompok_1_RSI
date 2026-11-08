<?php

class PelanggaranModel
{
    private $db;
    private $table = 'pelanggaran';

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Sesuai UC 12: Melaporkan Pelanggaran (oleh Pembeli/Pengguna)
     */
    public function laporkanPelanggaran($idUser, $deskripsi, $idProduk = null, $idToko = null)
    {
        if (!$this->db->isConnected()) return true;

        try {
            $this->db->query("INSERT INTO {$this->table} (id_user, deskripsi, id_produk, id_toko, status, tgl_lapor) 
                              VALUES (:id_user, :deskripsi, :id_produk, :id_toko, 'menunggu_review', NOW())");
            $this->db->bind('id_user', $idUser);
            $this->db->bind('deskripsi', $deskripsi);
            $this->db->bind('id_produk', !empty($idProduk) ? $idProduk : null);
            $this->db->bind('id_toko', !empty($idToko) ? $idToko : null);
            return $this->db->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Sesuai UC 20: Kelola Pelanggaran (oleh Admin)
     * Menampilkan semua laporan pelanggaran yang masuk
     */
    public function getAllPelanggaran()
    {
        if (!$this->db->isConnected()) {
            return [
                [
                    'id_pelanggaran' => 1,
                    'id_user' => 3,
                    'nama_pelapor' => 'Siti Nurhaliza',
                    'email_pelapor' => 'siti@pembeli.com',
                    'id_produk' => 1,
                    'nama_produk' => 'Laptop Gaming Asus ROG Strix G16',
                    'id_toko' => 1,
                    'nama_toko' => 'Budi Tech Store',
                    'deskripsi' => 'Deskripsi barang mencurigakan dan terindikasi barang tiruan/KW.',
                    'status' => 'menunggu_review',
                    'tgl_lapor' => date('Y-m-d H:i:s')
                ]
            ];
        }

        try {
            $this->db->query("SELECT p.*, 
                                     u.nama AS nama_pelapor, u.email AS email_pelapor,
                                     pr.nama_produk,
                                     t.nama_toko 
                              FROM {$this->table} p 
                              JOIN user u ON p.id_user = u.id_user 
                              LEFT JOIN produk pr ON p.id_produk = pr.id_produk 
                              LEFT JOIN toko t ON p.id_toko = t.id_toko 
                              ORDER BY p.id_pelanggaran DESC");
            return $this->db->resultSet();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Sesuai UC 20: Tinjau dan update status laporan pelanggaran
     */
    public function updateStatusPelanggaran($idPelanggaran, $status)
    {
        if (!$this->db->isConnected()) return true;

        try {
            $this->db->query("UPDATE {$this->table} SET status = :status WHERE id_pelanggaran = :id");
            $this->db->bind('status', $status);
            $this->db->bind('id', $idPelanggaran);
            return $this->db->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
