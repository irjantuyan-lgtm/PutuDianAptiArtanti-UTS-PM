
<?php require __DIR__ . '/templates/header.php'; ?>

<?php require __DIR__ . '/templates/navbar.php'; ?>

<!-- Judul Halaman -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Data Peminjaman</h3>
        <p class="text-muted mb-0">
            Kelola transaksi peminjaman buku perpustakaan.
        </p>
    </div>

    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
        <span class="fs-2">📋</span>
    </div>
</div>

<!-- Informasi Jumlah Data -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="text-muted mb-1">Jumlah transaksi peminjaman</p>
                <h3 class="fw-bold text-primary mb-0">
                    <?= (int) $totalData ?>
                </h3>
            </div>

            <a href="index.php?action=tambahPeminjaman"
               class="btn btn-primary">
                + Tambah Peminjaman
            </a>
        </div>
    </div>
</div>

<!-- Form Pencarian -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="index.php" method="GET">
            <input type="hidden" name="action" value="peminjaman">

            <div class="row g-2">
                <div class="col-md-9">
                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Cari nama anggota, judul buku, petugas, atau status..."
                        value="<?= htmlspecialchars($keyword) ?>"
                    >
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit"
                            class="btn btn-primary flex-fill">
                        Cari
                    </button>

                    <a href="index.php?action=peminjaman"
                       class="btn btn-outline-secondary flex-fill">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data Peminjaman -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0">Daftar Transaksi Peminjaman</h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Anggota</th>
                        <th>Judul Buku</th>
                        <th>Nama Petugas</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($data)): ?>

                        <?php $no = $offset + 1; ?>

                        <?php foreach ($data as $peminjaman): ?>
                            <tr>
                                <td><?= $no++ ?></td>

                                <td class="fw-semibold">
                                    <?= htmlspecialchars($peminjaman['nama_anggota']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($peminjaman['judul_buku']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($peminjaman['nama_petugas']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($peminjaman['tanggal_pinjam']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($peminjaman['tanggal_jatuh_tempo']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars((string) $peminjaman['jumlah']) ?>
                                </td>

                                <td>
                                    <?php if ($peminjaman['status_peminjaman'] == 'Dipinjam'): ?>
                                        <span class="badge bg-warning text-dark">
                                            Dipinjam
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success">
                                            Dikembalikan
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <a href="index.php?action=editPeminjaman&id=<?= (int) $peminjaman['id_peminjaman'] ?>"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="index.php?action=hapusPeminjaman"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?');">

                                        <input
                                            type="hidden"
                                            name="id_peminjaman"
                                            value="<?= (int) $peminjaman['id_peminjaman'] ?>"
                                        >

                                        <button type="submit"
                                                class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Belum ada data peminjaman.
                            </td>
                        </tr>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<?php if ($totalHalaman > 1): ?>

    <nav aria-label="Navigasi halaman">
        <ul class="pagination justify-content-center">

            <?php if ($halaman > 1): ?>
                <li class="page-item">
                    <a class="page-link"
                       href="index.php?action=peminjaman&keyword=<?= urlencode($keyword) ?>&halaman=<?= $halaman - 1 ?>">
                        Sebelumnya
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>

                <li class="page-item <?= $i == $halaman ? 'active' : '' ?>">
                    <a class="page-link"
                       href="index.php?action=peminjaman&keyword=<?= urlencode($keyword) ?>&halaman=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>

            <?php endfor; ?>

            <?php if ($halaman < $totalHalaman): ?>
                <li class="page-item">
                    <a class="page-link"
                       href="index.php?action=peminjaman&keyword=<?= urlencode($keyword) ?>&halaman=<?= $halaman + 1 ?>">
                        Selanjutnya
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </nav>

<?php endif; ?>

<?php require __DIR__ . '/templates/footer.php'; ?>
