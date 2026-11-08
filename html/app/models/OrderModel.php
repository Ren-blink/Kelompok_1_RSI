<?php

class OrderModel
{
    protected $db;
    protected $table = 'pesanan';

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Sesuai Sequence Diagram: setOrder()
     * Menyimpan data order checkout dan mengembalikan status 'AWAITING PAYMENT'
     */
    public function setOrder($userId, $alamat, $items, $totalHarga)
    {
        if (!$this->db->isConnected()) {
            $mockId = rand(100, 999);
            return [
                'status' => 'AWAITING PAYMENT',
                'id_pesanan' => $mockId,
                'total_harga' => $totalHarga,
                'alamat' => $alamat
            ];
        }

        try {
            // 1. Simpan Header Pesanan
            $this->db->query("INSERT INTO {$this->table} (id_user, alamat_pengiriman, status_pesanan, total_harga) 
                              VALUES (:id_user, :alamat, 'menunggu_pembayaran', :total)");
            $this->db->bind('id_user', $userId);
            $this->db->bind('alamat', $alamat);
            $this->db->bind('total', $totalHarga);
            $this->db->execute();

            // Dapatkan ID pesanan terakhir
            $this->db->query("SELECT LAST_INSERT_ID() as last_id");
            $lastRow = $this->db->single();
            $orderId = $lastRow['last_id'] ?? rand(10, 99);

            // 2. Simpan Detail Pesanan & kurangi stok
            foreach ($items as $item) {
                $this->db->query("INSERT INTO detail_pesanan (id_pesanan, id_produk, harga_satuan, jumlah) 
                                  VALUES (:id_pesanan, :id_produk, :harga, :jumlah)");
                $this->db->bind('id_pesanan', $orderId);
                $this->db->bind('id_produk', $item['id_produk']);
                $this->db->bind('harga', $item['harga']);
                $this->db->bind('jumlah', $item['jumlah']);
                $this->db->execute();

                // Kurangi stok produk
                $this->db->query("UPDATE produk SET stok = GREATEST(0, stok - :jumlah) WHERE id_produk = :id_produk");
                $this->db->bind('jumlah', $item['jumlah']);
                $this->db->bind('id_produk', $item['id_produk']);
                $this->db->execute();
            }

            // 3. Buat record awal pembayaran
            $this->db->query("INSERT INTO pembayaran (id_pesanan, metode, status_bayar) VALUES (:id_pesanan, 'Bank Transfer / QRIS', 'pending')");
            $this->db->bind('id_pesanan', $orderId);
            $this->db->execute();

            return [
                'status' => 'AWAITING PAYMENT',
                'id_pesanan' => $orderId,
                'total_harga' => $totalHarga,
                'alamat' => $alamat
            ];
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Ambil detail pesanan by ID lengkap beserta item dan pembayarannya
     */
    public function getPesananById($idPesanan)
    {
        if (!$this->db->isConnected()) {
            return [
                'id_pesanan' => $idPesanan,
                'id_user' => 3,
                'nama_pembeli' => 'Siti Nurhaliza',
                'alamat_pengiriman' => 'Jl. Telekomunikasi No. 1, Bandung',
                'status_pesanan' => 'diproses',
                'total_harga' => 1650000.00,
                'tgl_pesanan' => date('Y-m-d H:i:s'),
                'items' => [
                    [
                        'id_detail' => 1,
                        'id_produk' => 3,
                        'nama_produk' => 'Logitech MX Master 3S Wireless Mouse',
                        'harga_satuan' => 1650000.00,
                        'jumlah' => 1,
                        'subtotal' => 1650000.00,
                        'nama_toko' => 'Budi Tech Store',
                        'id_toko' => 1
                    ]
                ],
                'pembayaran' => [
                    'id_pembayaran' => 1,
                    'metode' => 'Bank Transfer BCA',
                    'status_bayar' => 'berhasil',
                    'tgl_bayar' => date('Y-m-d H:i:s')
                ],
                'pengiriman' => [
                    'id_pengiriman' => 1,
                    'no_resi' => 'JNE-REG-882910394',
                    'status_kirim' => 'dalam_perjalanan',
                    'tgl_kirim' => date('Y-m-d H:i:s')
                ]
            ];
        }

        try {
            $this->db->query("SELECT p.*, u.nama AS nama_pembeli, u.email AS email_pembeli 
                              FROM {$this->table} p 
                              JOIN user u ON p.id_user = u.id_user 
                              WHERE p.id_pesanan = :id");
            $this->db->bind('id', $idPesanan);
            $pesanan = $this->db->single();

            if (!$pesanan) return null;

            // Ambil item produk
            $this->db->query("SELECT dp.*, pr.nama_produk, pr.gambar, t.nama_toko, t.id_toko, (dp.harga_satuan * dp.jumlah) AS subtotal 
                              FROM detail_pesanan dp 
                              JOIN produk pr ON dp.id_produk = pr.id_produk 
                              JOIN toko t ON pr.id_toko = t.id_toko 
                              WHERE dp.id_pesanan = :id");
            $this->db->bind('id', $idPesanan);
            $pesanan['items'] = $this->db->resultSet();

            // Ambil status pembayaran
            $this->db->query("SELECT * FROM pembayaran WHERE id_pesanan = :id ORDER BY id_pembayaran DESC LIMIT 1");
            $this->db->bind('id', $idPesanan);
            $pesanan['pembayaran'] = $this->db->single();

            // Ambil status pengiriman
            $this->db->query("SELECT * FROM pengiriman WHERE id_pesanan = :id ORDER BY id_pengiriman DESC LIMIT 1");
            $this->db->bind('id', $idPesanan);
            $pesanan['pengiriman'] = $this->db->single();

            return $pesanan;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Ambil riwayat pesanan milik seorang user (Pembeli)
     */
    public function getPesananByUser($idUser)
    {
        if (!$this->db->isConnected()) {
            $sample = $this->getPesananById(1);
            return [$sample];
        }

        try {
            $this->db->query("SELECT p.*, 
                                     pb.metode AS metode_bayar, pb.status_bayar,
                                     pg.no_resi, pg.status_kirim,
                                     COUNT(dp.id_detail) AS total_item
                              FROM {$this->table} p 
                              LEFT JOIN pembayaran pb ON p.id_pesanan = pb.id_pesanan 
                              LEFT JOIN pengiriman pg ON p.id_pesanan = pg.id_pesanan 
                              LEFT JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan 
                              WHERE p.id_user = :id_user 
                              GROUP BY p.id_pesanan 
                              ORDER BY p.id_pesanan DESC");
            $this->db->bind('id_user', $idUser);
            return $this->db->resultSet();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Ambil daftar pesanan masuk untuk toko tertentu (Dicky UC 15)
     */
    public function getPesananByToko($idToko)
    {
        if (!$this->db->isConnected()) {
            return [
                [
                    'id_pesanan' => 1,
                    'tgl_pesanan' => date('Y-m-d H:i:s'),
                    'nama_pembeli' => 'Siti Nurhaliza',
                    'alamat_pengiriman' => 'Jl. Telekomunikasi No. 1, Bandung',
                    'status_pesanan' => 'diproses',
                    'total_harga' => 1650000.00,
                    'status_bayar' => 'berhasil',
                    'nama_produk' => 'Logitech MX Master 3S Wireless Mouse',
                    'jumlah' => 1,
                    'no_resi' => 'JNE-REG-882910394'
                ]
            ];
        }

        try {
            $this->db->query("SELECT p.id_pesanan, p.tgl_pesanan, p.status_pesanan, p.alamat_pengiriman,
                                     u.nama AS nama_pembeli,
                                     dp.id_produk, dp.jumlah, dp.harga_satuan, pr.nama_produk,
                                     pb.status_bayar,
                                     pg.no_resi, pg.status_kirim
                              FROM detail_pesanan dp 
                              JOIN produk pr ON dp.id_produk = pr.id_produk 
                              JOIN {$this->table} p ON dp.id_pesanan = p.id_pesanan 
                              JOIN user u ON p.id_user = u.id_user 
                              LEFT JOIN pembayaran pb ON p.id_pesanan = pb.id_pesanan 
                              LEFT JOIN pengiriman pg ON (p.id_pesanan = pg.id_pesanan AND pg.id_toko = pr.id_toko)
                              WHERE pr.id_toko = :id_toko 
                              ORDER BY p.id_pesanan DESC");
            $this->db->bind('id_toko', $idToko);
            return $this->db->resultSet();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Update status pesanan
     */
    public function updateStatusPesanan($idPesanan, $status)
    {
        if (!$this->db->isConnected()) return true;

        $this->db->query("UPDATE {$this->table} SET status_pesanan = :status WHERE id_pesanan = :id");
        $this->db->bind('status', $status);
        $this->db->bind('id', $idPesanan);
        return $this->db->execute();
    }

    /**
     * Sesuai UC 11: Konfirmasi Penerimaan Paket oleh Pembeli
     * Mengubah status pesanan menjadi 'selesai', pengiriman menjadi 'terkirim',
     * dan meneruskan dana ke saldo toko (UC 18).
     */
    public function konfirmasiSelesai($idPesanan, $idUser)
    {
        if (!$this->db->isConnected()) return true;

        try {
            // 1. Verifikasi kepemilikan pesanan
            $this->db->query("SELECT * FROM {$this->table} WHERE id_pesanan = :id AND id_user = :id_user");
            $this->db->bind('id', $idPesanan);
            $this->db->bind('id_user', $idUser);
            $pesanan = $this->db->single();

            if (!$pesanan) return false;

            // 2. Update status pesanan jadi 'selesai'
            $this->db->query("UPDATE {$this->table} SET status_pesanan = 'selesai' WHERE id_pesanan = :id");
            $this->db->bind('id', $idPesanan);
            $this->db->execute();

            // 3. Update pengiriman jadi 'terkirim'
            $this->db->query("UPDATE pengiriman SET status_kirim = 'terkirim' WHERE id_pesanan = :id");
            $this->db->bind('id', $idPesanan);
            $this->db->execute();

            // 4. Masukkan dana ke saldo toko terkait
            $this->db->query("SELECT pr.id_toko, SUM(dp.harga_satuan * dp.jumlah) AS omset 
                              FROM detail_pesanan dp 
                              JOIN produk pr ON dp.id_produk = pr.id_produk 
                              WHERE dp.id_pesanan = :id 
                              GROUP BY pr.id_toko");
            $this->db->bind('id', $idPesanan);
            $tokoOrders = $this->db->resultSet();

            foreach ($tokoOrders as $to) {
                $idToko = $to['id_toko'];
                $omset = $to['omset'];

                // Tambahkan saldo toko
                $this->db->query("UPDATE toko SET saldo = saldo + :omset WHERE id_toko = :id_toko");
                $this->db->bind('omset', $omset);
                $this->db->bind('id_toko', $idToko);
                $this->db->execute();

                // Catat mutasi saldo
                $this->db->query("INSERT INTO saldo_toko (id_toko, jumlah, keterangan, tipe) 
                                  VALUES (:id_toko, :jumlah, :ket, 'masuk')");
                $this->db->bind('id_toko', $idToko);
                $this->db->bind('jumlah', $omset);
                $this->db->bind('ket', "Pencairan pesanan #{$idPesanan} telah diterima pembeli");
                $this->db->execute();
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
