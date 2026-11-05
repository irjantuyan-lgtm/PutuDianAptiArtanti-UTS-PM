
<?php

require_once __DIR__ . '/../models/BerandaModel.php';

class BerandaController
{
    private $berandaModel;

    public function __construct()
    {
        $this->berandaModel = new BerandaModel();
    }

    public function index()
    {
        $totalBuku = $this->berandaModel->hitungBuku();
        $totalAnggota = $this->berandaModel->hitungAnggota();
        $totalPeminjaman = $this->berandaModel->hitungPeminjaman();
        $totalPengembalian = $this->berandaModel->hitungPengembalian();
        $totalStok = $this->berandaModel->hitungStokBuku();

        $namaPetugas = $_SESSION['nama_petugas'] ?? 'Petugas';

        require __DIR__ . '/../views/beranda.php';
    }
}
