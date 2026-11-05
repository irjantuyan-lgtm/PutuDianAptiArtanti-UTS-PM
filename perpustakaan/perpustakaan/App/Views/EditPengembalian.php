
<?php require __DIR__ . '/templates/header.php'; ?>

<?php require __DIR__ . '/templates/navbar.php'; ?>

<div class="mb-4">
    <h3 class="fw-bold text-primary">Edit Data Pengembalian</h3>
    <p class="text-muted">Perbarui informasi transaksi pengembalian buku.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <form action="index.php?action=updatePengembalian" method="POST">

            <!-- ID Pengembalian -->
            <input
                type="hidden"
                name="id_pengembalian"
                value="<?= (int) $pengembalian['id_pengembalian'] ?>"
            >

            <!-- Transaksi Peminjaman -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Transaksi Peminjaman</label>

                <select name="id_peminjaman" class="form-select" required>

                    <?php foreach ($peminjaman as $p): ?>
                        <option
                            value="<?= (int) $p['id_peminjaman'] ?>"
                            <?= $pengembalian['id_peminjaman'] == $p['id_peminjaman'] ? 'selected' : '' ?>
                        >
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
                    value="<?= htmlspecialchars($pengembalian['tanggal_kembali']) ?>"
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
                    value="<?= htmlspecialchars((string) $pengembalian['denda']) ?>"
                    required
                >
                <small class="text-muted">
                    Jika tidak ada denda, masukkan 0.
                </small>
            </div>

            <!-- Tombol -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

                <a href="index.php?action=pengembalian" class="btn btn-outline-secondary">
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>

<?php require __DIR__ . '/templates/footer.php'; ?>
