<?php
// Izinkan akses dari semua origin / port (mengatasi blokir CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$db_name = "db_siling";
$username = "root";
$password = "";

try {
    $db = new PDO("mysql:host=" . $host . ";dbname=" . $db_name . ";charset=utf8mb4", $username, $password);
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