
<?php require __DIR__ . '/templates/header.php'; ?>

<?php require __DIR__ . '/templates/navbar.php'; ?>

<div class="mb-4">
    <h3 class="fw-bold text-primary">Tambah Peminjaman</h3>
    <p class="text-muted">Masukkan informasi transaksi peminjaman buku.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <form action="index.php?action=simpanPeminjaman" method="POST">

            <!-- Nama Anggota -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Anggota</label>
                <select name="id_anggota" class="form-select" required>
                    <option value="">-- Pilih Anggota --</option>

                    <?php foreach ($anggota as $a): ?>
                        <option value="<?= (int) $a['id_anggota'] ?>">
                            <?= htmlspecialchars($a['nama_anggota']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Judul Buku -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Buku</label>
                <select name="id_buku" class="form-select" required>
                    <option value="">-- Pilih Buku --</option>

                    <?php foreach ($buku as $b): ?>
                        <option value="<?= (int) $b['id_buku'] ?>">
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
                    <option value="">-- Pilih Petugas --</option>

                    <?php foreach ($petugas as $p): ?>
                        <option value="<?= (int) $p['id_petugas'] ?>">
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
                    required
                >
            </div>

            <!-- Jumlah Buku -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Jumlah Buku</label>
                <input
                    type="number"
                    name="jumlah"
                    class="form-control"
                    min="1"
                    required
                >
            </div>

            <!-- Tombol -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Simpan Peminjaman
                </button>

                <a href="index.php?action=peminjaman" class="btn btn-outline-secondary">
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>

<?php require __DIR__ . '/templates/footer.php'; ?>
