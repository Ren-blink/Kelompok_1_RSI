<?php

class PembayaranModel
{
    private $db;
    private $table = 'pembayaran';

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Sesuai UC 08: Melacak Pembayaran
     * Mengambil status pembayaran berdasarkan ID Pesanan
     */
    public function getPembayaranByPesanan($idPesanan)
    {
        if (!$this->db->isConnected()) {
            return [
                'id_pembayaran' => 1,
                'id_pesanan' => $idPesanan,
                'id_transaksi_gateway' => 'GW-' . date('Ymd') . '-001',
                'metode' => 'Bank Transfer BCA',
                'status_bayar' => 'berhasil',
                'tgl_bayar' => date('Y-m-d H:i:s'),
                'total_harga' => 1650000.00
            ];
        }

        try {
            $this->db->query("SELECT pb.*, p.total_harga, p.status_pesanan 
                              FROM {$this->table} pb 
                              JOIN pesanan p ON pb.id_pesanan = p.id_pesanan 
                              WHERE pb.id_pesanan = :id 
                              ORDER BY pb.id_pembayaran DESC LIMIT 1");
            $this->db->bind('id', $idPesanan);
            return $this->db->single();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Sesuai UC 07: Melakukan Pembayaran
     * Memproses submit pembayaran oleh pembeli
     */
    public function bayarPesanan($idPesanan, $metode, $idTransaksiGateway = null)
    {
        if (empty($idTransaksiGateway)) {
            $idTransaksiGateway = 'GW-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        }

        if (!$this->db->isConnected()) {
            return [
                'status' => true,
                'id_transaksi_gateway' => $idTransaksiGateway,
                'status_bayar' => 'berhasil'
            ];
        }

        try {
            // Cek apakah record pembayaran sudah ada
            $this->db->query("SELECT id_pembayaran FROM {$this->table} WHERE id_pesanan = :id");
            $this->db->bind('id', $idPesanan);
            $existing = $this->db->single();

            if ($existing) {
                $this->db->query("UPDATE {$this->table} 
                                  SET metode = :metode, id_transaksi_gateway = :gw, status_bayar = 'berhasil', tgl_bayar = NOW() 
                                  WHERE id_pesanan = :id");
            } else {
                $this->db->query("INSERT INTO {$this->table} (id_pesanan, id_transaksi_gateway, metode, status_bayar, tgl_bayar) 
                                  VALUES (:id, :gw, :metode, 'berhasil', NOW())");
            }

            $this->db->bind('metode', $metode);
            $this->db->bind('gw', $idTransaksiGateway);
            $this->db->bind('id', $idPesanan);
            $this->db->execute();

            // Update status pesanan jadi 'diproses'
            $this->db->query("UPDATE pesanan SET status_pesanan = 'diproses' WHERE id_pesanan = :id");
            $this->db->bind('id', $idPesanan);
            $this->db->execute();

            return [
                'status' => true,
                'id_transaksi_gateway' => $idTransaksiGateway,
                'status_bayar' => 'berhasil'
            ];
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Sesuai UC 22: Memproses Transaksi Payment Gateway
     * Endpoint simulasi webhook / callback dari payment gateway eksternal
     */
    public function prosesGatewayCallback($idTransaksiGateway, $statusBayar)
    {
        if (!$this->db->isConnected()) return true;

        try {
            $this->db->query("SELECT * FROM {$this->table} WHERE id_transaksi_gateway = :gw");
            $this->db->bind('gw', $idTransaksiGateway);
            $trx = $this->db->single();

            if (!$trx) return false;

            // Update status pembayaran
            $this->db->query("UPDATE {$this->table} SET status_bayar = :status WHERE id_pembayaran = :id");
            $this->db->bind('status', $statusBayar);
            $this->db->bind('id', $trx['id_pembayaran']);
            $this->db->execute();

            // Jika berhasil, ubah status pesanan menjadi diproses
            if ($statusBayar === 'berhasil') {
                $this->db->query("UPDATE pesanan SET status_pesanan = 'diproses' WHERE id_pesanan = :id");
                $this->db->bind('id', $trx['id_pesanan']);
                $this->db->execute();
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Ambil semua transaksi pembayaran untuk pantauan Admin (UC 21 & 22)
     */
    public function getAllPembayaran()
    {
        if (!$this->db->isConnected()) {
            return [
                [
                    'id_pembayaran' => 1,
                    'id_pesanan' => 1,
                    'id_transaksi_gateway' => 'GW-20261108-001',
                    'metode' => 'Bank Transfer BCA',
                    'status_bayar' => 'berhasil',
                    'tgl_bayar' => date('Y-m-d H:i:s'),
                    'nama_pembeli' => 'Siti Nurhaliza',
                    'total_harga' => 1650000.00
                ]
            ];
        }

        try {
            $this->db->query("SELECT pb.*, p.total_harga, u.nama AS nama_pembeli 
                              FROM {$this->table} pb 
                              JOIN pesanan p ON pb.id_pesanan = p.id_pesanan 
                              JOIN user u ON p.id_user = u.id_user 
                              ORDER BY pb.id_pembayaran DESC");
            return $this->db->resultSet();
        } catch (Exception $e) {
            return [];
        }
    }
}
