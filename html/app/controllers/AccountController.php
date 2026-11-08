<?php

class AccountController extends Controller
{
    /**
     * Sesuai Sequence Diagram 1: getProfile()
     * Menampilkan profil pengguna
     */
    public function getProfile()
    {
        $user = $this->requireLogin();
        $userId = $user['id_user'];
        $userModel = $this->model('UserModel');
        $profile = $userModel->getProfile($userId);

        $data = [
            'title' => 'Laman Profile - Kelompok 1 RSI',
            'profile' => $profile,
            'message' => $_SESSION['flash_message'] ?? null
        ];
        unset($_SESSION['flash_message']);

        // Tampilkan Laman Profile
        $this->view('templates/header', $data);
        $this->view('account/profile', $data);
        $this->view('templates/footer');
    }

    /**
     * Sesuai Sequence Diagram 1: setProfile()
     * Mengubah data profil pengguna
     */
    public function setProfile()
    {
        $user = $this->requireLogin();
        $userId = $user['id_user'];
        $userModel = $this->model('UserModel');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updateData = [
                'nama' => trim($_POST['nama'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => trim($_POST['password'] ?? '')
            ];

            $userModel->setProfile($userId, $updateData);
            $_SESSION['user']['nama'] = $updateData['nama'];
            $_SESSION['user']['email'] = $updateData['email'];
            $_SESSION['flash_message'] = 'Profil berhasil diperbarui!';
        }

        // Redirect kembali ke getProfile() untuk tampilkanProfile()
        header('Location: ' . BASE_URL . '/account/getProfile');
        exit;
    }

    /**
     * Sesuai Sequence Diagram 1: logout()
     * Memanggil hapusSession() lalu menampilkan Laman Login
     */
    public function logout()
    {
        $this->hapusSession();
        header('Location: ' . BASE_URL . '/account/login');
        exit;
    }

    /**
     * Sesuai Sequence Diagram 1: hapusSession()
     */
    protected function hapusSession()
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    /**
     * Sesuai Sequence Diagram 1: hapusProfile()
     * Menghapus profil/akun lalu kembali ke Dashboard/Homepage
     */
    public function hapusProfile()
    {
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id_user'];
            $userModel = $this->model('UserModel');
            $userModel->hapusProfile($userId);
            $this->hapusSession();
        }

        // Tampilkan Homepage / Dashboard
        header('Location: ' . BASE_URL);
        exit;
    }

    /**
     * Pelengkap Autentikasi (tampilkanFormLogin)
     */
    public function login()
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $userModel = $this->model('UserModel');
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id_user' => $user['id_user'],
                    'nama' => $user['nama'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];

                $redirect = $_SESSION['redirect_url'] ?? (BASE_URL . '/account/getProfile');
                unset($_SESSION['redirect_url']);
                header('Location: ' . $redirect);
                exit;
            } else {
                $error = 'Email atau password salah!';
            }
        }

        $data = [
            'title' => 'Laman Login - Kelompok 1 RSI',
            'error' => $error
        ];

        $this->view('templates/header', $data);
        $this->view('account/login', $data);
        $this->view('templates/footer');
    }

    public function register()
    {
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = $_POST['role'] ?? 'pembeli';

            $userModel = $this->model('UserModel');
            if ($userModel->findByEmail($email)) {
                $error = 'Email sudah terdaftar!';
            } else {
                $created = $userModel->register($nama, $email, $password, $role);
                if ($created) {
                    $success = 'Pendaftaran berhasil! Silakan login.';
                } else {
                    $error = 'Gagal mendaftar akun.';
                }
            }
        }

        $data = [
            'title' => 'Daftar Akun - Kelompok 1 RSI',
            'error' => $error,
            'success' => $success
        ];

        $this->view('templates/header', $data);
        $this->view('account/register', $data);
        $this->view('templates/footer');
    }
}
