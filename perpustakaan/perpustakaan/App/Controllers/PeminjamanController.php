<?php
require_once __DIR__ . '/../models/PeminjamanModel.php';

class PeminjamanController
{
    private $peminjamanModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
    }

    // Menampilkan data peminjaman
    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');
        $limit = 10;

        $halaman = filter_input(INPUT_GET, 'halaman', FILTER_VALIDATE_INT);

        if (!$halaman || $halaman < 1) {
            $halaman = 1;
        }

        $totalData = $this->peminjamanModel->hitungTotalPeminjaman($keyword);
        $totalHalaman = max(1, (int) ceil($totalData / $limit));
        $halaman = min($halaman, $totalHalaman);
        $offset = ($halaman - 1) * $limit;

        $data = $this->peminjamanModel->getPeminjamanPagination(
            $keyword,
            $limit,
            $offset
        );

        require __DIR__ . '/../views/peminjaman.php';
    }

    // Menampilkan form tambah
    public function tambah()
    {
        $anggota = $this->peminjamanModel->getAnggota();
        $buku = $this->peminjamanModel->getBuku();
        $petugas = $this->peminjamanModel->getPetugas();

        require __DIR__ . '/../views/TambahPeminjaman.php';
    }

    // Menyimpan peminjaman baru
    public function simpan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=peminjaman');
            exit;
        }

        $idAnggota = $_POST['id_anggota'] ?? '';
        $idBuku = $_POST['id_buku'] ?? '';
        $idPetugas = $_POST['id_petugas'] ?? '';
        $tanggalPinjam = $_POST['tanggal_pinjam'] ?? '';
        $tanggalJatuhTempo = $_POST['tanggal_jatuh_tempo'] ?? '';
        $jumlah = $_POST['jumlah'] ?? '';

        if (
            !ctype_digit((string) $idAnggota) ||
            !ctype_digit((string) $idBuku) ||
            !ctype_digit((string) $idPetugas) ||
            !ctype_digit((string) $jumlah) ||
            (int) $jumlah < 1 ||
            $tanggalPinjam === '' ||
            $tanggalJatuhTempo === '' ||
            $tanggalJatuhTempo < $tanggalPinjam
        ) {
            echo "Data peminjaman tidak valid. Periksa kembali formulir.";
            return;
        }

        $berhasil = $this->peminjamanModel->tambahPeminjaman(
            (int) $idAnggota,
            (int) $idBuku,
            (int) $idPetugas,
            $tanggalPinjam,
            $tanggalJatuhTempo,
            (int) $jumlah
        );

        if (!$berhasil) {
            echo "Peminjaman gagal! Periksa stok buku atau data database.";
            return;
        }

        header('Location: index.php?action=peminjaman');
        exit;
    }

    // Menampilkan form edit
    public function edit()
    {
        $id = filter_input(INPUT_GET, 'id_peminjaman', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            header('Location: index.php?action=peminjaman');
            exit;
        }

        $peminjaman = $this->peminjamanModel->getPeminjamanById($id);

        if (!$peminjaman) {
            echo "Data peminjaman tidak ditemukan.";
            return;
        }

        $anggota = $this->peminjamanModel->getAnggota();
        $buku = $this->peminjamanModel->getBuku();
        $petugas = $this->peminjamanModel->getPetugas();

        require __DIR__ . '/../views/EditPeminjaman.php';
    }

    // Memperbarui data peminjaman
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=peminjaman');
            exit;
        }

        $id = $_POST['id_peminjaman'] ?? '';
        $idAnggota = $_POST['id_anggota'] ?? '';
        $idBuku = $_POST['id_buku'] ?? '';
        $idPetugas = $_POST['id_petugas'] ?? '';
        $tanggalPinjam = $_POST['tanggal_pinjam'] ?? '';
        $tanggalJatuhTempo = $_POST['tanggal_jatuh_tempo'] ?? '';
        $jumlah = $_POST['jumlah'] ?? '';
        $status = $_POST['status_peminjaman'] ?? '';

        if (
            !ctype_digit((string) $id) ||
            !ctype_digit((string) $idAnggota) ||
            !ctype_digit((string) $idBuku) ||
            !ctype_digit((string) $idPetugas) ||
            !ctype_digit((string) $jumlah) ||
            (int) $jumlah < 1 ||
            $tanggalPinjam === '' ||
            $tanggalJatuhTempo === '' ||
            $tanggalJatuhTempo < $tanggalPinjam ||
            !in_array($status, ['Dipinjam', 'Dikembalikan'], true)
        ) {
            echo "Data peminjaman tidak valid. Periksa kembali formulir.";
            return;
        }

        $berhasil = $this->peminjamanModel->ubahPeminjaman(
            (int) $id,
            (int) $idAnggota,
            (int) $idBuku,
            (int) $idPetugas,
            $tanggalPinjam,
            $tanggalJatuhTempo,
            (int) $jumlah,
            $status
        );

        if (!$berhasil) {
            echo "Gagal mengubah data peminjaman. Periksa stok buku atau data pengembalian.";
            return;
        }

        header('Location: index.php?action=peminjaman');
        exit;
    }

    // Menghapus peminjaman
    public function hapus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=peminjaman');
            exit;
        }

        $id = filter_input(INPUT_POST, 'id_peminjaman', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            header('Location: index.php?action=peminjaman');
            exit;
        }

        $this->peminjamanModel->hapusPeminjaman($id);

        header('Location: index.php?action=peminjaman');
        exit;
    }
}