
<?php require __DIR__ . '/templates/header.php'; ?>
<?php require __DIR__ . '/templates/navbar.php'; ?>

<!-- Sambutan -->
<div class="card border-0 shadow-sm mb-4 text-white"
     style="background: linear-gradient(135deg, #0d6efd, #4f8dfd);">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-2">
            Selamat Datang, <?= htmlspecialchars($namaPetugas) ?>!
        </h3>
        <p class="mb-2">
            Selamat datang di Sistem Informasi Perpustakaan.
        </p>
        <p class="mb-0">
            Kelola data buku, anggota, serta transaksi perpustakaan
            dengan lebih mudah dan teratur.
        </p>
    </div>
</div>

<!-- Statistik -->
<div class="row g-3 mb-4">

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Total Buku</p>
                <h2 class="fw-bold text-primary"><?= $totalBuku ?></h2>
                <a href="index.php?action=buku"
                   class="text-decoration-none small">
                    Kelola data buku →
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Total Anggota</p>
                <h2 class="fw-bold text-success"><?= $totalAnggota ?></h2>
                <a href="index.php?action=anggota"
                   class="text-decoration-none small">
                    Kelola anggota →
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Total Peminjaman</p>
                <h2 class="fw-bold text-warning"><?= $totalPeminjaman ?></h2>
                <a href="index.php?action=peminjaman"
                   class="text-decoration-none small">
                    Lihat peminjaman →
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Total Pengembalian</p>
                <h2 class="fw-bold text-info"><?= $totalPengembalian ?></h2>
                <a href="index.php?action=pengembalian"
                   class="text-decoration-none small">
                    Lihat pengembalian →
                </a>
            </div>
        </div>
    </div>

</div>

<!-- Informasi Stok -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">Stok Buku Tersedia</h5>
                <p class="text-muted mb-0">
                    Total seluruh eksemplar buku yang tersedia di perpustakaan.
                </p>
            </div>

            <h2 class="fw-bold text-primary mb-0">
                <?= $totalStok ?>
            </h2>
        </div>
    </div>
</div>

<!-- Akses Cepat -->
<h5 class="fw-bold mb-3">Akses Cepat</h5>

<div class="row g-3 mb-4">

    <div class="col-md-6 col-lg-3">
        <a href="index.php?action=tambahBuku"
           class="text-decoration-none">
            <div class="card border shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-primary">Tambah Buku</h6>
                    <p class="text-muted small mb-0">
                        Tambahkan koleksi buku baru.
                    </p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-lg-3">
        <a href="index.php?action=tambahAnggota"
           class="text-decoration-none">
            <div class="card border shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-success">Tambah Anggota</h6>
                    <p class="text-muted small mb-0">
                        Daftarkan anggota perpustakaan.
                    </p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-lg-3">
        <a href="index.php?action=tambahPeminjaman"
           class="text-decoration-none">
            <div class="card border shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-warning">Tambah Peminjaman</h6>
                    <p class="text-muted small mb-0">
                        Catat transaksi peminjaman buku.
                    </p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-lg-3">
        <a href="index.php?action=tambahPengembalian"
           class="text-decoration-none">
            <div class="card border shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-info">Tambah Pengembalian</h6>
                    <p class="text-muted small mb-0">
                        Catat pengembalian buku.
                    </p>
                </div>
            </div>
        </a>
    </div>

</div>

<!-- Informasi Sistem -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Tentang Sistem</h5>
        <p class="text-muted mb-2">
            Sistem Informasi Perpustakaan merupakan aplikasi
            pengelolaan data dan transaksi perpustakaan.
        </p>
        <ul class="text-muted mb-0">
            <li>Pengelolaan data buku.</li>
            <li>Pengelolaan data anggota.</li>
            <li>Pencatatan peminjaman buku.</li>
            <li>Pencatatan pengembalian dan denda.</li>
        </ul>
    </div>
</div>

<?php require __DIR__ . '/templates/footer.php'; ?>
