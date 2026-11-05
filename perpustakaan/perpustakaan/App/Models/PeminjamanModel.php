
<?php

require_once __DIR__ . '/../config/koneksi.php';

class PeminjamanModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Mengambil semua data peminjaman
    public function getAllPeminjaman()
    {
        $sql = "SELECT
                    p.*,
                    a.nama_anggota,
                    b.judul_buku,
                    pt.nama_petugas
                FROM tb_peminjaman p
                JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                JOIN tb_buku b ON p.id_buku = b.id_buku
                JOIN tb_petugas pt ON p.id_petugas = pt.id_petugas
                ORDER BY p.id_peminjaman DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mengambil data peminjaman dengan pagination dan pencarian
    public function getPeminjamanPagination($keyword, $limit, $offset)
    {
        $sql = "SELECT
                    p.*,
                    a.nama_anggota,
                    b.judul_buku,
                    pt.nama_petugas
                FROM tb_peminjaman p
                JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                JOIN tb_buku b ON p.id_buku = b.id_buku
                JOIN tb_petugas pt ON p.id_petugas = pt.id_petugas
                WHERE a.nama_anggota LIKE :nama
                    OR b.judul_buku LIKE :judul
                    OR p.status_peminjaman LIKE :status
                ORDER BY p.id_peminjaman DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        $cari = '%' . $keyword . '%';

        $stmt->bindValue(':nama', $cari, PDO::PARAM_STR);
        $stmt->bindValue(':judul', $cari, PDO::PARAM_STR);
        $stmt->bindValue(':status', $cari, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Menghitung total data peminjaman
    public function hitungTotalPeminjaman($keyword)
    {
        $sql = "SELECT COUNT(*)
                FROM tb_peminjaman p
                JOIN tb_anggota a ON p.id_anggota = a.id_anggota
                JOIN tb_buku b ON p.id_buku = b.id_buku
                WHERE a.nama_anggota LIKE :nama
                    OR b.judul_buku LIKE :judul
                    OR p.status_peminjaman LIKE :status";

        $stmt = $this->db->prepare($sql);

        $cari = '%' . $keyword . '%';

        $stmt->bindValue(':nama', $cari, PDO::PARAM_STR);
        $stmt->bindValue(':judul', $cari, PDO::PARAM_STR);
        $stmt->bindValue(':status', $cari, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    // Menambahkan peminjaman baru
    public function tambahPeminjaman(
        $idAnggota,
        $idBuku,
        $idPetugas,
        $tanggalPinjam,
        $tanggalJatuhTempo,
        $jumlah
    ) {
        try {
            $this->db->beginTransaction();

            // Mengunci data buku
            $stmtBuku = $this->db->prepare(
                "SELECT stok FROM tb_buku WHERE id_buku = ? FOR UPDATE"
            );

            $stmtBuku->execute([$idBuku]);

            $buku = $stmtBuku->fetch(PDO::FETCH_ASSOC);

            if (!$buku || $buku['stok'] < $jumlah) {
                $this->db->rollBack();
                return false;
            }

            // Menambahkan data peminjaman
            $sql = "INSERT INTO tb_peminjaman
                    (
                        id_anggota,
                        id_buku,
                        id_petugas,
                        tanggal_pinjam,
                        tanggal_jatuh_tempo,
                        jumlah,
                        status_peminjaman
                    )
                    VALUES (?, ?, ?, ?, ?, ?, 'Dipinjam')";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                $idAnggota,
                $idBuku,
                $idPetugas,
                $tanggalPinjam,
                $tanggalJatuhTempo,
                $jumlah
            ]);

            // Mengurangi stok buku
            $updateStok = $this->db->prepare(
                "UPDATE tb_buku SET stok = stok - ? WHERE id_buku = ?"
            );

            $updateStok->execute([$jumlah, $idBuku]);

            $this->db->commit();

            return true;

        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            return false;
        }
    }

    // Mengambil data peminjaman berdasarkan ID
    public function getPeminjamanById($id)
    {
        $sql = "SELECT * FROM tb_peminjaman
                WHERE id_peminjaman = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mengubah data peminjaman
    public function ubahPeminjaman(
        $id,
        $idAnggota,
        $idBuku,
        $idPetugas,
        $tanggalPinjam,
        $tanggalJatuhTempo,
        $jumlah,
        $status
    ) {
        try {
            $this->db->beginTransaction();

            // Mengambil dan mengunci data lama
            $stmtLama = $this->db->prepare(
                "SELECT * FROM tb_peminjaman
                 WHERE id_peminjaman = ?
                 FOR UPDATE"
            );

            $stmtLama->execute([$id]);

            $dataLama = $stmtLama->fetch(PDO::FETCH_ASSOC);

            if (!$dataLama) {
                $this->db->rollBack();
                return false;
            }

            // Memeriksa data pengembalian
            $cekPengembalian = $this->db->prepare(
                "SELECT COUNT(*) FROM tb_pengembalian
                 WHERE id_peminjaman = ?"
            );

            $cekPengembalian->execute([$id]);

            if ($cekPengembalian->fetchColumn() > 0) {
                $this->db->rollBack();
                return false;
            }

            $idBukuLama = (int) $dataLama['id_buku'];
            $jumlahLama = (int) $dataLama['jumlah'];
            $statusLama = $dataLama['status_peminjaman'];

            // Mengunci data buku berdasarkan urutan ID
            $daftarIdBuku = array_unique([
                $idBukuLama,
                (int) $idBuku
            ]);

            sort($daftarIdBuku);

            $stmtKunciBuku = $this->db->prepare(
                "SELECT id_buku, stok
                 FROM tb_buku
                 WHERE id_buku = ?
                 FOR UPDATE"
            );

            $dataBuku = [];

            foreach ($daftarIdBuku as $idBukuKunci) {
                $stmtKunciBuku->execute([$idBukuKunci]);

                $buku = $stmtKunciBuku->fetch(PDO::FETCH_ASSOC);

                if (!$buku) {
                    $this->db->rollBack();
                    return false;
                }

                $dataBuku[$idBukuKunci] = (int) $buku['stok'];
            }

            // Mengembalikan stok buku lama jika masih dipinjam
            if ($statusLama === 'Dipinjam') {
                $stmtStokLama = $this->db->prepare(
                    "UPDATE tb_buku
                     SET stok = stok + ?
                     WHERE id_buku = ?"
                );

                $stmtStokLama->execute([
                    $jumlahLama,
                    $idBukuLama
                ]);

                $dataBuku[$idBukuLama] += $jumlahLama;
            }

            // Mengurangi stok buku baru jika masih dipinjam
            if ($status === 'Dipinjam') {
                if ($dataBuku[$idBuku] < $jumlah) {
                    $this->db->rollBack();
                    return false;
                }

                $stmtStokBaru = $this->db->prepare(
                    "UPDATE tb_buku
                     SET stok = stok - ?
                     WHERE id_buku = ?"
                );

                $stmtStokBaru->execute([
                    $jumlah,
                    $idBuku
                ]);
            }

            // Memperbarui data peminjaman
            $sql = "UPDATE tb_peminjaman
                    SET
                        id_anggota = ?,
                        id_buku = ?,
                        id_petugas = ?,
                        tanggal_pinjam = ?,
                        tanggal_jatuh_tempo = ?,
                        jumlah = ?,
                        status_peminjaman = ?
                    WHERE id_peminjaman = ?";

            $stmtUpdate = $this->db->prepare($sql);

            $stmtUpdate->execute([
                $idAnggota,
                $idBuku,
                $idPetugas,
                $tanggalPinjam,
                $tanggalJatuhTempo,
                $jumlah,
                $status,
                $id
            ]);

            $this->db->commit();

            return true;

        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            return false;
        }
    }

    // Menghapus data peminjaman
    public function hapusPeminjaman($id)
    {
        try {
            $this->db->beginTransaction();

            // Mengambil data peminjaman
            $stmt = $this->db->prepare(
                "SELECT * FROM tb_peminjaman
                 WHERE id_peminjaman = ?
                 FOR UPDATE"
            );

            $stmt->execute([$id]);

            $peminjaman = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$peminjaman) {
                $this->db->rollBack();
                return false;
            }

            // Memeriksa apakah sudah ada data pengembalian
            $cekPengembalian = $this->db->prepare(
                "SELECT COUNT(*) FROM tb_pengembalian
                 WHERE id_peminjaman = ?"
            );

            $cekPengembalian->execute([$id]);

            if ($cekPengembalian->fetchColumn() > 0) {
                $this->db->rollBack();
                return false;
            }

            // Mengembalikan stok jika buku masih dipinjam
            if ($peminjaman['status_peminjaman'] === 'Dipinjam') {
                $stmtStok = $this->db->prepare(
                    "UPDATE tb_buku
                     SET stok = stok + ?
                     WHERE id_buku = ?"
                );

                $stmtStok->execute([
                    $peminjaman['jumlah'],
                    $peminjaman['id_buku']
                ]);
            }

            // Menghapus data peminjaman
            $stmtHapus = $this->db->prepare(
                "DELETE FROM tb_peminjaman
                 WHERE id_peminjaman = ?"
            );

            $stmtHapus->execute([$id]);

            $this->db->commit();

            return true;

        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            return false;
        }
    }

    // Mengambil data anggota
    public function getAnggota()
    {
        $sql = "SELECT * FROM tb_anggota
                ORDER BY nama_anggota ASC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mengambil data buku
    public function getBuku()
    {
        $sql = "SELECT * FROM tb_buku
                ORDER BY judul_buku ASC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mengambil data petugas
    public function getPetugas()
    {
        $sql = "SELECT * FROM tb_petugas
                ORDER BY nama_petugas ASC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
