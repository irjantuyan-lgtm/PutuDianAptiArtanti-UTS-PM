
<?php
$halamanAktif = $_GET['action'] ?? 'beranda';
?>

<nav class="navbar navbar-expand-lg bg-white shadow-sm rounded-3 mb-4">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold text-primary"
           href="index.php?action=beranda">
            Perpustakaan
        </a>

        <div class="navbar-nav">

            <a class="nav-link px-3 <?= $halamanAktif == 'beranda' ? 'active fw-bold text-primary' : 'text-dark' ?>"
               href="index.php?action=beranda">
                Beranda
            </a>

            <a class="nav-link px-3 <?= $halamanAktif == 'buku' ? 'active fw-bold text-primary' : 'text-dark' ?>"
               href="index.php?action=buku">
                Data Buku
            </a>

            <a class="nav-link px-3 <?= $halamanAktif == 'anggota' ? 'active fw-bold text-primary' : 'text-dark' ?>"
               href="index.php?action=anggota">
                Data Anggota
            </a>

            <a class="nav-link px-3 <?= $halamanAktif == 'peminjaman' ? 'active fw-bold text-primary' : 'text-dark' ?>"
               href="index.php?action=peminjaman">
                Data Peminjaman
            </a>

            <a class="nav-link px-3 <?= $halamanAktif == 'pengembalian' ? 'active fw-bold text-primary' : 'text-dark' ?>"
               href="index.php?action=pengembalian">
                Data Pengembalian
            </a>

            <a class="nav-link px-3 text-danger fw-bold"
               href="index.php?action=logout"
               onclick="return confirm('Apakah kamu yakin ingin logout?')">
                Logout
            </a>

        </div>
    </div>
</nav>
