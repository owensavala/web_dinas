<?php
// Live AJAX Search API - Dinas Kebudayaan Kota Ternate Enterprise
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/koneksi.php';

$q = trim($_GET['q'] ?? '');

if (strlen($q) < 2) {
    echo json_encode(['results' => []]);
    exit();
}

$param = "%" . $q . "%";
$results = [];

// 1. Cari di Cagar Budaya (WebGIS & Katalog)
$stmt_cb = mysqli_prepare($koneksi, "SELECT id, nama, kategori, deskripsi FROM cagar_budaya WHERE nama LIKE ? OR deskripsi LIKE ? LIMIT 3");
mysqli_stmt_bind_param($stmt_cb, "ss", $param, $param);
mysqli_stmt_execute($stmt_cb);
$res_cb = mysqli_stmt_get_result($stmt_cb);
while ($row = mysqli_fetch_assoc($res_cb)) {
    $results[] = [
        'type' => 'Cagar Budaya',
        'title' => $row['nama'],
        'desc' => mb_substr(strip_tags($row['deskripsi']), 0, 75) . '...',
        'badge' => $row['kategori'],
        'icon' => 'bi-bank2 text-amber-600',
        'url' => 'index.php?halaman=peta-budaya'
    ];
}

// 2. Cari di Kalender Event & Festival Budaya
$stmt_ev = mysqli_prepare($koneksi, "SELECT id, nama_event, lokasi, deskripsi FROM kalender_budaya WHERE nama_event LIKE ? OR deskripsi LIKE ? LIMIT 3");
mysqli_stmt_bind_param($stmt_ev, "ss", $param, $param);
mysqli_stmt_execute($stmt_ev);
$res_ev = mysqli_stmt_get_result($stmt_ev);
while ($row = mysqli_fetch_assoc($res_ev)) {
    $results[] = [
        'type' => 'Festival Budaya',
        'title' => $row['nama_event'],
        'desc' => 'Lokasi: ' . $row['lokasi'],
        'badge' => 'Event',
        'icon' => 'bi-calendar-event-fill text-rose-600',
        'url' => 'index.php?halaman=kalender'
    ];
}

// 3. Cari di Sanggar Budaya & Komunitas Seni
$stmt_sg = mysqli_prepare($koneksi, "SELECT id, nama_sanggar, jenis_kesenian, nama_ketua FROM sanggar_budaya WHERE nama_sanggar LIKE ? OR jenis_kesenian LIKE ? LIMIT 3");
mysqli_stmt_bind_param($stmt_sg, "ss", $param, $param);
mysqli_stmt_execute($stmt_sg);
$res_sg = mysqli_stmt_get_result($stmt_sg);
while ($row = mysqli_fetch_assoc($res_sg)) {
    $results[] = [
        'type' => 'Sanggar Seni',
        'title' => $row['nama_sanggar'],
        'desc' => $row['jenis_kesenian'] . ' • Pimpinan: ' . $row['nama_ketua'],
        'badge' => 'Komunitas',
        'icon' => 'bi-people-fill text-emerald-600',
        'url' => 'index.php?halaman=sanggar'
    ];
}

// 4. Cari di Glosarium Istilah Adat Ternate
$stmt_glo = mysqli_prepare($koneksi, "SELECT id, istilah, pelafalan, arti_definisi, kategori FROM glosarium_budaya WHERE istilah LIKE ? OR arti_definisi LIKE ? LIMIT 3");
mysqli_stmt_bind_param($stmt_glo, "ss", $param, $param);
mysqli_stmt_execute($stmt_glo);
$res_glo = mysqli_stmt_get_result($stmt_glo);
while ($row = mysqli_fetch_assoc($res_glo)) {
    $results[] = [
        'type' => 'Istilah Adat',
        'title' => $row['istilah'] . (!empty($row['pelafalan']) ? ' [' . $row['pelafalan'] . ']' : ''),
        'desc' => mb_substr($row['arti_definisi'], 0, 75) . '...',
        'badge' => $row['kategori'],
        'icon' => 'bi-book-half text-amber-500',
        'url' => 'index.php?halaman=glosarium&q=' . urlencode($row['istilah'])
    ];
}

// 5. Cari di Warta / Agenda
$stmt_w = mysqli_prepare($koneksi, "SELECT id, judul, tanggal FROM agenda_b WHERE judul LIKE ? LIMIT 3");
mysqli_stmt_bind_param($stmt_w, "s", $param);
mysqli_stmt_execute($stmt_w);
$res_w = mysqli_stmt_get_result($stmt_w);
while ($row = mysqli_fetch_assoc($res_w)) {
    $results[] = [
        'type' => 'Warta Budaya',
        'title' => $row['judul'],
        'desc' => 'Dipublikasikan pada ' . date('d M Y', strtotime($row['tanggal'])),
        'badge' => 'Warta',
        'icon' => 'bi-newspaper text-orange-600',
        'url' => 'index.php?halaman=halaman_berita&id=' . $row['id']
    ];
}

// 6. Pencocokan Kata Kunci Statis Halaman
$static_pages = [
    'peta' => ['title' => 'WebGIS Peta Sebaran Cagar Budaya', 'desc' => 'Peta interaktif cagar budaya dan jalur rempah Kota Ternate.', 'url' => 'index.php?halaman=peta-budaya'],
    'gis' => ['title' => 'WebGIS Geospasial Cagar Budaya', 'desc' => 'Peta interaktif cagar budaya dan jalur rempah Kota Ternate.', 'url' => 'index.php?halaman=peta-budaya'],
    'kalender' => ['title' => 'Kalender Festival & Event Budaya', 'desc' => 'Calendar of Events Kota Ternate dan hitung mundur acara.', 'url' => 'index.php?halaman=kalender'],
    'sanggar' => ['title' => 'Fasilitasi & Direktori Sanggar Seni', 'desc' => 'Pendaftaran mandiri dan direktori sanggar tari & musik.', 'url' => 'index.php?halaman=sanggar'],
    'glosarium' => ['title' => 'Glosarium Bahasa & Budaya Ternate', 'desc' => 'Kamus digital istilah adat se atorang Kesultanan Ternate.', 'url' => 'index.php?halaman=glosarium'],
    'survei' => ['title' => 'Survei Kepuasan Masyarakat (IKM)', 'desc' => 'Penilaian transparansi layanan publik standar PermenPAN-RB.', 'url' => 'index.php?halaman=survei'],
    'ikm' => ['title' => 'Indeks Kepuasan Masyarakat (IKM)', 'desc' => 'Skor mutu pelayanan digital Dinas Kebudayaan Kota Ternate.', 'url' => 'index.php?halaman=survei'],
    'visi misi' => ['title' => 'Visi & Misi Dinas Kebudayaan', 'desc' => 'Rencana strategis dan visi pembangunan kebudayaan Kota Ternate.', 'url' => 'index.php?halaman=visi-misi'],
    'sejarah' => ['title' => 'Sejarah Kebudayaan Kota Ternate', 'desc' => 'Rekam jejak berdirinya dinas dan sejarah Kota Pusaka Ternate.', 'url' => 'index.php?halaman=sejarah'],
    'struktur' => ['title' => 'Struktur Organisasi & Pejabat', 'desc' => 'Bagan susunan organisasi dan tupoksi aparatur dinas.', 'url' => 'index.php?halaman=struktur-organisasi'],
    'kontak' => ['title' => 'Layanan Pengaduan & Kontak Dinas', 'desc' => 'Kanal aspirasi warga dan pelacakan tiket aduan resmi.', 'url' => 'index.php?halaman=kontak'],
    'unduhan' => ['title' => 'Pusat Unduhan Dokumen Resmi', 'desc' => 'Daftar dokumen publik dan laporan resmi dinas.', 'url' => 'index.php?halaman=unduhan'],
];

foreach ($static_pages as $keyword => $page_info) {
    if (stripos($keyword, $q) !== false || stripos($page_info['title'], $q) !== false) {
        $results[] = [
            'type' => 'Halaman Layanan',
            'title' => $page_info['title'],
            'desc' => $page_info['desc'],
            'badge' => 'Portal',
            'icon' => 'bi-compass-fill text-indigo-600',
            'url' => $page_info['url']
        ];
    }
}

echo json_encode(['results' => $results]);
exit();
