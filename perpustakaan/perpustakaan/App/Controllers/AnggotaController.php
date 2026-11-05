
<?php

require_once __DIR__ . '/../models/AnggotaModel.php';

class AnggotaController
{
    private $anggotaModel;

    public function __construct()
    {
        $this->anggotaModel = new AnggotaModel();
    }

    // Menampilkan daftar anggota, pencarian, dan pagination
    public function index()
    {
        // Mengambil kata kunci pencarian
        $keyword = trim($_GET['keyword'] ?? '');

        // Maksimal data setiap halaman
        $limit = 10;

        // Mengambil nomor halaman
        $halaman = filter_input(INPUT_GET, 'halaman', FILTER_VALIDATE_INT);

        if (!$halaman || $halaman < 1) {
            $halaman = 1;
        }

        // Menghitung jumlah data anggota
        $totalData = $this->anggotaModel->hitungTotalAnggota($keyword);

        // Menghitung jumlah halaman
        $totalHalaman = max(1, (int) ceil($totalData / $limit));

        // Memastikan halaman tidak melebihi jumlah halaman
        $halaman = min($halaman, $totalHalaman);

        // Menghitung posisi awal data
        $offset = ($halaman - 1) * $limit;

        // Mengambil data anggota sesuai pencarian dan halaman
        $data = $this->anggotaModel->getAnggotaPagination(
            $keyword,
            $limit,
            $offset
        );

        require __DIR__ . '/../views/anggota.php';
    }

    // Menampilkan form tambah anggota
    public function tambah()
    {
        require __DIR__ . '/../views/TambahAnggota.php';
    }

    // Menyimpan anggota baru
    public function simpan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=anggota');
            exit;
        }

        $nama = trim($_POST['nama_anggota'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');
        $telepon = trim($_POST['nomor_telepon'] ?? '');

        if ($telepon !== '' && !ctype_digit($telepon)) {
            echo "Nomor telepon hanya boleh berisi angka.";
            return;
        }

        if ($nama === '') {
            echo "Nama anggota wajib diisi.";
            return;
        }

        if (strlen($telepon) > 15) {
            echo "Nomor telepon maksimal 15 karakter.";
            return;
        }

        $this->anggotaModel->tambahAnggota(
            $nama,
            $alamat,
            $telepon
        );

        header('Location: index.php?action=anggota');
        exit;
    }

    // Menampilkan form edit anggota
    public function edit()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            header('Location: index.php?action=anggota');
            exit;
        }

        $anggota = $this->anggotaModel->getAnggotaById($id);

        if (!$anggota) {
            header('Location: index.php?action=anggota');
            exit;
        }

        require __DIR__ . '/../views/EditAnggota.php';
    }

    // Memperbarui anggota
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=anggota');
            exit;
        }

        $id = filter_input(INPUT_POST, 'id_anggota', FILTER_VALIDATE_INT);
        $nama = trim($_POST['nama_anggota'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');
        $telepon = trim($_POST['nomor_telepon'] ?? '');

        if ($telepon !== '' && !ctype_digit($telepon)) {
            echo "Nomor telepon hanya boleh berisi angka.";
            return;
        }

        if (!$id || $id < 1 || $nama === '') {
            echo "Data anggota tidak valid.";
            return;
        }

        if (strlen($telepon) > 15) {
            echo "Nomor telepon maksimal 15 karakter.";
            return;
        }

        $this->anggotaModel->ubahAnggota(
            $id,
            $nama,
            $alamat,
            $telepon
        );

        header('Location: index.php?action=anggota');
        exit;
    }

    // Menghapus anggota
    public function hapus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=anggota');
            exit;
        }

        $id = filter_input(INPUT_POST, 'id_anggota', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            header('Location: index.php?action=anggota');
            exit;
        }

        $this->anggotaModel->hapusAnggota($id);

        header('Location: index.php?action=anggota');
        exit;
    }
}
