
<?php

require_once __DIR__ . '/../config/koneksi.php';

class LoginModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Mengambil data petugas berdasarkan username
    public function getPetugasByUsername($username)
    {
        $sql = "SELECT * FROM tb_petugas WHERE username = :username";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':username' => $username
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
