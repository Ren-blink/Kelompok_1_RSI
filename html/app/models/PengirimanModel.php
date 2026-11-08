<?php

class PengirimanModel
{
    private $db;
    private $table = 'pengiriman';

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Sesuai UC 09: Melacak Pesanan
     * Mengambil informasi pengiriman dan resi pesanan
     */
    public function getPengirimanByPesanan($idPesanan)
    {
        if (!$this->db->isConnected()) {
            return [
                'id_pengiriman' => 1,
                'id_pesanan' => $idPesanan,
                'id_toko' => 1,
                'nama_toko' => 'Budi Tech Store',
                'no_resi' => 'JNE-REG-882910394',
                'status_kirim' => 'dalam_perjalanan',
                'tgl_kirim' => date('Y-m-d H:i:s')
            ];
        }

        try {
            $this->db->query("SELECT pg.*, t.nama_toko 
                              FROM {$this->table} pg 
                              JOIN toko t ON pg.id_toko = t.id_toko 
                              WHERE pg.id_pesanan = :id 
                              ORDER BY pg.id_pengiriman DESC LIMIT 1");
            $this->db->bind('id', $idPesanan);
            return $this->db->single();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Sesuai UC 16: Mengajukan / Atur Pengiriman oleh Toko
     * Penjual menginput resi pengiriman kurir
     */
    public function setPengiriman($idPesanan, $idToko, $noResi)
    {
        if (!$this->db->isConnected()) return true;

        try {
            // Cek apakah sudah ada record pengiriman untuk pesanan & toko ini
            $this->db->query("SELECT id_pengiriman FROM {$this->table} WHERE id_pesanan = :id AND id_toko = :id_toko");
            $this->db->bind('id', $idPesanan);
            $this->db->bind('id_toko', $idToko);
            $existing = $this->db->single();

            if ($existing) {
                $this->db->query("UPDATE {$this->table} 
                                  SET no_resi = :resi, status_kirim = 'dalam_perjalanan', tgl_kirim = NOW() 
                                  WHERE id_pengiriman = :id_pengiriman");
                $this->db->bind('resi', $noResi);
                $this->db->bind('id_pengiriman', $existing['id_pengiriman']);
                $this->db->execute();
            } else {
                $this->db->query("INSERT INTO {$this->table} (id_pesanan, id_toko, no_resi, status_kirim, tgl_kirim) 
                                  VALUES (:id, :id_toko, :resi, 'dalam_perjalanan', NOW())");
                $this->db->bind('id', $idPesanan);
                $this->db->bind('id_toko', $idToko);
                $this->db->bind('resi', $noResi);
                $this->db->execute();
            }

            // Update status pesanan jadi 'dikirim'
            $this->db->query("UPDATE pesanan SET status_pesanan = 'dikirim' WHERE id_pesanan = :id");
            $this->db->bind('id', $idPesanan);
            $this->db->execute();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Update status pengiriman
     */
    public function updateStatusKirim($idPengiriman, $statusKirim)
    {
        if (!$this->db->isConnected()) return true;

        $this->db->query("UPDATE {$this->table} SET status_kirim = :status WHERE id_pengiriman = :id");
        $this->db->bind('status', $statusKirim);
        $this->db->bind('id', $idPengiriman);
        return $this->db->execute();
    }
}
