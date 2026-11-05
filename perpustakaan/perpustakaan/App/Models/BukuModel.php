
<?php

require_once __DIR__ . '/../config/koneksi.php';

class BukuModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Mengambil semua data buku
    public function getAllBuku()
    {
        $sql = "SELECT * FROM tb_buku ORDER BY id_buku DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mengambil data buku berdasarkan pencarian dan halaman
    public function getBukuPagination($keyword, $limit, $offset)
    {
        $sql = "SELECT * FROM tb_buku
                WHERE judul_buku LIKE :keyword
                   OR penulis LIKE :keyword
                   OR penerbit LIKE :keyword
                ORDER BY id_buku DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Menghitung jumlah buku berdasarkan pencarian
    public function hitungTotalBuku($keyword)
    {
        $sql = "SELECT COUNT(*) FROM tb_buku
                WHERE judul_buku LIKE :keyword
                   OR penulis LIKE :keyword
                   OR penerbit LIKE :keyword";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':keyword' => '%' . $keyword . '%'
        ]);

        return $stmt->fetchColumn();
    }

    // Menambahkan buku
    public function tambahBuku($judul, $penulis, $penerbit, $tahun, $stok)
    {
        $sql = "INSERT INTO tb_buku
                (judul_buku, penulis, penerbit, tahun_terbit, stok)
                VALUES (:judul, :penulis, :penerbit, :tahun, :stok)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':judul' => $judul,
            ':penulis' => $penulis,
            ':penerbit' => $penerbit,
            ':tahun' => $tahun,
            ':stok' => $stok
        ]);
    }

    // Mengambil satu buku berdasarkan ID
    public function getBukuById($id)
    {
        $sql = "SELECT * FROM tb_buku WHERE id_buku = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mengubah data buku
    public function ubahBuku($id, $judul, $penulis, $penerbit, $tahun, $stok)
    {
        $sql = "UPDATE tb_buku
                SET judul_buku = :judul,
                    penulis = :penulis,
                    penerbit = :penerbit,
                    tahun_terbit = :tahun,
                    stok = :stok
                WHERE id_buku = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':judul' => $judul,
            ':penulis' => $penulis,
            ':penerbit' => $penerbit,
            ':tahun' => $tahun,
            ':stok' => $stok,
            ':id' => $id
        ]);
    }

    // Menghapus data buku
    public function hapusBuku($id)
    {
        $sql = "DELETE FROM tb_buku WHERE id_buku = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}
