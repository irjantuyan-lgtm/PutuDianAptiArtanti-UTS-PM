
<?php require __DIR__ . '/templates/header.php'; ?>

<?php require __DIR__ . '/templates/navbar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Data Pengembalian</h3>
        <p class="text-muted mb-0">Kelola data pengembalian buku perpustakaan.</p>
    </div>

    <a href="index.php?action=tambahPengembalian" class="btn btn-primary">
        + Tambah Pengembalian
    </a>
</div>

<!-- Ringkasan Data -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="text-muted mb-1">Jumlah Data Pengembalian</h6>
        <h3 class="fw-bold text-primary mb-0">
            <?= $totalData ?> Data
        </h3>
    </div>
</div>

<!-- Form Pencarian -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="index.php" method="GET" class="row g-2">
            <input type="hidden" name="action" value="pengembalian">

            <div class="col-md-9">
                <input
                    type="text"
                    name="keyword"
                    class="form-control"
                    placeholder="Cari nama anggota, judul buku, atau tanggal kembali..."
                    value="<?= htmlspecialchars($keyword) ?>"
                >
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Cari
                </button>

                <a href="index.php?action=pengembalian" class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Anggota</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tanggal Kembali</th>
                        <th>Denda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($data)): ?>

                        <?php $no = $offset + 1; ?>

                        <?php foreach ($data as $pengembalian): ?>
                            <tr>
                                <td><?= $no++ ?></td>

                                <td>
                                    <?= htmlspecialchars($pengembalian['nama_anggota']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pengembalian['judul_buku']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pengembalian['tanggal_pinjam']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pengembalian['tanggal_jatuh_tempo']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pengembalian['tanggal_kembali']) ?>
                                </td>

                                <td>
                                    Rp <?= number_format((float) $pengembalian['denda'], 0, ',', '.') ?>
                                </td>

                                <td>
                                    <div class="d-flex gap-1">
                                        <a
                                            href="index.php?action=editPengembalian&id=<?= (int) $pengembalian['id_pengembalian'] ?>"
                                            class="btn btn-warning btn-sm"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="index.php?action=hapusPengembalian"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data pengembalian ini?');"
                                        >
                                            <input
                                                type="hidden"
                                                name="id_pengembalian"
                                                value="<?= (int) $pengembalian['id_pengembalian'] ?>"
                                            >

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Belum ada data pengembalian.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalHalaman > 1): ?>

            <nav>
                <ul class="pagination justify-content-center mb-0">

                    <?php if ($halaman > 1): ?>
                        <li class="page-item">
                            <a
                                class="page-link"
                                href="index.php?action=pengembalian&keyword=<?= urlencode($keyword) ?>&halaman=<?= $halaman - 1 ?>"
                            >
                                Sebelumnya
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>

                        <li class="page-item <?= $i == $halaman ? 'active' : '' ?>">
                            <a
                                class="page-link"
                                href="index.php?action=pengembalian&keyword=<?= urlencode($keyword) ?>&halaman=<?= $i ?>"
                            >
                                <?= $i ?>
                            </a>
                        </li>

                    <?php endfor; ?>

                    <?php if ($halaman < $totalHalaman): ?>
                        <li class="page-item">
                            <a
                                class="page-link"
                                href="index.php?action=pengembalian&keyword=<?= urlencode($keyword) ?>&halaman=<?= $halaman + 1 ?>"
                            >
                                Selanjutnya
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </nav>

        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/templates/footer.php'; ?>
