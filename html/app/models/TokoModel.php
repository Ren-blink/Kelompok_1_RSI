<?php

class TokoModel
{
    private $db;
    private $table = 'toko';

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getById($id)
    {
        if (!$this->db->isConnected()) {
            return [
                'id_toko' => $id,
                'id_user' => 2,
                'nama_toko' => 'Budi Tech Store',
                'deskripsi' => 'Pusat periferal dan perangkat keras komputer terlengkap & terpercaya.',
                'saldo' => 5000000.00,
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        $this->db->query("SELECT t.*, u.nama AS nama_pemilik, u.email 
                          FROM {$this->table} t 
                          JOIN user u ON t.id_user = u.id_user 
                          WHERE t.id_toko = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getByUserId($userId)
    {
        if (!$this->db->isConnected()) {
            return [
                'id_toko' => 1,
                'id_user' => $userId,
                'nama_toko' => 'Budi Tech Store',
                'deskripsi' => 'Pusat periferal dan perangkat keras komputer terlengkap & terpercaya.',
                'saldo' => 5000000.00,
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        $this->db->query("SELECT * FROM {$this->table} WHERE id_user = :id_user");
        $this->db->bind('id_user', $userId);
        return $this->db->single();
    }

    /**
     * Sesuai UC 14: Kelola Profil Toko
     * Mengubah nama toko dan deskripsi
     */
    public function updateProfil($idToko, $namaToko, $deskripsi)
    {
        if (!$this->db->isConnected()) return true;

        $this->db->query("UPDATE {$this->table} SET nama_toko = :nama, deskripsi = :deskripsi WHERE id_toko = :id");
        $this->db->bind('nama', $namaToko);
        $this->db->bind('deskripsi', $deskripsi);
        $this->db->bind('id', $idToko);
        return $this->db->execute();
    }

    /**
     * Sesuai UC 18: Melihat Saldo Toko & Mutasi Saldo
     */
    public function getMutasiSaldo($idToko)
    {
        if (!$this->db->isConnected()) {
            return [
                [
                    'id_saldo' => 1,
                    'id_toko' => $idToko,
                    'jumlah' => 5000000.00,
                    'keterangan' => 'Saldo awal pembukaan toko',
                    'tipe' => 'masuk',
                    'tgl' => date('Y-m-d H:i:s')
                ]
            ];
        }

        $this->db->query("SELECT * FROM saldo_toko WHERE id_toko = :id_toko ORDER BY tgl DESC");
        $this->db->bind('id_toko', $idToko);
        return $this->db->resultSet();
    }

    /**
     * Sesuai UC 19: Tarik Saldo Toko
     * Validasi nominal saldo, mengurangi saldo toko, dan mencatat riwayat mutasi 'keluar'
     */
    public function tarikSaldo($idToko, $jumlah, $bank, $noRekening)
    {
        if ($jumlah <= 0) return ['status' => false, 'message' => 'Nominal penarikan harus lebih dari 0.'];

        if (!$this->db->isConnected()) {
            return ['status' => true, 'message' => 'Penarikan saldo sebesar Rp ' . number_format($jumlah, 0, ',', '.') . ' ke ' . $bank . ' (' . $noRekening . ') berhasil diproses.'];
        }

        try {
            // Cek kecukupan saldo
            $toko = $this->getById($idToko);
            if (!$toko || $toko['saldo'] < $jumlah) {
                return ['status' => false, 'message' => 'Saldo tidak mencukupi untuk melakukan penarikan.'];
            }

            // Kurangi saldo toko
            $this->db->query("UPDATE {$this->table} SET saldo = saldo - :jumlah WHERE id_toko = :id");
            $this->db->bind('jumlah', $jumlah);
            $this->db->bind('id', $idToko);
            $this->db->execute();

            // Catat mutasi keluar
            $keterangan = "Penarikan ke Bank {$bank} ({$noRekening})";
            $this->db->query("INSERT INTO saldo_toko (id_toko, jumlah, keterangan, tipe) 
                              VALUES (:id_toko, :jumlah, :ket, 'keluar')");
            $this->db->bind('id_toko', $idToko);
            $this->db->bind('jumlah', $jumlah);
            $this->db->bind('ket', $keterangan);
            $this->db->execute();

            return ['status' => true, 'message' => 'Penarikan saldo sebesar Rp ' . number_format($jumlah, 0, ',', '.') . ' berhasil diproses.'];
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Sesuai UC 17: Melihat Laporan Penjualan Toko
     * Agregasi transaksi penjualan toko dengan filter tanggal opsional
     */
    public function getLaporanPenjualan($idToko, $startDate = null, $endDate = null)
    {
        if (!$this->db->isConnected()) {
            return [
                'total_omset' => 1650000.00,
                'total_transaksi' => 1,
                'total_barang_terjual' => 1,
                'rincian' => [
                    [
                        'id_pesanan' => 1,
                        'tgl_pesanan' => date('Y-m-d H:i:s'),
                        'nama_produk' => 'Logitech MX Master 3S Wireless Mouse',
                        'jumlah' => 1,
                        'harga_satuan' => 1650000.00,
                        'subtotal' => 1650000.00,
                        'status_pesanan' => 'selesai'
                    ]
                ]
            ];
        }

        try {
            $sql = "SELECT dp.*, pr.nama_produk, p.tgl_pesanan, p.status_pesanan,
                           (dp.harga_satuan * dp.jumlah) AS subtotal 
                    FROM detail_pesanan dp 
                    JOIN produk pr ON dp.id_produk = pr.id_produk 
                    JOIN pesanan p ON dp.id_pesanan = p.id_pesanan 
                    WHERE pr.id_toko = :id_toko AND p.status_pesanan IN ('diproses', 'dikirim', 'selesai')";

            if (!empty($startDate) && !empty($endDate)) {
                $sql .= " AND DATE(p.tgl_pesanan) BETWEEN :start AND :end";
            }

            $sql .= " ORDER BY p.id_pesanan DESC";

            $this->db->query($sql);
            $this->db->bind('id_toko', $idToko);
            if (!empty($startDate) && !empty($endDate)) {
                $this->db->bind('start', $startDate);
                $this->db->bind('end', $endDate);
            }

            $rincian = $this->db->resultSet();

            $totalOmset = 0;
            $totalBarang = 0;
            $pesananUnik = [];

            foreach ($rincian as $r) {
                $totalOmset += $r['subtotal'];
                $totalBarang += $r['jumlah'];
                $pesananUnik[$r['id_pesanan']] = true;
            }

            return [
                'total_omset' => $totalOmset,
                'total_transaksi' => count($pesananUnik),
                'total_barang_terjual' => $totalBarang,
                'rincian' => $rincian
            ];
        } catch (Exception $e) {
            return [
                'total_omset' => 0,
                'total_transaksi' => 0,
                'total_barang_terjual' => 0,
                'rincian' => []
            ];
        }
    }
}
