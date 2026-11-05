
<?php require __DIR__ . '/templates/header.php'; ?>

<?php require __DIR__ . '/templates/navbar.php'; ?>

<div class="mb-4">
    <h3 class="fw-bold text-primary">Tambah Data Buku</h3>
    <p class="text-muted">Masukkan informasi buku baru ke dalam sistem perpustakaan.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <form action="index.php?action=simpanBuku" method="POST">

            <!-- Judul Buku -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Buku</label>
                <input
                    type="text"
                    name="judul_buku"
                    class="form-control"
                    maxlength="150"
                    placeholder="Masukkan judul buku"
                    required
                >
            </div>

            <!-- Penulis -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Penulis</label>
                <input
                    type="text"
                    name="penulis"
                    class="form-control"
                    maxlength="100"
                    placeholder="Masukkan nama penulis"
                    required
                >
            </div>

            <!-- Penerbit -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Penerbit</label>
                <input
                    type="text"
                    name="penerbit"
                    class="form-control"
                    maxlength="100"
                    placeholder="Masukkan nama penerbit"
                >
            </div>

            <!-- Tahun Terbit -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Tahun Terbit</label>
                <input
                    type="number"
                    name="tahun_terbit"
                    class="form-control"
                    min="1901"
                    max="2155"
                    placeholder="Masukkan tahun terbit"
                >
            </div>

            <!-- Stok Buku -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Stok Buku</label>
                <input
                    type="number"
                    name="stok"
                    class="form-control"
                    min="0"
                    value="0"
                    required
                >
            </div>

            <!-- Tombol -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Simpan Buku
                </button>

                <a href="index.php?action=buku" class="btn btn-outline-secondary">
                    Kembali
                </a>
            </div>

        </form>

    </div>
</div>

<?php require __DIR__ . '/templates/footer.php'; ?>
