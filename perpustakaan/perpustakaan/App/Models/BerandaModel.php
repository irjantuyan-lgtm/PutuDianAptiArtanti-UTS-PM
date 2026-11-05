
<?php

require_once __DIR__ . '/../config/koneksi.php';

class BerandaModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Menghitung jumlah seluruh buku
    public function hitungBuku()
    {
        $sql = "SELECT COUNT(*) FROM tb_buku";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    // Menghitung jumlah seluruh anggota
    public function hitungAnggota()
    {
        $sql = "SELECT COUNT(*) FROM tb_anggota";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    // Menghitung jumlah seluruh peminjaman
    public function hitungPeminjaman()
    {
        $sql = "SELECT COUNT(*) FROM tb_peminjaman";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    // Menghitung jumlah seluruh pengembalian
    public function hitungPengembalian()
    {
        $sql = "SELECT COUNT(*) FROM tb_pengembalian";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
    
    // Menghitung total stok buku yang tersedia
    public function hitungStokBuku()
    {
        $sql = "SELECT SUM(stok) FROM tb_buku";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchColumn() ?? 0;
    }

}
