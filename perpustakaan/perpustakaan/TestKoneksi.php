
<?php

require_once __DIR__ . '/app/config/koneksi.php';

$database = new Database();
$conn = $database->getConnection();

echo "Koneksi PDO berhasil!";
