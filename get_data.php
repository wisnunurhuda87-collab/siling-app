<?php
// Izinkan akses dari semua origin / port (mengatasi blokir CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Mengambil konfigurasi dari Environment Variables Railway (dengan fallback ke localhost untuk testing lokal)
$host = getenv('MYSQLHOST') ?: 'localhost';
$db_name = getenv('MYSQLDATABASE') ?: 'db_siling';
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$port = getenv('MYSQLPORT') ?: '3306';

try {
    // Menambahkan parameter port ke PDO agar koneksi Railway berhasil
    $db = new PDO("mysql:host=" . $host . ";port=" . $port . ";dbname=" . $db_name . ";charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $query = "SELECT * FROM pendampingan_krs ORDER BY id DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($result);

} catch(PDOException $exception) {
    echo json_encode(array("error" => true, "message" => $exception->getMessage()));
}
?>