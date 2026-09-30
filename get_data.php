<?php
// 1. Izinkan CORS & Atur Header JSON
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Jika permintaan berupa OPTIONS (preflight CORS), langsung hentikan
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 2. Ambil konfigurasi database dari Railway
$host = getenv('MYSQLHOST') ?: 'localhost';
$db_name = getenv('MYSQLDATABASE') ?: 'db_siling';
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$port = getenv('MYSQLPORT') ?: '3306';

try {
    $db = new PDO("mysql:host=" . $host . ";port=" . $port . ";dbname=" . $db_name . ";charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 3. Tangkap data JSON yang dikirim dari JavaScript
    $inputData = file_get_contents("php://input");
    $data = json_decode($inputData);

    if (!empty($data->Nik_Sasaran)) {
        // 4. Siapkan query SQL untuk menyimpan data
        $query = "INSERT INTO pendampingan_krs (
                    timestamp_data, kecamatan, desa, jenis_sasaran, desil, 
                    nama_kader, nama_kk, nama_sasaran, nik_sasaran, no_handphone, 
                    alamat, foto, lokasi_gps, status_risiko, alasan_risiko, status_pendampingan
                  ) VALUES (
                    :timestamp, :kecamatan, :desa, :jenis_sasaran, :desil,
                    :nama_kader, :nama_kk, :nama_sasaran, :nik_sasaran, :no_handphone,
                    :alamat, :foto, :lokasi_gps, :status_risiko, :alasan_risiko, :status_pendampingan
                  )";

        $stmt = $db->prepare($query);
        
        // Eksekusi data (Pastikan nama kolom di tabel SQL Anda sesuai dengan bind parameter ini)
        $stmt->execute([
            ':timestamp' => $data->Timestamp ?? date('Y-m-d'),
            ':kecamatan' => $data->Kecamatan ?? '',
            ':desa' => $data->Desa ?? '',
            ':jenis_sasaran' => $data->Jenis_Sasaran ?? '',
            ':desil' => $data->Desil ?? '1',
            ':nama_kader' => $data->Nama_Kader ?? '',
            ':nama_kk' => $data->Nama_KK ?? '',
            ':nama_sasaran' => $data->Nama_Sasaran ?? '',
            ':nik_sasaran' => $data->Nik_Sasaran ?? '',
            ':no_handphone' => $data->No_Handphone ?? '',
            ':alamat' => $data->Alamat ?? '',
            ':foto' => $data->Foto ?? '',
            ':lokasi_gps' => $data->Lokasi_Gps ?? '',
            ':status_risiko' => $data->Status_Risiko ?? '',
            ':alasan_risiko' => $data->Alasan_Risiko ?? '',
            ':status_pendampingan' => $data->Status_Pendampingan ?? ''
        ]);

        echo json_encode(array("status" => "success", "message" => "Data berhasil disimpan ke database"));
    } else {
        echo json_encode(array("status" => "error", "message" => "NIK Sasaran kosong atau tidak valid"));
    }

} catch(PDOException $exception) {
    echo json_encode(array("status" => "error", "message" => "Database Error: " . $exception->getMessage()));
}
?>