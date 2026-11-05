
<?php

require_once __DIR__ . '/../config/koneksi.php';

class AnggotaModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Menampilkan semua data anggota
    public function getAllAnggota()
    {
        $sql = "SELECT * FROM tb_anggota ORDER BY id_anggota DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mengambil data anggota berdasarkan pencarian dan halaman
    public function getAnggotaPagination($keyword, $limit, $offset)
    {
        $sql = "SELECT * FROM tb_anggota
                WHERE nama_anggota LIKE :keyword
                   OR alamat LIKE :keyword
                   OR nomor_telepon LIKE :keyword
                ORDER BY id_anggota DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Menghitung jumlah data anggota berdasarkan pencarian
    public function hitungTotalAnggota($keyword)
    {
        $sql = "SELECT COUNT(*) FROM tb_anggota
                WHERE nama_anggota LIKE :keyword
                   OR alamat LIKE :keyword
                   OR nomor_telepon LIKE :keyword";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':keyword' => '%' . $keyword . '%'
        ]);

        return $stmt->fetchColumn();
    }

    // Menambahkan anggota
    public function tambahAnggota($nama, $alamat, $telepon)
    {
        $sql = "INSERT INTO tb_anggota
                (nama_anggota, alamat, nomor_telepon)
                VALUES (:nama, :alamat, :telepon)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nama' => $nama,
            ':alamat' => $alamat,
            ':telepon' => $telepon
        ]);
    }

    // Mengambil anggota berdasarkan ID
    public function getAnggotaById($id)
    {
        $sql = "SELECT * FROM tb_anggota
                WHERE id_anggota = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mengubah data anggota
    public function ubahAnggota($id, $nama, $alamat, $telepon)
    {
        $sql = "UPDATE tb_anggota
                SET nama_anggota = :nama,
                    alamat = :alamat,
                    nomor_telepon = :telepon
                WHERE id_anggota = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nama' => $nama,
            ':alamat' => $alamat,
            ':telepon' => $telepon,
            ':id' => $id
        ]);
    }

    // Menghapus anggota
    public function hapusAnggota($id)
    {
        $sql = "DELETE FROM tb_anggota
                WHERE id_anggota = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}
