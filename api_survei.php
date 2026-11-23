<?php
require_once __DIR__ . '/koneksi.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Metode permintaan tidak valid.']);
    exit;
}

$nama        = trim($_POST['nama'] ?? 'Masyarakat Umum');
$email       = trim($_POST['email'] ?? '');
$pekerjaan   = trim($_POST['pekerjaan'] ?? 'Masyarakat Umum');
$kemudahan   = max(1, min(5, (int)($_POST['kemudahan'] ?? 5)));
$tampilan    = max(1, min(5, (int)($_POST['tampilan'] ?? 5)));
$info        = max(1, min(5, (int)($_POST['kualitas_info'] ?? 5)));
$kecepatan   = max(1, min(5, (int)($_POST['kecepatan'] ?? 5)));
$saran       = trim($_POST['saran'] ?? '');

$stmt = mysqli_prepare($koneksi, "INSERT INTO survei_kepuasan (nama, email, pekerjaan, skor_kemudahan, skor_tampilan, skor_kualitas_info, skor_kecepatan, saran) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sssiiiis", $nama, $email, $pekerjaan, $kemudahan, $tampilan, $info, $kecepatan, $saran);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Penilaian Anda sangat berharga untuk peningkatan mutu layanan digital Dinas Kebudayaan Kota Ternate.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menyimpan penilaian: ' . mysqli_error($koneksi)
    ]);
}
exit;
