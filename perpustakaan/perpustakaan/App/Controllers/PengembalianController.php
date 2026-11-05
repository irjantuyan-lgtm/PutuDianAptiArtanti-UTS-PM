
<?php
require_once __DIR__ . '/../models/PengembalianModel.php';

class PengembalianController
{
    private $pengembalianModel;

    public function __construct()
    {
        $this->pengembalianModel = new PengembalianModel();
    }

    // Menampilkan data pengembalian dengan pencarian dan pagination
    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');

        // Maksimal data dalam satu halaman
        $limit = 10;

        // Mengambil nomor halaman
        $halaman = filter_input(INPUT_GET, 'halaman', FILTER_VALIDATE_INT);

        if (!$halaman || $halaman < 1) {
            $halaman = 1;
        }

        // Menghitung jumlah data sesuai pencarian
        $totalData = $this->pengembalianModel->hitungTotalPengembalian($keyword);

        // Menghitung jumlah halaman
        $totalHalaman = max(1, (int) ceil($totalData / $limit));

        // Memastikan halaman tidak melebihi jumlah halaman
        $halaman = min($halaman, $totalHalaman);

        // Menghitung posisi awal data
        $offset = ($halaman - 1) * $limit;

        // Mengambil data sesuai halaman
        $data = $this->pengembalianModel->getPengembalianPagination(
            $keyword,
            $limit,
            $offset
        );

        require __DIR__ . '/../views/pengembalian.php';
    }

    // Menampilkan form tambah pengembalian
    public function tambah()
    {
        $peminjaman = $this->pengembalianModel->getPeminjaman();

        require __DIR__ . '/../views/TambahPengembalian.php';
    }

    // Menyimpan data pengembalian
    public function simpan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=pengembalian');
            exit;
        }

        $idPeminjaman = $_POST['id_peminjaman'] ?? '';
        $tanggalKembali = $_POST['tanggal_kembali'] ?? '';
        $denda = $_POST['denda'] ?? '';

        if (
            !ctype_digit((string) $idPeminjaman) ||
            $tanggalKembali === '' ||
            !is_numeric($denda) ||
            (float) $denda < 0
        ) {
            echo "Data pengembalian tidak valid. Periksa kembali formulir.";
            return;
        }

        $this->pengembalianModel->tambahPengembalian(
            (int) $idPeminjaman,
            $tanggalKembali,
            (float) $denda
        );

        header('Location: index.php?action=pengembalian');
        exit;
    }

    // Menampilkan form edit
    public function edit()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            header('Location: index.php?action=pengembalian');
            exit;
        }

        $pengembalian = $this->pengembalianModel->getPengembalianById($id);

        if (!$pengembalian) {
            header('Location: index.php?action=pengembalian');
            exit;
        }

        $peminjaman = $this->pengembalianModel->getPeminjaman();

        require __DIR__ . '/../views/EditPengembalian.php';
    }

    // Memperbarui data pengembalian
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=pengembalian');
            exit;
        }

        $id = filter_input(INPUT_POST, 'id_pengembalian', FILTER_VALIDATE_INT);
        $idPeminjaman = $_POST['id_peminjaman'] ?? '';
        $tanggalKembali = $_POST['tanggal_kembali'] ?? '';
        $denda = $_POST['denda'] ?? '';

        if (
            !$id || $id < 1 ||
            !ctype_digit((string) $idPeminjaman) ||
            $tanggalKembali === '' ||
            !is_numeric($denda) ||
            (float) $denda < 0
        ) {
            echo "Data pengembalian tidak valid. Periksa kembali formulir.";
            return;
        }

        $this->pengembalianModel->ubahPengembalian(
            $id,
            (int) $idPeminjaman,
            $tanggalKembali,
            (float) $denda
        );

        header('Location: index.php?action=pengembalian');
        exit;
    }

    // Menghapus data pengembalian
    public function hapus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=pengembalian');
            exit;
        }

        $id = filter_input(INPUT_POST, 'id_pengembalian', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            header('Location: index.php?action=pengembalian');
            exit;
        }

        $this->pengembalianModel->hapusPengembalian($id);

        header('Location: index.php?action=pengembalian');
        exit;
    }
}
