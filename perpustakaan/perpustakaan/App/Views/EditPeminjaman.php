<?php require __DIR__ . '/templates/header.php'; ?>
<?php require __DIR__ . '/templates/navbar.php'; ?>

<div class="mb-4">
    <h3 class="fw-bold text-primary">Edit Data Peminjaman</h3>
    <p class="text-muted">Perbarui informasi transaksi peminjaman buku.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <form action="index.php?action=updatePeminjaman" method="POST">

            <input
                type="hidden"
                name="id_peminjaman"
                value="<?= (int) $peminjaman['id_peminjaman'] ?>"
            >

            <!-- Nama Anggota -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Anggota</label>
                <select name="id_anggota" class="form-select" required>
                    <?php foreach ($anggota as $a): ?>
                        <option
                            value="<?= (int) $a['id_anggota'] ?>"
                            <?= $peminjaman['id_anggota'] == $a['id_anggota'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($a['nama_anggota']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Judul Buku -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Buku</label>
                <select name="id_buku" class="form-select" required>
                    <?php foreach ($buku as $b): ?>
                        <option
                            value="<?= (int) $b['id_buku'] ?>"
                            <?= $peminjaman['id_buku'] == $b['id_buku'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($b['judul_buku']) ?>
                            (Stok: <?= (int) $b['stok'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nama Petugas -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Petugas</label>
                <select name="id_petugas" class="form-select" required>
                    <?php foreach ($petugas as $p): ?>
                        <option
                            value="<?= (int) $p['id_petugas'] ?>"
                            <?= $peminjaman['id_petugas'] == $p['id_petugas'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($p['nama_petugas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tanggal Pinjam -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Pinjam</label>
                <input
                    type="date"
                    name="tanggal_pinjam"
                    class="form-control"
                    value="<?= htmlspecialchars($peminjaman['tanggal_pinjam']) ?>"
                    required
                >
            </div>

            <!-- Tanggal Jatuh Tempo -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Jatuh Tempo</label>
                <input
                    type="date"
                    name="tanggal_jatuh_tempo"
                    class="form-control"
                    value="<?= htmlspecialchars($peminjaman['tanggal_jatuh_tempo']) ?>"
                    required
                >
            </div>

            <!-- Jumlah Buku -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Jumlah Buku</label>
                <input
                    type="number"
                    name="jumlah"
                    class="form-control"
                    min="1"
                    value="<?= (int) $peminjaman['jumlah'] ?>"
                    required
                >
            </div>

            <!-- Status Peminjaman -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Status Peminjaman</label>
                <input
                    type="text"
                    class="form-control"
                    value="<?= htmlspecialchars($peminjaman['status_peminjaman']) ?>"
                    readonly
                >
            </div>

            <!-- Tombol -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

                <a href="index.php?action=peminjaman"
                   class="btn btn-outline-secondary">
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>

<?php require __DIR__ . '/templates/footer.php'; ?>