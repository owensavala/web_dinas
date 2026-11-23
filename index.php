<?php
// Website Resmi Dinas Kebudayaan Kota Ternate - Tailwind CSS Edition
require_once __DIR__ . '/koneksi.php';

$halaman = isset($_GET['halaman']) ? trim($_GET['halaman']) : '';

// Judul halaman dinamis
$page_titles = [
    'cagar-budaya'        => 'Katalog Cagar Budaya & Situs Sejarah - Dinas Kebudayaan Kota Ternate',
    'visi-misi'           => 'Visi & Misi - Dinas Kebudayaan Kota Ternate',
    'sejarah'             => 'Sejarah Kebudayaan - Dinas Kebudayaan Kota Ternate',
    'struktur-organisasi' => 'Struktur Organisasi - Dinas Kebudayaan Kota Ternate',
    'pejabat'             => 'Pejabat Struktural - Dinas Kebudayaan Kota Ternate',
    'berita'              => 'Warta Budaya & Agenda Kegiatan - Dinas Kebudayaan Kota Ternate',
    'halaman_berita'      => 'Detail Berita - Dinas Kebudayaan Kota Ternate',
    'foto'                => 'Galeri Foto - Dinas Kebudayaan Kota Ternate',
    'video'               => 'Galeri Video - Dinas Kebudayaan Kota Ternate',
    'unduhan'             => 'Pusat Unduhan Dokumen Resmi - Dinas Kebudayaan Kota Ternate',
    'kontak'              => 'Kontak & Layanan Pengaduan - Dinas Kebudayaan Kota Ternate',
];

$page_title = $page_titles[$halaman] ?? 'Dinas Kebudayaan Kota Ternate - Website Resmi';

// Sertakan Header
include 'header.php';
?>

<!-- Kontainer Konten Utama -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Kolom Konten Utama -->
        <div class="lg:col-span-8 space-y-6">
            <?php
            switch ($halaman) {
                case 'cagar-budaya':
                    include 'cagar-budaya.php';
                    break;
                case 'peta-budaya':
                case 'webgis':
                    include 'peta-budaya.php';
                    break;
                case 'sanggar':
                case 'komunitas':
                    include 'sanggar.php';
                    break;
                case 'kalender':
                case 'events':
                case 'festival':
                    include 'kalender.php';
                    break;
                case 'glosarium':
                case 'kamus':
                    include 'glosarium.php';
                    break;
                case 'survei':
                case 'ikm':
                    include 'survei.php';
                    break;
                case 'visi-misi':
                    include 'visi-misi.php';
                    break;
                case 'sejarah':
                    include 'sejarah.php';
                    break;
                case 'struktur-organisasi':
                case 'satuan':
                case 'satuan-kerja':
                    include 'struktur-organisasi.php';
                    break;
                case 'pejabat':
                    include 'pejabat.php';
                    break;
                case 'berita':
                    include 'berita.php';
                    break;
                case 'halaman_berita':
                    include 'halaman_berita.php';
                    break;
                case 'foto':
                    include 'foto.php';
                    break;
                case 'video':
                    include 'video.php';
                    break;
                case 'unduhan':
                case 'layanan':
                    include 'unduhan.php';
                    break;
                case 'kontak':
                    include 'kontak.php';
                    break;
                default:
                    include 'beranda.php';
                    break;
            }
            ?>
        </div>

        <!-- Kolom Sidebar Terpadu -->
        <div class="lg:col-span-4 sticky top-28 space-y-6">
            <?php include 'sidebar.php'; ?>
        </div>
    </div>
</main>

<?php
// Sertakan Footer
include 'footer.php';
?>
