<?php

class UserModel
{
    private $db;
    private $table = 'user';

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Sesuai Sequence Diagram: Mengambil data profil user
     */
    public function getProfile($idUser)
    {
        if (!$this->db->isConnected()) {
            return [
                'id_user' => $idUser,
                'nama' => 'User Demo',
                'email' => 'user@rsi.com',
                'role' => 'pembeli',
                'tgl_daftar' => date('Y-m-d H:i:s')
            ];
        }

        $this->db->query("SELECT id_user, nama, email, role, tgl_daftar FROM {$this->table} WHERE id_user = :id");
        $this->db->bind('id', $idUser);
        return $this->db->single();
    }

    /**
     * Sesuai Sequence Diagram: Memperbarui profil user
     */
    public function setProfile($idUser, $data)
    {
        if (!$this->db->isConnected()) return true;

        $fields = ["nama = :nama", "email = :email"];
        if (!empty($data['password'])) {
            $fields[] = "password = :password";
        }

        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id_user = :id";
        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('id', $idUser);

        if (!empty($data['password'])) {
            $this->db->bind('password', password_hash($data['password'], PASSWORD_BCRYPT));
        }

        return $this->db->execute();
    }

    /**
     * Sesuai Sequence Diagram: Menghapus profil/akun user
     */
    public function hapusProfile($idUser)
    {
        if (!$this->db->isConnected()) return true;

        $this->db->query("DELETE FROM {$this->table} WHERE id_user = :id");
        $this->db->bind('id', $idUser);
        return $this->db->execute();
    }

    public function findByEmail($email)
    {
        if (!$this->db->isConnected()) {
            if ($email === 'admin@rsi.com' || $email === 'siti@pembeli.com') {
                return [
                    'id_user' => ($email === 'admin@rsi.com') ? 1 : 3,
                    'nama' => ($email === 'admin@rsi.com') ? 'Administrator' : 'Siti Nurhaliza',
                    'email' => $email,
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                    'role' => ($email === 'admin@rsi.com') ? 'admin' : 'pembeli'
                ];
            }
            return null;
        }

        $this->db->query("SELECT * FROM {$this->table} WHERE email = :email");
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    public function register($nama, $email, $password, $role = 'pembeli')
    {
        if (!$this->db->isConnected()) return false;

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $this->db->query("INSERT INTO {$this->table} (nama, email, password, role) VALUES (:nama, :email, :password, :role)");
        $this->db->bind('nama', $nama);
        $this->db->bind('email', $email);
        $this->db->bind('password', $hashedPassword);
        $this->db->bind('role', $role);
        return $this->db->execute();
    }

    /**
     * Sesuai UC 21: Kelola Akun Pembeli & Merchant oleh Admin
     */
    public function getAllUsers()
    {
        if (!$this->db->isConnected()) {
            return [
                ['id_user' => 1, 'nama' => 'Administrator', 'email' => 'admin@rsi.com', 'role' => 'admin', 'tgl_daftar' => '2026-11-08 00:00:00'],
                ['id_user' => 2, 'nama' => 'Budi Santoso', 'email' => 'budi@penjual.com', 'role' => 'penjual', 'tgl_daftar' => '2026-11-08 00:00:00'],
                ['id_user' => 3, 'nama' => 'Siti Nurhaliza', 'email' => 'siti@pembeli.com', 'role' => 'pembeli', 'tgl_daftar' => '2026-11-08 00:00:00']
            ];
        }

        try {
            $this->db->query("SELECT id_user, nama, email, role, tgl_daftar FROM {$this->table} ORDER BY id_user ASC");
            return $this->db->resultSet();
        } catch (Exception $e) {
            return [];
        }
    }

    public function updateRole($idUser, $role)
    {
        if (!$this->db->isConnected()) return true;

        try {
            $this->db->query("UPDATE {$this->table} SET role = :role WHERE id_user = :id");
            $this->db->bind('role', $role);
            $this->db->bind('id', $idUser);
            return $this->db->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
