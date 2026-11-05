
<?php

// Memulai session
session_start();

// Memanggil controller
require_once __DIR__ . '/app/controllers/LoginController.php';
require_once __DIR__ . '/app/controllers/BukuController.php';
require_once __DIR__ . '/app/controllers/AnggotaController.php';
require_once __DIR__ . '/app/controllers/PeminjamanController.php';
require_once __DIR__ . '/app/controllers/PengembalianController.php';
require_once __DIR__ . '/app/controllers/BerandaController.php';

// Membuat objek controller
$loginController = new LoginController();
$bukuController = new BukuController();
$anggotaController = new AnggotaController();
$peminjamanController = new PeminjamanController();
$pengembalianController = new PengembalianController();
$berandaController = new BerandaController();

// Menentukan halaman
$action = $_GET['action'] ?? 'login';

// ROUTING LOGIN
if ($action === 'login') {

    // Jika sudah login, langsung ke beranda
    if (isset($_SESSION['id_petugas'])) {
        header('Location: index.php?action=beranda');
        exit;
    }

    $loginController->index();

} elseif ($action === 'prosesLogin') {

    $loginController->prosesLogin();

} elseif ($action === 'logout') {

    $loginController->logout();

// PROTEKSI HALAMAN
} else {

    // Jika belum login, arahkan ke login
    if (!isset($_SESSION['id_petugas'])) {
        header('Location: index.php?action=login');
        exit;
    }

    // ROUTING BERANDA
    if ($action === 'beranda') {
        $berandaController->index();

    // ROUTING BUKU
    } elseif ($action === 'buku') {
        $bukuController->index();
    } elseif ($action === 'tambahBuku') {
        $bukuController->tambah();
    } elseif ($action === 'simpanBuku') {
        $bukuController->simpan();
    } elseif ($action === 'editBuku') {
        $bukuController->edit();
    } elseif ($action === 'updateBuku') {
        $bukuController->update();
    } elseif ($action === 'hapusBuku') {
        $bukuController->hapus();

    // ROUTING ANGGOTA
    } elseif ($action === 'anggota') {
        $anggotaController->index();
    } elseif ($action === 'tambahAnggota') {
        $anggotaController->tambah();
    } elseif ($action === 'simpanAnggota') {
        $anggotaController->simpan();
    } elseif ($action === 'editAnggota') {
        $anggotaController->edit();
    } elseif ($action === 'updateAnggota') {
        $anggotaController->update();
    } elseif ($action === 'hapusAnggota') {
        $anggotaController->hapus();

    // ROUTING PEMINJAMAN
    } elseif ($action === 'peminjaman') {
        $peminjamanController->index();
    } elseif ($action === 'tambahPeminjaman') {
        $peminjamanController->tambah();
    } elseif ($action === 'simpanPeminjaman') {
        $peminjamanController->simpan();
    } elseif ($action === 'editPeminjaman') {
        $peminjamanController->edit();
    } elseif ($action === 'updatePeminjaman') {
        $peminjamanController->update();
    } elseif ($action === 'hapusPeminjaman') {
        $peminjamanController->hapus();

    // ROUTING PENGEMBALIAN
    } elseif ($action === 'pengembalian') {
        $pengembalianController->index();
    } elseif ($action === 'tambahPengembalian') {
        $pengembalianController->tambah();
    } elseif ($action === 'simpanPengembalian') {
        $pengembalianController->simpan();
    } elseif ($action === 'editPengembalian') {
        $pengembalianController->edit();
    } elseif ($action === 'updatePengembalian') {
        $pengembalianController->update();
    } elseif ($action === 'hapusPengembalian') {
        $pengembalianController->hapus();

    // HALAMAN TIDAK DITEMUKAN
    } else {
        http_response_code(404);
        echo "Halaman tidak ditemukan.";
    }
}
