
<?php require __DIR__ . '/templates/header.php'; ?>

<?php require __DIR__ . '/templates/navbar.php'; ?>

<div class="mb-4">
    <h3 class="fw-bold text-primary">Edit Data Anggota</h3>
    <p class="text-muted">Perbarui informasi anggota perpustakaan.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <form action="index.php?action=updateAnggota" method="POST">

            <!-- ID Anggota -->
            <input
                type="hidden"
                name="id_anggota"
                value="<?= (int) $anggota['id_anggota'] ?>"
            >

            <!-- Nama Anggota -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Anggota</label>
                <input
                    type="text"
                    name="nama_anggota"
                    class="form-control"
                    maxlength="100"
                    value="<?= htmlspecialchars($anggota['nama_anggota']) ?>"
                    required
                >
            </div>

            <!-- Alamat -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <textarea
                    name="alamat"
                    class="form-control"
                    rows="4"
                    placeholder="Masukkan alamat anggota"
                ><?= htmlspecialchars($anggota['alamat'] ?? '') ?></textarea>
            </div>

            <!-- Nomor Telepon -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Nomor Telepon</label>
                <input
                    type="tel"
                    name="nomor_telepon"
                    class="form-control"
                    maxlength="15"
                    pattern="[0-9]*"
                    inputmode="numeric"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    value="<?= htmlspecialchars($anggota['nomor_telepon'] ?? '') ?>"
                    placeholder="Masukkan nomor telepon"
                >
                <small class="text-muted">
                    Nomor telepon hanya boleh berisi angka.
                </small>
            </div>

            <!-- Tombol -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

                <a href="index.php?action=anggota" class="btn btn-outline-secondary">
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>

<?php require __DIR__ . '/templates/footer.php'; ?>
