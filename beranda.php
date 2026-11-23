<?php
// Beranda Utama - Masterpiece Government Edition
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/agenda_fungsi.php';

// Ambil berita terbaru (agenda_b)
$sql_berita = "SELECT * FROM agenda_b ORDER BY id DESC LIMIT 4";
$q_berita   = mysqli_query($koneksi, $sql_berita);

// Ambil cagar budaya unggulan
$sql_cagar = "SELECT * FROM cagar_budaya LIMIT 3";
$q_cagar   = mysqli_query($koneksi, $sql_cagar);

// Ambil event festival terdekat
$q_ev_home = mysqli_query($koneksi, "SELECT * FROM kalender_budaya WHERE tanggal_mulai >= CURDATE() ORDER BY tanggal_mulai ASC LIMIT 1");
$ev_home = mysqli_fetch_assoc($q_ev_home);
if (!$ev_home) {
    $q_ev_home_fallback = mysqli_query($koneksi, "SELECT * FROM kalender_budaya ORDER BY tanggal_mulai DESC LIMIT 1");
    $ev_home = mysqli_fetch_assoc($q_ev_home_fallback);
}

// Ambil rata-rata IKM
$q_ikm_home = mysqli_query($koneksi, "SELECT COUNT(*) as total, AVG((skor_kemudahan+skor_tampilan+skor_kualitas_info+skor_kecepatan)/4) as skor_avg FROM survei_kepuasan");
$d_ikm_home = mysqli_fetch_assoc($q_ikm_home);
$ikm_skor_home = round(($d_ikm_home['skor_avg'] ?? 4.8) * 20, 1);
$ikm_total_resp = (int)($d_ikm_home['total'] ?? 0);
?>

<div class="space-y-10">
    <!-- Hero Showcase Carousel with Quick Search Overlay -->
    <div class="relative rounded-3xl overflow-hidden shadow-xl border border-[#E9DCCB] bg-[#2B1D14] group">
        <div id="tailwindCarousel" class="relative h-[380px] sm:h-[450px] w-full overflow-hidden">
            <!-- Slide 1 -->
            <div class="carousel-slide absolute inset-0 transition-opacity duration-1000 opacity-100 z-10">
                <img src="asset/gmbr/diskeb.png" alt="Dinas Kebudayaan" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#2B1D14] via-[#2B1D14]/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10 text-white z-20 space-y-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#A8643A] text-white shadow">
                        <i class="bi bi-star-fill mr-1.5 text-[#E9DCCB]"></i> Portal Resmi
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white max-w-2xl leading-tight font-serif">
                        Melestarikan Warisan Luhur & Titik Awal Jalur Rempah Dunia
                    </h2>
                    <p class="text-[#E9DCCB] text-xs sm:text-sm max-w-xl line-clamp-2">
                        Pusat perlindungan cagar budaya benteng, keraton, dan tradisi Kesultanan Ternate sebagai Kota Pusaka kebanggaan nusantara.
                    </p>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-slide absolute inset-0 transition-opacity duration-1000 opacity-0 z-0">
                <img src="https://1.bp.blogspot.com/-E0ha9A8nXA0/XCoBpvDjICI/AAAAAAAAe7Y/wfwUmoivrNE4Aj9aadf5ruTXyPPXibn0gCLcBGAs/s1600/landmark%2Bkota%2Bternate.jpg" alt="Landmark Ternate" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#2B1D14] via-[#2B1D14]/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10 text-white z-20 space-y-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#A8643A] text-white shadow">
                        <i class="bi bi-compass-fill mr-1.5 text-white"></i> Sejarah Peradaban Bahari
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white max-w-2xl leading-tight font-serif">
                        Episentrum Kejayaan Moloku Kie Raha
                    </h2>
                    <p class="text-[#E9DCCB] text-xs sm:text-sm max-w-xl line-clamp-2">
                        Kearifan lokal dan nilai sejarah cengkeh yang menghubungkan kepulauan rempah dengan jalur perniagaan maritim dunia.
                    </p>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-slide absolute inset-0 transition-opacity duration-1000 opacity-0 z-0">
                <img src="asset/gmbr/ternateppp.jpg" alt="Festival Budaya Ternate" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#2B1D14] via-[#2B1D14]/70 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10 text-white z-20 space-y-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#A8643A] text-white shadow">
                        <i class="bi bi-fire mr-1.5 text-amber-300"></i> Festival Ela-Ela
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white max-w-2xl leading-tight font-serif">
                        Semarak Tradisi Adat Se Atoran
                    </h2>
                    <p class="text-[#E9DCCB] text-xs sm:text-sm max-w-xl line-clamp-2">
                        Menghidupkan kembali tradisi membakar lampu minyak ela-ela, pentas seni tradisi, dan kolaborasi sanggar seni generasi muda.
                    </p>
                </div>
            </div>
        </div>

        <!-- Slider Controls -->
        <button type="button" onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-black/40 hover:bg-[#A8643A] text-white flex items-center justify-center backdrop-blur-sm transition z-30 opacity-0 group-hover:opacity-100">
            <i class="bi bi-chevron-left text-base"></i>
        </button>
        <button type="button" onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-black/40 hover:bg-[#A8643A] text-white flex items-center justify-center backdrop-blur-sm transition z-30 opacity-0 group-hover:opacity-100">
            <i class="bi bi-chevron-right text-base"></i>
        </button>
    </div>

    <!-- Live Cultural Metrics Counter Bar - Cream Mocha Palette -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-[#E9DCCB] shadow-sm text-center">
            <span class="text-2xl sm:text-3xl font-extrabold text-[#A8643A] block font-serif">47+</span>
            <span class="text-xs font-semibold text-[#8C7B6B]">Situs Cagar Budaya</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-[#E9DCCB] shadow-sm text-center">
            <span class="text-2xl sm:text-3xl font-extrabold text-[#A8643A] block font-serif">12+</span>
            <span class="text-xs font-semibold text-[#8C7B6B]">Festival Budaya Tahunan</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-[#E9DCCB] shadow-sm text-center">
            <span class="text-2xl sm:text-3xl font-extrabold text-[#A8643A] block font-serif">35</span>
            <span class="text-xs font-semibold text-[#8C7B6B]">Sanggar Kesenian Daerah</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-[#E9DCCB] shadow-sm text-center">
            <span class="text-2xl sm:text-3xl font-extrabold text-[#A8643A] block font-serif">1</span>
            <span class="text-xs font-semibold text-[#8C7B6B]">Kota Pusaka Dunia</span>
        </div>
    </div>

    <!-- Sambutan Pimpinan (Quote Card Mewah Cream Mocha) -->
    <div class="bg-gradient-to-br from-white via-white to-[#E9DCCB]/30 rounded-3xl p-6 sm:p-8 shadow-sm border border-[#E9DCCB] relative overflow-hidden">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="shrink-0 relative">
                <div class="w-28 h-36 rounded-2xl overflow-hidden shadow-lg ring-4 ring-[#E9DCCB]">
                    <img src="asset/gmbr/kepala1709108904_saa.jpg" alt="DR. Muslim Gani" class="w-full h-full object-cover">
                </div>
                <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#A8643A] text-white uppercase tracking-wider shadow">
                    Kepala Dinas
                </span>
            </div>
            <div class="space-y-3 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start space-x-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#A8643A] bg-[#E9DCCB]/50 px-2.5 py-0.5 rounded-full border border-[#E9DCCB]">
                        Sambutan Resmi Pimpinan
                    </span>
                </div>
                <h3 class="text-xl font-extrabold text-[#2B1D14] tracking-tight leading-snug font-serif">
                    "Menjaga Akar Budaya, Merawat Peradaban Bahari Ternate"
                </h3>
                <p class="text-[#504237] text-sm leading-relaxed italic">
                    "Selamat datang di portal informasi resmi Dinas Kebudayaan Kota Ternate. Kehadiran platform ini adalah wujud komitmen kami dalam keterbukaan informasi publik serta wadah sinergi bersama seluruh elemen masyarakat, akademisi, dan generasi muda untuk menjaga kehormatan sejarah dan cagar budaya Kota Pusaka Ternate."
                </p>
                <div class="pt-1 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div>
                        <strong class="text-[#2B1D14] text-sm block font-bold">DR. MUSLIM GANI, S.Pd., M.Pd.</strong>
                        <span class="text-xs text-[#8C7B6B]">Kepala Dinas Kebudayaan Kota Ternate</span>
                    </div>
                    <a href="index.php?halaman=visi-misi" class="inline-flex items-center text-xs font-bold text-[#A8643A] hover:text-[#94542E] transition">
                        <span>Pelajari Visi & Misi</span>
                        <i class="bi bi-arrow-right ml-1.5"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Showcase Cagar Budaya Unggulan Kota Ternate -->
    <div>
        <div class="flex items-center justify-between mb-5">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#A8643A]">Warisan Cagar Budaya</span>
                <h3 class="text-xl font-extrabold text-[#2B1D14] tracking-tight">Situs Sejarah & Cagar Budaya Unggulan</h3>
            </div>
            <a href="index.php?halaman=sejarah" class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold text-[#2B1D14] bg-[#E9DCCB]/60 hover:bg-[#E9DCCB] transition border border-[#E9DCCB]">
                <span>Eksplorasi Sejarah</span>
                <i class="bi bi-arrow-right ml-1.5"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if (mysqli_num_rows($q_cagar) > 0): ?>
                <?php while ($c = mysqli_fetch_assoc($q_cagar)): ?>
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-[#E9DCCB] hover:border-[#A8643A] hover:shadow-card-hover transition group flex flex-col">
                        <div class="h-44 overflow-hidden relative cursor-pointer" onclick="openLightbox('asset/gmbr/<?= htmlspecialchars($c['gambar']) ?>', '<?= htmlspecialchars($c['nama']) ?>')">
                            <img src="asset/gmbr/<?= htmlspecialchars($c['gambar']) ?>" alt="<?= htmlspecialchars($c['nama']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-[#2B1D14]/90 text-[#E9DCCB] backdrop-blur-sm border border-[#522B15]">
                                <?= htmlspecialchars($c['kategori']) ?>
                            </span>
                        </div>
                        <div class="p-5 flex flex-col flex-grow space-y-2">
                            <h4 class="font-bold text-[#2B1D14] text-base leading-snug group-hover:text-[#A8643A] transition">
                                <?= htmlspecialchars($c['nama']) ?>
                            </h4>
                            <p class="text-xs text-[#8C7B6B] flex items-center">
                                <i class="bi bi-geo-alt-fill text-[#A8643A] mr-1.5"></i> <?= htmlspecialchars($c['lokasi']) ?>
                            </p>
                            <p class="text-xs text-[#504237] line-clamp-3 leading-relaxed pt-1">
                                <?= htmlspecialchars($c['deskripsi']) ?>
                            </p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- 4 Pilar Program Kebudayaan -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#A8643A]">Fokus Strategis</span>
                <h3 class="text-xl font-extrabold text-[#2B1D14] tracking-tight">Pilar Pemajuan Kebudayaan</h3>
            </div>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-[#E9DCCB] hover:border-[#A8643A] transition group">
                <div class="w-10 h-10 rounded-xl bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                    <i class="bi bi-bank2"></i>
                </div>
                <h4 class="font-bold text-[#2B1D14] text-sm mb-1">Cagar Budaya</h4>
                <p class="text-xs text-[#8C7B6B] leading-relaxed">Perlindungan dan revitalisasi benteng serta situs bersejarah.</p>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-[#E9DCCB] hover:border-[#A8643A] transition group">
                <div class="w-10 h-10 rounded-xl bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                    <i class="bi bi-compass-fill"></i>
                </div>
                <h4 class="font-bold text-[#2B1D14] text-sm mb-1">Jalur Rempah</h4>
                <p class="text-xs text-[#8C7B6B] leading-relaxed">Revitalisasi jejak maritim dan diplomasi rempah dunia.</p>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-[#E9DCCB] hover:border-[#A8643A] transition group">
                <div class="w-10 h-10 rounded-xl bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                    <i class="bi bi-palette-fill"></i>
                </div>
                <h4 class="font-bold text-[#2B1D14] text-sm mb-1">Seni & Tradisi</h4>
                <p class="text-xs text-[#8C7B6B] leading-relaxed">Pembinaan sanggar tari, musik tradisional, dan festival.</p>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-[#E9DCCB] hover:border-[#A8643A] transition group">
                <div class="w-10 h-10 rounded-xl bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                    <i class="bi bi-journal-check"></i>
                </div>
                <h4 class="font-bold text-[#2B1D14] text-sm mb-1">Pusat Data WBTB</h4>
                <p class="text-xs text-[#8C7B6B] leading-relaxed">Registrasi Warisan Budaya Tak Benda khas daerah Ternate.</p>
            </div>
        </div>
    </div>

    <!-- Banner Inovasi WebGIS Kebudayaan - Rich Mocha Aesthetic -->
    <div class="relative bg-gradient-to-r from-[#2B1D14] via-[#522B15] to-[#A8643A] text-white rounded-3xl overflow-hidden shadow-xl border border-[#A8643A]/40 p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 max-w-xl">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#E9DCCB] text-[#2B1D14] uppercase tracking-wider">
                <i class="bi bi-geo-alt-fill mr-1.5 text-[#A8643A]"></i> Inovasi Geospasial
            </span>
            <h3 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight font-serif">
                WebGIS Cagar Budaya & Jalur Rempah Kota Ternate
            </h3>
            <p class="text-xs sm:text-sm text-[#E9DCCB] leading-relaxed">
                Jelajahi peta interaktif sebaran benteng peninggalan Portugis & Spanyol, Kedaton Kesultanan, dan situs cagar budaya nasional di seluruh penjuru pulau secara presisi.
            </p>
        </div>
        <div class="shrink-0 flex flex-wrap items-center gap-3 w-full md:w-auto">
            <a href="index.php?halaman=peta-budaya" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-2xl text-xs font-bold uppercase tracking-wider text-white bg-[#A8643A] hover:bg-[#94542E] shadow-lg transition transform hover:-translate-y-0.5 border border-white/20">
                <i class="bi bi-map-fill mr-2"></i> Buka Peta Budaya GIS
            </a>
            <a href="index.php?halaman=glosarium" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-3 rounded-2xl text-xs font-bold uppercase tracking-wider text-[#E9DCCB] bg-white/10 hover:bg-white/20 border border-white/20 transition">
                <i class="bi bi-book-half mr-2"></i> Glosarium Adat
            </a>
        </div>
    </div>

    <!-- Combo: Festival Terdekat & Penilaian IKM Publik -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-stretch">
        <!-- Event Terdekat (7 Kolom) -->
        <div class="md:col-span-7 bg-white rounded-3xl p-6 shadow-sm border border-[#E9DCCB] flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#A8643A] flex items-center">
                        <i class="bi bi-calendar-event-fill mr-1.5"></i> Festival Budaya Terdekat
                    </span>
                    <a href="index.php?halaman=kalender" class="text-xs font-bold text-[#A8643A] hover:underline">
                        Lihat Semua Kalender &rarr;
                    </a>
                </div>
                <?php if ($ev_home): ?>
                    <h4 class="text-lg font-extrabold text-[#2B1D14] font-serif mb-1">
                        <?= htmlspecialchars($ev_home['nama_event']) ?>
                    </h4>
                    <p class="text-xs text-[#8C7B6B] line-clamp-2 mb-3">
                        <?= htmlspecialchars($ev_home['deskripsi']) ?>
                    </p>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-[#504237]">
                        <span class="flex items-center"><i class="bi bi-clock-history mr-1 text-[#A8643A]"></i> <?= date('d M Y', strtotime($ev_home['tanggal_mulai'])) ?></span>
                        <span class="flex items-center"><i class="bi bi-geo-alt-fill mr-1 text-[#A8643A]"></i> <?= htmlspecialchars($ev_home['lokasi']) ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="pt-4 mt-3 border-t border-[#E9DCCB] flex items-center justify-between">
                <span class="text-[11px] text-[#8C7B6B]">Kalender Kegiatan Resmi (CoE) Dinas Kebudayaan</span>
                <a href="index.php?halaman=kalender" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#E9DCCB]/60 text-[#2B1D14] hover:bg-[#E9DCCB] transition">
                    Hitung Mundur Acara &rarr;
                </a>
            </div>
        </div>

        <!-- IKM Badge (5 Kolom) -->
        <div class="md:col-span-5 bg-[#2B1D14] text-white rounded-3xl p-6 shadow-sm border border-[#522B15] flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#E9DCCB] flex items-center">
                    <i class="bi bi-award-fill mr-1.5 text-[#A8643A]"></i> Indeks Kepuasan Masyarakat (IKM)
                </span>
                <div class="flex items-baseline space-x-2.5 mt-2">
                    <span class="text-4xl font-black font-mono tracking-tight text-white"><?= $ikm_skor_home ?></span>
                    <span class="text-xs text-[#E9DCCB]">/ 100</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        Mutu A (Sangat Baik)
                    </span>
                </div>
                <p class="text-xs text-[#E9DCCB]/80 mt-2 leading-relaxed">
                    Berdasarkan evaluasi <?= $ikm_total_resp ?> responden warga atas kualitas transparansi informasi dan layanan kebudayaan.
                </p>
            </div>
            <div class="pt-4 mt-2 border-t border-[#38271E] flex items-center justify-between">
                <span class="text-[11px] text-[#8C7B6B]">PermenPAN-RB No. 14/2017</span>
                <a href="index.php?halaman=survei" class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#A8643A] text-white hover:bg-[#94542E] transition shadow">
                    Beri Penilaian
                </a>
            </div>
        </div>
    </div>

    <!-- Warta Budaya & Agenda Terkini -->
    <div>
        <div class="flex items-center justify-between mb-5">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#A8643A]">Publikasi Resmi</span>
                <h3 class="text-xl font-extrabold text-[#2B1D14] tracking-tight">Warta & Agenda Budaya Terkini</h3>
            </div>
            <a href="index.php?halaman=berita" class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold text-[#2B1D14] bg-[#E9DCCB]/60 hover:bg-[#E9DCCB] transition border border-[#E9DCCB]">
                <span>Lihat Semua</span>
                <i class="bi bi-arrow-right ml-1.5"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <?php if (mysqli_num_rows($q_berita) > 0): ?>
                <?php while ($r1 = mysqli_fetch_assoc($q_berita)): ?>
                    <?php
                    $img_src = !empty($r1['gambar']) ? $r1['gambar'] : 'diskeb.png';
                    $formatted_date = date('d F Y', strtotime($r1['tanggal']));
                    ?>
                    <article class="bg-white rounded-2xl overflow-hidden shadow-sm border border-[#E9DCCB] hover:shadow-card-hover hover:border-[#A8643A] transition duration-300 flex flex-col group">
                        <div class="relative h-48 overflow-hidden bg-[#F6EFE6] cursor-pointer" onclick="openLightbox('asset/img/<?= htmlspecialchars($img_src) ?>', '<?= htmlspecialchars($r1['judul']) ?>')">
                            <img src="asset/img/<?= htmlspecialchars($img_src) ?>" 
                                 alt="<?= htmlspecialchars($r1['judul']) ?>" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 onerror="this.src='asset/gmbr/diskeb.png';">
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md text-[11px] font-bold bg-[#2B1D14]/90 text-[#E9DCCB] backdrop-blur-sm shadow border border-[#522B15]">
                                <i class="bi bi-tag-fill mr-1 text-[#A8643A]"></i> Agenda Dinas
                            </span>
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <div class="flex items-center text-xs text-[#8C7B6B] space-x-2 mb-2">
                                <i class="bi bi-calendar-event text-[#A8643A]"></i>
                                <span><?= $formatted_date ?></span>
                            </div>
                            <h4 class="font-bold text-[#2B1D14] text-base leading-snug group-hover:text-[#A8643A] transition line-clamp-2 mb-4">
                                <a href="index.php?halaman=halaman_berita&id=<?= $r1['id'] ?>">
                                    <?= htmlspecialchars($r1['judul']) ?>
                                </a>
                            </h4>
                            <div class="mt-auto pt-3 border-t border-[#E9DCCB] flex items-center justify-between text-xs">
                                <a href="index.php?halaman=halaman_berita&id=<?= $r1['id'] ?>" class="font-bold text-[#A8643A] hover:text-[#94542E] inline-flex items-center">
                                    <span>Baca Selengkapnya</span>
                                    <i class="bi bi-chevron-right ml-1 text-[10px]"></i>
                                </a>
                                <?php if (!empty($r1['file_pdf'])): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#E9DCCB]/60 text-[#2B1D14] border border-[#E9DCCB]">
                                        <i class="bi bi-file-earmark-pdf mr-1 text-[#A8643A]"></i> PDF
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-2 bg-white rounded-2xl p-8 text-center text-[#8C7B6B] border border-[#E9DCCB]">
                    <i class="bi bi-inbox text-4xl mb-2 block text-[#A8643A]"></i>
                    <p class="text-sm">Belum ada warta kebudayaan yang dipublikasikan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Script Simple Carousel Slider -->
<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('opacity-0', 'z-0');
                slide.classList.add('opacity-100', 'z-10');
            } else {
                slide.classList.remove('opacity-100', 'z-10');
                slide.classList.add('opacity-0', 'z-0');
            }
        });
    }

    function nextSlide() {
        if (!slides.length) return;
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function prevSlide() {
        if (!slides.length) return;
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    }

    setInterval(nextSlide, 5000);
</script>
