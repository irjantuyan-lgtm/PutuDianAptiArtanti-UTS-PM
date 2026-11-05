
<?php

require_once __DIR__ . '/app/config/koneksi.php';

$database = new Database();
$conn = $database->getConnection();

$passwordHash = password_hash('admin123', PASSWORD_DEFAULT);

$sql = "UPDATE tb_petugas
        SET password = :password
        WHERE id_petugas = 1";

$stmt = $conn->prepare($sql);

$berhasil = $stmt->execute([
    ':password' => $passwordHash
]);

if ($berhasil) {
    echo "Password berhasil diamankan.";
} else {
    echo "Gagal mengubah password.";
}
