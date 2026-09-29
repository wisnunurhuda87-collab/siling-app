<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$host = "localhost";
$db_name = "db_siling";
$username = "root";
$password = "";

try {
    $db = new PDO("mysql:host=" . $host . ";dbname=" . $db_name . ";charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $exception) {
    echo json_encode(array("result" => "error", "message" => "Koneksi database gagal: " . $exception->getMessage()));
    exit();
}

$inputData = file_get_contents("php://input");
$data = json_decode($inputData);

$nik = $data->Nik_Sasaran ?? $data->nik_sasaran ?? "";

if (!empty($nik)) {
    // 1. Data Wilayah & Umum
    $kecamatan = $data->Kecamatan ?? $data->kecamatan ?? "-";
    $desa = $data->Desa ?? $data->desa ?? "-";
    $jenis_sasaran = $data->Jenis_Sasaran ?? $data->jenis_sasaran ?? "-";
    $desil = $data->Desil ?? $data->desil ?? 1;
    
    // 2. Survei Kesejahteraan
    $pendidikan_kk = $data->Pendidikan_KK ?? $data->pendidikan_kk ?? "-";
    $pekerjaan_kk = $data->Pekerjaan_KK ?? $data->pekerjaan_kk ?? "-";
    $kepemilikan_rumah = $data->Kepemilikan_Rumah ?? $data->kepemilikan_rumah ?? "-";
    $luas_lantai = $data->Luas_Lantai ?? $data->luas_lantai ?? "-";
    $jenis_dinding = $data->Jenis_Dinding ?? $data->jenis_dinding ?? "-";
    $jenis_atap = $data->Jenis_Atap ?? $data->jenis_atap ?? "-";
    $jenis_lantai = $data->Jenis_Lantai ?? $data->jenis_lantai ?? "-";
    $sumber_listrik = $data->Sumber_Listrik ?? $data->sumber_listrik ?? "-";
    $bahan_bakar = $data->Bahan_Bakar ?? $data->bahan_bakar ?? "-";
    $aset_kendaraan = $data->Aset_Kendaraan ?? $data->aset_kendaraan ?? "-";
    $tabungan = $data->Tabungan ?? $data->tabungan ?? "-";
    $emas_perhiasan = $data->Emas_Perhiasan ?? $data->emas_perhiasan ?? "-";

    // 3. Identitas & Kontak
    $no_tim_tpk = $data->No_TPK ?? $data->no_tim_tpk ?? "-";
    $nama_kader_tpk = $data->Nama_Kader ?? $data->nama_kader_tpk ?? "-";
    $nama_kk = $data->Nama_KK ?? $data->nama_kk ?? "-";
    $nama_sasaran = $data->Nama_Sasaran ?? $data->nama_sasaran ?? "-";
    $no_hp = $data->No_Handphone ?? $data->no_hp ?? "-";
    $alamat = $data->Alamat ?? $data->alamat ?? "-";

    // 4. Data Spesifik Kategori & Pengukuran Fisik
    $tempat_pemeriksaan_catin = $data->Tempat_Pemeriksaan_Catin ?? "-";
    $catin_terpapar_rokok = $data->Catin_Terpapar_Rokok ?? "-";
    $catin_pria_merokok = $data->Catin_Pria_Merokok ?? "-";
    $catin_mendapat_ttd = $data->Catin_Mendapat_Ttd ?? "-";
    $bumil_anak_ke = $data->Bumil_Anak_Ke ?? "-";
    $bumil_jaminan_kesehatan = $data->Bumil_Jaminan_Kesehatan ?? "-";
    $bupas_tgl_melahirkan = $data->Bupas_Tgl_Melahirkan ?? "-";
    $bupas_tempat_persalinan = $data->Bupas_Tempat_Persalinan ?? "-";
    $bupas_proses_persalinan = $data->Bupas_Proses_Persalinan ?? "-";
    $bupas_kondisi_bayi = $data->Bupas_Kondisi_Bayi ?? "-";
    $bupas_komplikasi_nifas = $data->Bupas_Komplikasi_Nifas ?? "-";
    $bb_lahir_anak = $data->Bb_Lahir_Anak ?? "-";
    $tb_lahir_anak = $data->Tb_Lahir_Anak ?? "-";
    $bb_ukur_anak = $data->Bb_Ukur_Anak ?? "-";
    $tb_ukur_anak = $data->Tb_Ukur_Anak ?? "-";

    $usia_catin = $data->Usia_Catin ?? 0;
    $tgl_nikah = $data->Tgl_Nikah ?? "-";
    $hb = $data->Hb ?? 0;
    $tb = $data->Tb ?? 0;
    $bb_lahir = $data->Bb_Lahir ?? 0;
    $lila = $data->Lila ?? 0;
    $anemia = $data->Anemia ?? "-";
    $tfu = $data->Tfu ?? 0;
    $lingkar_kepala = $data->Lingkar_Kepala ?? 0;
    $usia_hamil = $data->Usia_Hamil ?? 0;
    $anak_ke = $data->Anak_Ke ?? 0;
    $mbg_3b = $data->Mbg_3b ?? "-";
    $pus_4t = $data->Pus_4t ?? "-";
    $alat_kontrasepsi = $data->Alat_Kontrasepsi ?? "-";

    // 5. Intervensi & Sanitasi
    $intervensi = $data->Intervensi ?? "-";
    $air_minum = $data->Air_Minum ?? "-";
    $sumber_intervensi_air = $data->Sumber_Intervensi_Air ?? "-";
    $sanitasi = $data->Sanitasi ?? "-";
    $sumber_intervensi_jamban = $data->Sumber_Intervensi_Jamban ?? "-";

    // 6. Foto, GPS, & Status Risiko
    $foto = $data->Foto ?? "-";
    $lokasi_gps = $data->Lokasi_Gps ?? "-";
    $status_risiko = $data->Status_Risiko ?? "TIDAK BERISIKO";
    $alasan_risiko = $data->Alasan_Risiko ?? "Parameter Aman";
    $status_pendampingan = $data->Status_Pendampingan ?? "Data KRS Baru";

    // 7. Kesehatan Mental (STRES-G)
    $skor_s1 = $data->Skor_S1 ?? 0;
    $skor_s2 = $data->Skor_S2 ?? 0;
    $skor_s3 = $data->Skor_S3 ?? 0;
    $skor_s4 = $data->Skor_S4 ?? 0;
    $total_skor_mental = $data->Total_Skor_Mental ?? 0;
    $status_mental = $data->Status_Mental ?? "-";

    // Eksekusi INSERT ke Database
    $query_insert = "INSERT INTO pendampingan_krs 
        (kecamatan, desa, jenis_sasaran, desil, pendidikan_kk, pekerjaan_kk, kepemilikan_rumah, luas_lantai, jenis_dinding, jenis_atap, jenis_lantai, sumber_listrik, bahan_bakar, aset_kendaraan, tabungan, emas_perhiasan, no_tim_tpk, nama_kader_tpk, nama_kk, nama_sasaran, nik_sasaran, no_hp, alamat, tempat_pemeriksaan_catin, catin_terpapar_rokok, catin_pria_merokok, catin_mendapat_ttd, bumil_anak_ke, bumil_jaminan_kesehatan, bupas_tgl_melahirkan, bupas_tempat_persalinan, bupas_proses_persalinan, bupas_kondisi_bayi, bupas_komplikasi_nifas, bb_lahir_anak, tb_lahir_anak, bb_ukur_anak, tb_ukur_anak, usia_catin, tgl_nikah, hb, tb, bb_lahir, lila, anemia, tfu, lingkar_kepala, usia_hamil, anak_ke, mbg_3b, pus_4t, alat_kontrasepsi, intervensi, air_minum, sumber_intervensi_air, sanitasi, sumber_intervensi_jamban, foto, lokasi_gps, status_risiko, alasan_risiko, status_pendampingan, skor_s1, skor_s2, skor_s3, skor_s4, total_skor_mental, status_mental) 
        VALUES 
        (:kecamatan, :desa, :jenis_sasaran, :desil, :pendidikan_kk, :pekerjaan_kk, :kepemilikan_rumah, :luas_lantai, :jenis_dinding, :jenis_atap, :jenis_lantai, :sumber_listrik, :bahan_bakar, :aset_kendaraan, :tabungan, :emas_perhiasan, :no_tim_tpk, :nama_kader_tpk, :nama_kk, :nama_sasaran, :nik, :no_hp, :alamat, :tempat_pemeriksaan_catin, :catin_terpapar_rokok, :catin_pria_merokok, :catin_mendapat_ttd, :bumil_anak_ke, :bumil_jaminan_kesehatan, :bupas_tgl_melahirkan, :bupas_tempat_persalinan, :bupas_proses_persalinan, :bupas_kondisi_bayi, :bupas_komplikasi_nifas, :bb_lahir_anak, :tb_lahir_anak, :bb_ukur_anak, :tb_ukur_anak, :usia_catin, :tgl_nikah, :hb, :tb, :bb_lahir, :lila, :anemia, :tfu, :lingkar_kepala, :usia_hamil, :anak_ke, :mbg_3b, :pus_4t, :alat_kontrasepsi, :intervensi, :air_minum, :sumber_intervensi_air, :sanitasi, :sumber_intervensi_jamban, :foto, :lokasi_gps, :status_risiko, :alasan_risiko, :status_pendampingan, :skor_s1, :skor_s2, :skor_s3, :skor_s4, :total_skor_mental, :status_mental)";

    $stmt = $db->prepare($query_insert);
    
    // Binding parameters
    $params = [
        ':kecamatan' => $kecamatan, ':desa' => $desa, ':jenis_sasaran' => $jenis_sasaran, ':desil' => $desil,
        ':pendidikan_kk' => $pendidikan_kk, ':pekerjaan_kk' => $pekerjaan_kk, ':kepemilikan_rumah' => $kepemilikan_rumah,
        ':luas_lantai' => $luas_lantai, ':jenis_dinding' => $jenis_dinding, ':jenis_atap' => $jenis_atap, ':jenis_lantai' => $jenis_lantai,
        ':sumber_listrik' => $sumber_listrik, ':bahan_bakar' => $bahan_bakar, ':aset_kendaraan' => $aset_kendaraan,
        ':tabungan' => $tabungan, ':emas_perhiasan' => $emas_perhiasan, ':no_tim_tpk' => $no_tim_tpk, ':nama_kader_tpk' => $nama_kader_tpk,
        ':nama_kk' => $nama_kk, ':nama_sasaran' => $nama_sasaran, ':nik' => $nik, ':no_hp' => $no_hp, ':alamat' => $alamat,
        ':tempat_pemeriksaan_catin' => $tempat_pemeriksaan_catin, ':catin_terpapar_rokok' => $catin_terpapar_rokok,
        ':catin_pria_merokok' => $catin_pria_merokok, ':catin_mendapat_ttd' => $catin_mendapat_ttd, ':bumil_anak_ke' => $bumil_anak_ke,
        ':bumil_jaminan_kesehatan' => $bumil_jaminan_kesehatan, ':bupas_tgl_melahirkan' => $bupas_tgl_melahirkan,
        ':bupas_tempat_persalinan' => $bupas_tempat_persalinan, ':bupas_proses_persalinan' => $bupas_proses_persalinan,
        ':bupas_kondisi_bayi' => $bupas_kondisi_bayi, ':bupas_komplikasi_nifas' => $bupas_komplikasi_nifas,
        ':bb_lahir_anak' => $bb_lahir_anak, ':tb_lahir_anak' => $tb_lahir_anak, ':bb_ukur_anak' => $bb_ukur_anak, ':tb_ukur_anak' => $tb_ukur_anak,
        ':usia_catin' => $usia_catin, ':tgl_nikah' => $tgl_nikah, ':hb' => $hb, ':tb' => $tb, ':bb_lahir' => $bb_lahir,
        ':lila' => $lila, ':anemia' => $anemia, ':tfu' => $tfu, ':lingkar_kepala' => $lingkar_kepala, ':usia_hamil' => $usia_hamil,
        ':anak_ke' => $anak_ke, ':mbg_3b' => $mbg_3b, ':pus_4t' => $pus_4t, ':alat_kontrasepsi' => $alat_kontrasepsi,
        ':intervensi' => $intervensi, ':air_minum' => $air_minum, ':sumber_intervensi_air' => $sumber_intervensi_air,
        ':sanitasi' => $sanitasi, ':sumber_intervensi_jamban' => $sumber_intervensi_jamban, ':foto' => $foto,
        ':lokasi_gps' => $lokasi_gps, ':status_risiko' => $status_risiko, ':alasan_risiko' => $alasan_risiko,
        ':status_pendampingan' => $status_pendampingan, ':skor_s1' => $skor_s1, ':skor_s2' => $skor_s2,
        ':skor_s3' => $skor_s3, ':skor_s4' => $skor_s4, ':total_skor_mental' => $total_skor_mental, ':status_mental' => $status_mental
    ];

    if ($stmt->execute($params)) {
        echo json_encode(array("result" => "success", "message" => "Data lengkap berhasil disimpan."));
    } else {
        echo json_encode(array("result" => "error", "message" => "Gagal menyimpan data."));
    }
} else {
    echo json_encode(array("result" => "error", "message" => "NIK Sasaran kosong."));
}
?>