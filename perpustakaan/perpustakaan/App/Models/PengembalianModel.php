
<?php
require_once __DIR__ . '/../config/koneksi.php';

class PengembalianModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Menampilkan semua data pengembalian
    public function getAllPengembalian()
    {
        $sql = "SELECT
                    pg.*,
                    p.id_peminjaman,
                    a.nama_anggota,
                    b.judul_buku,
                    p.tanggal_pinjam,
                    p.tanggal_jatuh_tempo
                FROM tb_pengembalian pg
                JOIN tb_peminjaman p
                    ON pg.id_peminjaman = p.id_peminjaman
                JOIN tb_anggota a
                    ON p.id_anggota = a.id_anggota
                JOIN tb_buku b
                    ON p.id_buku = b.id_buku
                ORDER BY pg.id_pengembalian DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mengambil data pengembalian dengan pencarian dan pagination
    public function getPengembalianPagination($keyword, $limit, $offset)
    {
        $sql = "SELECT
                    pg.*,
                    p.id_peminjaman,
                    a.nama_anggota,
                    b.judul_buku,
                    p.tanggal_pinjam,
                    p.tanggal_jatuh_tempo
                FROM tb_pengembalian pg
                JOIN tb_peminjaman p
                    ON pg.id_peminjaman = p.id_peminjaman
                JOIN tb_anggota a
                    ON p.id_anggota = a.id_anggota
                JOIN tb_buku b
                    ON p.id_buku = b.id_buku
                WHERE a.nama_anggota LIKE :nama
                   OR b.judul_buku LIKE :judul
                   OR pg.tanggal_kembali LIKE :tanggal
                ORDER BY pg.id_pengembalian DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);

        $kataKunci = '%' . $keyword . '%';

        $stmt->bindValue(':nama', $kataKunci, PDO::PARAM_STR);
        $stmt->bindValue(':judul', $kataKunci, PDO::PARAM_STR);
        $stmt->bindValue(':tanggal', $kataKunci, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Menghitung jumlah data pengembalian sesuai pencarian
    public function hitungTotalPengembalian($keyword)
    {
        $sql = "SELECT COUNT(*)
                FROM tb_pengembalian pg
                JOIN tb_peminjaman p
                    ON pg.id_peminjaman = p.id_peminjaman
                JOIN tb_anggota a
                    ON p.id_anggota = a.id_anggota
                JOIN tb_buku b
                    ON p.id_buku = b.id_buku
                WHERE a.nama_anggota LIKE :nama
                   OR b.judul_buku LIKE :judul
                   OR pg.tanggal_kembali LIKE :tanggal";

        $stmt = $this->conn->prepare($sql);

        $kataKunci = '%' . $keyword . '%';

        $stmt->execute([
            ':nama' => $kataKunci,
            ':judul' => $kataKunci,
            ':tanggal' => $kataKunci
        ]);

        return (int) $stmt->fetchColumn();
    }

    // Menambahkan data pengembalian dan menambah stok buku
    public function tambahPengembalian($idPeminjaman, $tanggalKembali, $denda)
    {
        try {
            $this->conn->beginTransaction();

            // Mengambil data peminjaman
            $sqlPeminjaman = "SELECT id_buku, jumlah, status_peminjaman
                              FROM tb_peminjaman
                              WHERE id_peminjaman = :id
                              FOR UPDATE";

            $stmtPeminjaman = $this->conn->prepare($sqlPeminjaman);
            $stmtPeminjaman->execute([
                ':id' => $idPeminjaman
            ]);

            $peminjaman = $stmtPeminjaman->fetch(PDO::FETCH_ASSOC);

            // Memastikan peminjaman masih aktif
            if (!$peminjaman ||
                $peminjaman['status_peminjaman'] !== 'Dipinjam') {
                $this->conn->rollBack();
                return false;
            }

            // Memastikan belum pernah dikembalikan
            $sqlCek = "SELECT COUNT(*)
                       FROM tb_pengembalian
                       WHERE id_peminjaman = :id";

            $stmtCek = $this->conn->prepare($sqlCek);
            $stmtCek->execute([
                ':id' => $idPeminjaman
            ]);

            if ($stmtCek->fetchColumn() > 0) {
                $this->conn->rollBack();
                return false;
            }

            // Menambahkan stok buku
            $sqlStok = "UPDATE tb_buku
                        SET stok = stok + :jumlah
                        WHERE id_buku = :buku";

            $stmtStok = $this->conn->prepare($sqlStok);
            $stmtStok->execute([
                ':jumlah' => $peminjaman['jumlah'],
                ':buku' => $peminjaman['id_buku']
            ]);

            // Menyimpan data pengembalian
            $sql = "INSERT INTO tb_pengembalian
                    (id_peminjaman, tanggal_kembali, denda)
                    VALUES (:peminjaman, :tanggal, :denda)";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':peminjaman' => $idPeminjaman,
                ':tanggal' => $tanggalKembali,
                ':denda' => $denda
            ]);

            // Mengubah status peminjaman
            $sqlStatus = "UPDATE tb_peminjaman
                          SET status_peminjaman = 'Dikembalikan'
                          WHERE id_peminjaman = :id";

            $stmtStatus = $this->conn->prepare($sqlStatus);
            $stmtStatus->execute([
                ':id' => $idPeminjaman
            ]);

            $this->conn->commit();

            return true;

        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            return false;
        }
    }

    // Mengambil satu data pengembalian
    public function getPengembalianById($id)
    {
        $sql = "SELECT * FROM tb_pengembalian
                WHERE id_pengembalian = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mengubah data pengembalian
    public function ubahPengembalian($id, $idPeminjaman, $tanggalKembali, $denda)
    {
        try {
            $this->conn->beginTransaction();

            // Mengambil data pengembalian lama
            $sqlLama = "SELECT id_peminjaman
                        FROM tb_pengembalian
                        WHERE id_pengembalian = :id
                        FOR UPDATE";

            $stmtLama = $this->conn->prepare($sqlLama);
            $stmtLama->execute([
                ':id' => $id
            ]);

            $dataLama = $stmtLama->fetch(PDO::FETCH_ASSOC);

            if (!$dataLama) {
                $this->conn->rollBack();
                return false;
            }

            $idPeminjamanLama = $dataLama['id_peminjaman'];

            // Perbarui tanggal dan denda
            // ID peminjaman tidak diubah agar stok tetap aman
            $sql = "UPDATE tb_pengembalian
                    SET tanggal_kembali = :tanggal,
                        denda = :denda
                    WHERE id_pengembalian = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':tanggal' => $tanggalKembali,
                ':denda' => $denda,
                ':id' => $id
            ]);

            $this->conn->commit();

            return true;

        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            return false;
        }
    }

    // Menghapus data pengembalian dan menyesuaikan stok
    public function hapusPengembalian($id)
    {
        try {
            $this->conn->beginTransaction();

            // Mengambil data pengembalian
            $sql = "SELECT
                        pg.id_peminjaman,
                        p.id_buku,
                        p.jumlah
                    FROM tb_pengembalian pg
                    JOIN tb_peminjaman p
                        ON pg.id_peminjaman = p.id_peminjaman
                    WHERE pg.id_pengembalian = :id
                    FOR UPDATE";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id
            ]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                $this->conn->rollBack();
                return false;
            }

            // Mengurangi stok karena buku dianggap belum dikembalikan
            $sqlStok = "UPDATE tb_buku
                        SET stok = stok - :jumlah
                        WHERE id_buku = :buku
                        AND stok >= :jumlah";

            $stmtStok = $this->conn->prepare($sqlStok);
            $stmtStok->execute([
                ':jumlah' => $data['jumlah'],
                ':buku' => $data['id_buku']
            ]);

            // Jika stok tidak cukup, penghapusan dibatalkan
            if ($stmtStok->rowCount() !== 1) {
                $this->conn->rollBack();
                return false;
            }

            // Menghapus data pengembalian
            $sqlHapus = "DELETE FROM tb_pengembalian
                         WHERE id_pengembalian = :id";

            $stmtHapus = $this->conn->prepare($sqlHapus);
            $stmtHapus->execute([
                ':id' => $id
            ]);

            // Mengubah status peminjaman
            $sqlStatus = "UPDATE tb_peminjaman
                          SET status_peminjaman = 'Dipinjam'
                          WHERE id_peminjaman = :id";

            $stmtStatus = $this->conn->prepare($sqlStatus);
            $stmtStatus->execute([
                ':id' => $data['id_peminjaman']
            ]);

            $this->conn->commit();

            return true;

        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            return false;
        }
    }

    // Mengambil peminjaman yang belum dikembalikan
    public function getPeminjaman()
    {
        $sql = "SELECT
                    p.id_peminjaman,
                    a.nama_anggota,
                    b.judul_buku
                FROM tb_peminjaman p
                JOIN tb_anggota a
                    ON p.id_anggota = a.id_anggota
                JOIN tb_buku b
                    ON p.id_buku = b.id_buku
                WHERE p.status_peminjaman = 'Dipinjam'
                AND NOT EXISTS (
                    SELECT 1
                    FROM tb_pengembalian pg
                    WHERE pg.id_peminjaman = p.id_peminjaman
                )
                ORDER BY p.id_peminjaman DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
