
<?php

require_once __DIR__ . '/../models/LoginModel.php';

class LoginController
{
    private $loginModel;

    public function __construct()
    {
        $this->loginModel = new LoginModel();
    }

    // Menampilkan halaman login
    public function index()
    {
        require __DIR__ . '/../views/login.php';
    }

    // Memproses login
    public function prosesLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            header('Location: index.php?action=login&error=kosong');
            exit;
        }

        $petugas = $this->loginModel->getPetugasByUsername($username);

        if ($petugas && password_verify($password, $petugas['password'])) {
            session_regenerate_id(true);

            $_SESSION['id_petugas'] = $petugas['id_petugas'];
            $_SESSION['nama_petugas'] = $petugas['nama_petugas'];
            $_SESSION['username'] = $petugas['username'];

            header('Location: index.php?action=beranda');
            exit;
        }

        header('Location: index.php?action=login&error=gagal');
        exit;
    }

    // Logout
    public function logout()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: index.php?action=login');
        exit;
    }
}
