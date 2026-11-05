
<?php require __DIR__ . '/templates/header.php'; ?>

<?php require __DIR__ . '/templates/navbar.php'; ?>

<div class="mb-4">
    <h3 class="fw-bold text-primary">Tambah Pengembalian</h3>
    <p class="text-muted">Masukkan informasi pengembalian buku perpustakaan.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <form action="index.php?action=simpanPengembalian" method="POST">

            <!-- Transaksi Peminjaman -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Transaksi Peminjaman</label>

                <select name="id_peminjaman" class="form-select" required>
                    <option value="">-- Pilih Transaksi Peminjaman --</option>

                    <?php foreach ($peminjaman as $p): ?>
                        <option value="<?= (int) $p['id_peminjaman'] ?>">
                            <?= htmlspecialchars($p['nama_anggota']) ?>
                            - <?= htmlspecialchars($p['judul_buku']) ?>
                            (ID: <?= (int) $p['id_peminjaman'] ?>)
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <!-- Tanggal Kembali -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Kembali</label>
                <input
                    type="date"
                    name="tanggal_kembali"
                    class="form-control"
                    required
                >
            </div>

            <!-- Denda -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Denda (Rp)</label>
                <input
                    type="number"
                    name="denda"
                    class="form-control"
                    min="0"
                    step="0.01"
                    value="0"
                    required
                >
                <small class="text-muted">
                    Jika tidak ada denda, masukkan 0.
                </small>
            </div>

            <!-- Tombol -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Simpan Pengembalian
                </button>

                <a href="index.php?action=pengembalian" class="btn btn-outline-secondary">
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>

<?php require __DIR__ . '/templates/footer.php'; ?>
