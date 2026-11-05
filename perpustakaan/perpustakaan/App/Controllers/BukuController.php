
<?php

require_once __DIR__ . '/../models/BukuModel.php';

class BukuController
{
    private $bukuModel;

    public function __construct()
    {
        $this->bukuModel = new BukuModel();
    }

    // Menampilkan buku, pencarian, dan pagination
    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');

        $limit = 10;

        $halaman = filter_input(INPUT_GET, 'halaman', FILTER_VALIDATE_INT);

        if (!$halaman || $halaman < 1) {
            $halaman = 1;
        }

        $totalData = $this->bukuModel->hitungTotalBuku($keyword);

        $totalHalaman = max(1, (int) ceil($totalData / $limit));

        $halaman = min($halaman, $totalHalaman);

        $offset = ($halaman - 1) * $limit;

        $data = $this->bukuModel->getBukuPagination(
            $keyword,
            $limit,
            $offset
        );

        require __DIR__ . '/../views/buku.php';
    }

    // Menampilkan form tambah buku
    public function tambah()
    {
        require __DIR__ . '/../views/TambahBuku.php';
    }

    // Menyimpan buku
    public function simpan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=buku');
            exit;
        }

        $judul = trim($_POST['judul_buku'] ?? '');
        $penulis = trim($_POST['penulis'] ?? '');
        $penerbit = trim($_POST['penerbit'] ?? '');
        $tahun = $_POST['tahun_terbit'] ?? '';
        $stok = $_POST['stok'] ?? '';

        if (
            $judul === '' ||
            $penulis === '' ||
            !ctype_digit((string) $stok) ||
            (int) $stok < 0 ||
            ($tahun !== '' && (
                !ctype_digit((string) $tahun) ||
                (int) $tahun < 1901 ||
                (int) $tahun > 2155
            ))
        ) {
            echo "Data belum valid. Periksa kembali judul, penulis, tahun terbit, dan stok.";
            return;
        }

        $this->bukuModel->tambahBuku(
            $judul,
            $penulis,
            $penerbit,
            $tahun === '' ? null : (int) $tahun,
            (int) $stok
        );

        header('Location: index.php?action=buku');
        exit;
    }

    // Menampilkan form edit buku
    public function edit()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            header('Location: index.php?action=buku');
            exit;
        }

        $buku = $this->bukuModel->getBukuById($id);

        if (!$buku) {
            header('Location: index.php?action=buku');
            exit;
        }

        require __DIR__ . '/../views/EditBuku.php';
    }

    // Memperbarui buku
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=buku');
            exit;
        }

        $id = filter_input(INPUT_POST, 'id_buku', FILTER_VALIDATE_INT);
        $judul = trim($_POST['judul_buku'] ?? '');
        $penulis = trim($_POST['penulis'] ?? '');
        $penerbit = trim($_POST['penerbit'] ?? '');
        $tahun = $_POST['tahun_terbit'] ?? '';
        $stok = $_POST['stok'] ?? '';

        if (
            !$id || $id < 1 ||
            $judul === '' ||
            $penulis === '' ||
            !ctype_digit((string) $stok) ||
            (int) $stok < 0 ||
            ($tahun !== '' && (
                !ctype_digit((string) $tahun) ||
                (int) $tahun < 1901 ||
                (int) $tahun > 2155
            ))
        ) {
            echo "Data tidak valid. Periksa kembali isian formulir.";
            return;
        }

        $this->bukuModel->ubahBuku(
            $id,
            $judul,
            $penulis,
            $penerbit,
            $tahun === '' ? null : (int) $tahun,
            (int) $stok
        );

        header('Location: index.php?action=buku');
        exit;
    }

    // Menghapus buku
    public function hapus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=buku');
            exit;
        }

        $id = filter_input(INPUT_POST, 'id_buku', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            header('Location: index.php?action=buku');
            exit;
        }

        $this->bukuModel->hapusBuku($id);

        header('Location: index.php?action=buku');
        exit;
    }
}
