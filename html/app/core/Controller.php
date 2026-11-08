<?php

class Controller
{
    /**
     * Memuat file view dan meneruskan data
     *
     * @param string $view Path relatif view di dalam folder app/views/ (tanpa .php)
     * @param array $data Data yang diteruskan ke view
     */
    public function view($view, $data = [])
    {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (file_exists($viewFile)) {
            extract($data);
            require_once $viewFile;
        } else {
            die("Error: View <strong>{$view}</strong> tidak ditemukan di {$viewFile}");
        }
    }

    /**
     * Memuat file model dan mengembalikan instance objeknya
     *
     * @param string $model Nama class model (contoh: 'ProductModel')
     * @return object Instance dari model
     */
    public function model($model)
    {
        $modelFile = __DIR__ . '/../models/' . $model . '.php';

        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            die("Error: Model <strong>{$model}</strong> tidak ditemukan di {$modelFile}");
        }
    }

    /**
     * Memeriksa apakah pengguna saat ini sudah login
     * @return bool
     */
    public function isLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return !empty($_SESSION['user']);
    }

    /**
     * Mengambil data session user yang sedang aktif
     * @return array|null
     */
    public function getUser()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return $_SESSION['user'] ?? null;
    }

    /**
     * Access Guard: Memastikan user wajib login sebelum mengakses fitur
     * Jika belum login, redirect ke halaman login dengan pesan flash
     *
     * @param string $customMessage Pesan kustom flash error opsional
     * @return array Data pengguna yang login
     */
    public function requireLogin($customMessage = 'Silakan masuk (login) terlebih dahulu untuk mengakses halaman atau fitur tersebut.')
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['user'])) {
            $_SESSION['flash_error'] = $customMessage;
            // Simpan URL yang ingin dituju agar setelah login dapat diarahkan kembali
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'] ?? BASE_URL;
            header('Location: ' . BASE_URL . '/account/login');
            exit;
        }

        return $_SESSION['user'];
    }

    /**
     * Access Guard: Membatasi hak akses berdasarkan role akun (Role-Based Access Control)
     * Contoh: $this->requireRole('admin') atau $this->requireRole(['penjual', 'admin'])
     *
     * @param string|array $allowedRoles Role yang diizinkan
     * @param string $customMessage Pesan jika role tidak diizinkan
     * @return array Data pengguna yang login
     */
    public function requireRole($allowedRoles, $customMessage = null)
    {
        $user = $this->requireLogin();

        if (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }

        if (!in_array($user['role'], $allowedRoles)) {
            if (empty($customMessage)) {
                if (in_array('admin', $allowedRoles) && count($allowedRoles) === 1) {
                    $customMessage = 'Akses Ditolak: Halaman Dashboard Admin hanya dapat diakses oleh Administrator.';
                } elseif (in_array('penjual', $allowedRoles)) {
                    $customMessage = 'Akses Ditolak: Portal Toko hanya dapat diakses oleh akun Penjual (Merchant). Akun Anda saat ini adalah: ' . ucfirst($user['role']) . '.';
                } else {
                    $customMessage = 'Akses Ditolak: Anda tidak memiliki wewenang untuk membuka halaman tersebut.';
                }
            }

            $_SESSION['flash_error'] = $customMessage;
            header('Location: ' . BASE_URL);
            exit;
        }

        return $user;
    }
}
