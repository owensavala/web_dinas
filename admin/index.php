<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/agenda_fungsi.php';

// Early handler: Backup Database SQL
if (isset($_GET['action']) && $_GET['action'] === 'backup_db') {
    $db_name = "diskeb";
    $filename = "backup_diskeb_" . date("Ymd_His") . ".sql";
    header("Content-Type: application/sql; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "-- ========================================================\n";
    echo "-- CADANGAN BASIS DATA RESMI DINAS KEBUDAYAAN KOTA TERNATE\n";
    echo "-- Tanggal & Waktu: " . date("Y-m-d H:i:s") . " WIT\n";
    echo "-- Engine: MySQL / MariaDB (Database: diskeb)\n";
    echo "-- ========================================================\n\n";
    echo "SET FOREIGN_KEY_CHECKS=0;\n";
    echo "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
    echo "START TRANSACTION;\n\n";

    $tables = [];
    $res_tables = mysqli_query($koneksi, "SHOW TABLES");
    while ($row = mysqli_fetch_row($res_tables)) {
        $tables[] = $row[0];
    }

    foreach ($tables as $table) {
        echo "-- --------------------------------------------------------\n";
        echo "-- Struktur Tabel untuk `$table`\n";
        echo "-- --------------------------------------------------------\n";
        echo "DROP TABLE IF EXISTS `$table`;\n";
        $res_create = mysqli_query($koneksi, "SHOW CREATE TABLE `$table`");
        if ($res_create && $row_create = mysqli_fetch_row($res_create)) {
            echo $row_create[1] . ";\n\n";
        }

        $res_data = mysqli_query($koneksi, "SELECT * FROM `$table`");
        $num_rows = mysqli_num_rows($res_data);
        if ($num_rows > 0) {
            echo "-- Dumping data untuk tabel `$table` ($num_rows baris data)\n";
            while ($row_data = mysqli_fetch_assoc($res_data)) {
                $cols = array_keys($row_data);
                $escaped_vals = array_map(function($val) use ($koneksi) {
                    if ($val === null) return "NULL";
                    return "'" . mysqli_real_escape_string($koneksi, $val) . "'";
                }, array_values($row_data));

                echo "INSERT INTO `$table` (`" . implode("`, `", $cols) . "`) VALUES (" . implode(", ", $escaped_vals) . ");\n";
            }
            echo "\n";
        }
    }

    echo "SET FOREIGN_KEY_CHECKS=1;\n";
    echo "COMMIT;\n";
    exit();
}

// Early handler: Export CSV Aduan Warga
if (isset($_GET['action']) && $_GET['action'] === 'export_csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=Laporan_Aduan_Warga_Disbud_Ternate_' . date('Ymd_His') . '.csv');
    $output = fopen('php://output', 'w');
    fputs($output, "\xEF\xBB\xBF"); // BOM UTF-8 for Excel
    fputcsv($output, ['No', 'Nomor Tiket', 'Nama Pelapor', 'No. WhatsApp', 'Email', 'Kategori', 'Isi Aspirasi/Aduan', 'Status', 'Tanggal Masuk']);
    $q_exp = mysqli_query($koneksi, "SELECT * FROM pesan_aduan ORDER BY id DESC");
    $no = 1;
    while ($row_e = mysqli_fetch_assoc($q_exp)) {
        fputcsv($output, [
            $no++,
            $row_e['nomor_tiket'],
            $row_e['nama'],
            $row_e['telepon'],
            $row_e['email'],
            $row_e['kategori'],
            $row_e['pesan'],
            $row_e['status'],
            $row_e['tanggal']
        ]);
    }
    fclose($output);
    exit();
}

$halaman = $_GET['halaman'] ?? 'home';
$admin_user = $_SESSION['admin_username'] ?? 'Administrator';

// Hitung aduan baru untuk badge sidebar
$q_aduan_baru = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesan_aduan WHERE status = 'Baru'");
$count_aduan_baru = mysqli_fetch_assoc($q_aduan_baru)['total'] ?? 0;

// Hitung pendaftaran sanggar baru yang menunggu verifikasi
$q_sgr_pending = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM sanggar_budaya WHERE status_verifikasi = 'Menunggu'");
$count_sgr_pending = mysqli_fetch_assoc($q_sgr_pending)['total'] ?? 0;

// Menu mapping untuk breadcrumbs
$menu_titles = [
    'home'             => 'Dashboard Ringkasan',
    'berita'           => 'Manajemen Warta & Berita',
    'agenda'           => 'Manajemen Warta & Berita',
    'agenda-baru'      => 'Tambah Warta Baru',
    'edit_agenda'      => 'Edit Warta',
    'galeri'           => 'Manajemen Galeri Foto & Video',
    'aduan'            => 'Kotak Masuk Aduan Masyarakat',
    'cagar_admin'      => 'Manajemen Cagar Budaya & WebGIS',
    'sanggar_admin'    => 'Verifikasi & Direktori Sanggar Seni',
    'survei_admin'     => 'Rekapitulasi Indeks Kepuasan Masyarakat (IKM)',
    'edit-kepala'      => 'Profil Kepala Dinas',
    'visi-misi'        => 'Manajemen Visi & Misi',
    'edit-visimisi'    => 'Edit Visi & Misi',
    'sejarah'          => 'Manajemen Sejarah',
    'edit-sejarah'     => 'Edit Sejarah',
    'struktur'         => 'Struktur Organisasi & Pejabat',
    'pengaturan'       => 'Pengaturan Akun & Keamanan',
];

$page_header_title = $menu_titles[$halaman] ?? 'Dashboard Administrator';
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_header_title) ?> | Admin Dinas Kebudayaan Kota Ternate</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="../asset/js/tailwind-config.js"></script>
    
    <!-- Chart.js for Visual Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom Scrollbar & Cream Mocha Base -->
    <style>
        :root {
            --mocha-latar: #F6EFE6;
            --mocha-permukaan: #E9DCCB;
            --mocha-teks: #2B1D14;
            --mocha-aksen: #A8643A;
            --mocha-redup: #8C7B6B;
        }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #F6EFE6; }
        ::-webkit-scrollbar-thumb { background: #D9C5B0; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #A8643A; }
    </style>
</head>
<body class="h-full font-sans antialiased text-[#2B1D14] bg-[#F6EFE6] flex overflow-hidden">

    <!-- Sidebar Navigasi Kiri (Desktop) - Cream Mocha -->
    <aside class="w-64 bg-[#2B1D14] text-[#E9DCCB] flex-shrink-0 flex flex-col border-r border-[#38271E] shadow-xl z-30 transition-all duration-300 hidden md:flex">
        <!-- Brand Header -->
        <div class="h-20 flex items-center px-6 border-b border-[#38271E] bg-[#1A110B]/50">
            <a href="index.php" class="flex items-center space-x-3 group">
                <img src="https://tse4.mm.bing.net/th?id=OIP.kN9Rodvz86zqoyN7QrYdagHaI0&pid=Api&P=0&h=220" alt="Logo" class="w-9 h-9 object-contain rounded bg-white p-0.5 shadow">
                <div>
                    <h1 class="text-sm font-extrabold text-white tracking-wide">DISBUD PANEL</h1>
                    <span class="text-[10px] font-semibold text-[#A8643A] uppercase tracking-widest block">Kota Ternate</span>
                </div>
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-6">
            <!-- Grup Utama -->
            <div>
                <span class="px-3 text-[11px] font-bold text-[#8C7B6B] uppercase tracking-wider block mb-2">Utama</span>
                <nav class="space-y-1">
                    <a href="index.php" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= ($halaman == 'home' || empty($halaman)) ? 'bg-[#A8643A] text-white shadow-sm font-bold' : 'hover:bg-[#38271E] hover:text-[#E9DCCB] text-[#E9DCCB]/70' ?>">
                        <i class="bi bi-grid-1x2-fill mr-3 text-sm opacity-80"></i>
                        <span>Dashboard Analytics</span>
                    </a>
                </nav>
            </div>

            <!-- Grup Konten & Warta -->
            <div>
                <span class="px-3 text-[11px] font-bold text-[#8C7B6B] uppercase tracking-wider block mb-2">Publikasi & Media</span>
                <nav class="space-y-1">
                    <a href="index.php?halaman=berita" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= in_array($halaman, ['berita', 'agenda', 'agenda-baru', 'edit_agenda']) ? 'bg-[#A8643A] text-white shadow-sm font-bold' : 'hover:bg-[#38271E] hover:text-[#E9DCCB] text-[#E9DCCB]/70' ?>">
                        <i class="bi bi-newspaper mr-3 text-sm opacity-80"></i>
                        <span>Warta & Agenda</span>
                    </a>
                    <a href="index.php?halaman=galeri" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= ($halaman == 'galeri') ? 'bg-[#A8643A] text-white shadow-sm font-bold' : 'hover:bg-[#38271E] hover:text-[#E9DCCB] text-[#E9DCCB]/70' ?>">
                        <i class="bi bi-images mr-3 text-sm opacity-80"></i>
                        <span>Galeri Foto & Video</span>
                    </a>
                    <a href="index.php?halaman=aduan" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= ($halaman == 'aduan') ? 'bg-[#A8643A] text-white shadow-sm font-bold' : 'hover:bg-[#38271E] hover:text-[#E9DCCB] text-[#E9DCCB]/70' ?>">
                        <span class="flex items-center">
                            <i class="bi bi-chat-square-dots mr-3 text-sm opacity-80"></i>
                            <span>Aduan Warga</span>
                        </span>
                        <?php if ($count_aduan_baru > 0): ?>
                            <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-[#A8643A] text-white animate-pulse">
                                <?= $count_aduan_baru ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </nav>
            </div>

            <!-- Grup Layanan Budaya & SPBE -->
            <div>
                <span class="px-3 text-[11px] font-bold text-[#8C7B6B] uppercase tracking-wider block mb-2">Layanan Budaya & SPBE</span>
                <nav class="space-y-1">
                    <a href="index.php?halaman=cagar_admin" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= ($halaman == 'cagar_admin') ? 'bg-[#A8643A] text-white shadow-sm font-bold' : 'hover:bg-[#38271E] hover:text-[#E9DCCB] text-[#E9DCCB]/70' ?>">
                        <i class="bi bi-geo-alt-fill mr-3 text-sm opacity-80 text-[#E9DCCB]"></i>
                        <span>Cagar Budaya & WebGIS</span>
                    </a>
                    <a href="index.php?halaman=sanggar_admin" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= ($halaman == 'sanggar_admin') ? 'bg-[#A8643A] text-white shadow-sm font-bold' : 'hover:bg-[#38271E] hover:text-[#E9DCCB] text-[#E9DCCB]/70' ?>">
                        <span class="flex items-center">
                            <i class="bi bi-people-fill mr-3 text-sm opacity-80 text-[#E9DCCB]"></i>
                            <span>Sanggar Budaya</span>
                        </span>
                        <?php if ($count_sgr_pending > 0): ?>
                            <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-[#A8643A] text-white animate-pulse">
                                <?= $count_sgr_pending ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <a href="index.php?halaman=survei_admin" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= ($halaman == 'survei_admin') ? 'bg-[#A8643A] text-white shadow-sm font-bold' : 'hover:bg-[#38271E] hover:text-[#E9DCCB] text-[#E9DCCB]/70' ?>">
                        <i class="bi bi-star-fill mr-3 text-sm opacity-80 text-[#E9DCCB]"></i>
                        <span>Rekapitulasi IKM</span>
                    </a>
                </nav>
            </div>

            <!-- Grup Profil Dinas -->
            <div>
                <span class="px-3 text-[11px] font-bold text-[#8C7B6B] uppercase tracking-wider block mb-2">Profil OPD</span>
                <nav class="space-y-1">
                    <a href="index.php?halaman=edit-kepala&id=1" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= ($halaman == 'edit-kepala') ? 'bg-[#A8643A] text-white shadow-sm font-bold' : 'hover:bg-[#38271E] hover:text-[#E9DCCB] text-[#E9DCCB]/70' ?>">
                        <i class="bi bi-person-badge-fill mr-3 text-sm opacity-80"></i>
                        <span>Pimpinan Dinas</span>
                    </a>
                    <a href="index.php?halaman=visi-misi" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= in_array($halaman, ['visi-misi', 'edit-visimisi']) ? 'bg-[#A8643A] text-white shadow-sm font-bold' : 'hover:bg-[#38271E] hover:text-[#E9DCCB] text-[#E9DCCB]/70' ?>">
                        <i class="bi bi-bullseye mr-3 text-sm opacity-80"></i>
                        <span>Visi & Misi</span>
                    </a>
                    <a href="index.php?halaman=sejarah" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= in_array($halaman, ['sejarah', 'edit-sejarah']) ? 'bg-[#A8643A] text-white shadow-sm font-bold' : 'hover:bg-[#38271E] hover:text-[#E9DCCB] text-[#E9DCCB]/70' ?>">
                        <i class="bi bi-hourglass-split mr-3 text-sm opacity-80"></i>
                        <span>Sejarah Kebudayaan</span>
                    </a>
                    <a href="index.php?halaman=struktur" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= in_array($halaman, ['struktur', 'edit-struktur']) ? 'bg-[#A8643A] text-white shadow-sm font-bold' : 'hover:bg-[#38271E] hover:text-[#E9DCCB] text-[#E9DCCB]/70' ?>">
                        <i class="bi bi-diagram-3-fill mr-3 text-sm opacity-80"></i>
                        <span>Pejabat Struktural</span>
                    </a>
                </nav>
            </div>

            <!-- Grup Pengaturan -->
            <div>
                <span class="px-3 text-[11px] font-bold text-[#8C7B6B] uppercase tracking-wider block mb-2">Sistem</span>
                <nav class="space-y-1">
                    <a href="index.php?halaman=pengaturan" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition <?= ($halaman == 'pengaturan') ? 'bg-[#A8643A] text-white shadow-sm font-bold' : 'hover:bg-[#38271E] hover:text-[#E9DCCB] text-[#E9DCCB]/70' ?>">
                        <i class="bi bi-gear-fill mr-3 text-sm opacity-80"></i>
                        <span>Pengaturan Akun</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- User Quick Card & Logout -->
        <div class="p-4 border-t border-[#38271E] bg-[#1A110B]/60">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-[#A8643A] text-white flex items-center justify-center font-bold text-xs uppercase shadow">
                        <?= substr($admin_user, 0, 1) ?>
                    </div>
                    <div class="overflow-hidden">
                        <span class="text-xs font-bold text-white block truncate"><?= htmlspecialchars($admin_user) ?></span>
                        <span class="text-[10px] text-emerald-400 block font-medium">Online</span>
                    </div>
                </div>
                <a href="logout.php" class="p-2 rounded-lg text-[#8C7B6B] hover:text-rose-400 hover:bg-[#38271E] transition" title="Logout">
                    <i class="bi bi-box-arrow-right text-base"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Konten Utama (Header Atas + Subhalaman) -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navbar Header -->
        <header class="h-20 bg-white border-b border-[#E9DCCB] px-6 sm:px-8 flex items-center justify-between shadow-sm z-20">
            <div class="flex items-center space-x-4">
                <div>
                    <h2 class="text-lg font-bold text-[#2B1D14] tracking-tight leading-snug">
                        <?= htmlspecialchars($page_header_title) ?>
                    </h2>
                    <span class="text-xs text-[#8C7B6B] font-medium hidden sm:inline-block">Dinas Kebudayaan Pemerintah Kota Ternate</span>
                </div>
            </div>

            <!-- Right Controls -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                <a href="../index.php" target="_blank" class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-bold text-[#2B1D14] bg-[#E9DCCB]/60 hover:bg-[#E9DCCB] border border-[#E9DCCB] transition shadow-sm">
                    <i class="bi bi-box-arrow-up-right mr-1.5 text-[#A8643A]"></i>
                    <span class="hidden sm:inline">Lihat Web Publik</span>
                </a>
                
                <div class="h-5 w-px bg-[#E9DCCB] hidden sm:block"></div>

                <div class="flex items-center space-x-2 text-xs text-[#8C7B6B]">
                    <i class="bi bi-shield-check text-emerald-600 text-sm"></i>
                    <span class="hidden sm:inline font-semibold">Sistem Terproteksi</span>
                </div>
            </div>
        </header>

        <!-- Area Render Halaman Dinamis -->
        <main class="flex-1 overflow-y-auto p-6 sm:p-8">
            <div class="max-w-6xl mx-auto">
                <?php
                if (isset($_GET['halaman'])) {
                    if ($_GET['halaman'] == "agenda" || $_GET['halaman'] == "berita" || $_GET['halaman'] == "agenda-baru") {
                        include 'berita.php';
                    } elseif ($_GET['halaman'] == "edit_agenda") {
                        include 'agenda_input.php';
                    } elseif ($_GET['halaman'] == "galeri") {
                        include 'galeri.php';
                    } elseif ($_GET['halaman'] == "aduan") {
                        include 'aduan.php';
                    } elseif ($_GET['halaman'] == "cagar_admin") {
                        include 'cagar_admin.php';
                    } elseif ($_GET['halaman'] == "sanggar_admin") {
                        include 'sanggar_admin.php';
                    } elseif ($_GET['halaman'] == "survei_admin") {
                        include 'survei_admin.php';
                    } elseif ($_GET['halaman'] == "edit-kepala") {
                        include 'input_kepala.php';
                    } elseif ($_GET['halaman'] == "visi-misi" || $_GET['halaman'] == "edit-visimisi") {
                        include 'visi-misi.php';
                    } elseif ($_GET['halaman'] == "sejarah" || $_GET['halaman'] == "edit-sejarah") {
                        include 'sejarah.php';
                    } elseif ($_GET['halaman'] == "struktur" || $_GET['halaman'] == "edit-struktur") {
                        include 'struktur.php';
                    } elseif ($_GET['halaman'] == "pengaturan") {
                        include 'pengaturan.php';
                    } else {
                        include 'home.php';
                    }
                } else {
                    include 'home.php';
                }
                ?>
            </div>
        </main>
    </div>

</body>
</html>
