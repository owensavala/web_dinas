<?php
// Header Dinas Kebudayaan Kota Ternate - Enterprise Government Edition
if (!isset($page_title)) {
    $page_title = "Dinas Kebudayaan Kota Ternate - Website Resmi";
}
$current_page = $_GET['halaman'] ?? '';
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    
    <!-- Meta SEO & Open Graph -->
    <meta name="description" content="Portal Resmi Dinas Kebudayaan Pemerintah Kota Ternate. Pelestarian cagar budaya, sejarah jalur rempah, adat se atoran Kesultanan Ternate, dan warta kegiatan kebudayaan.">
    <meta name="keywords" content="Dinas Kebudayaan Kota Ternate, Ternate Kota Pusaka, Benteng Kalamata, Kedaton Ternate, Jalur Rempah, Festival Ela-Ela, Maluku Utara">
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="Portal Resmi Dinas Kebudayaan Pemerintah Kota Ternate.">
    <meta property="og:image" content="https://tse4.mm.bing.net/th?id=OIP.kN9Rodvz86zqoyN7QrYdagHaI0&pid=Api&P=0&h=220">
    <meta property="og:type" content="website">
    <link rel="icon" type="image/png" href="https://tse4.mm.bing.net/th?id=OIP.kN9Rodvz86zqoyN7QrYdagHaI0&pid=Api&P=0&h=220">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#0b2545">

    <!-- Schema.org Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "GovernmentOrganization",
      "name": "Dinas Kebudayaan Kota Ternate",
      "alternateName": "Disbud Kota Ternate",
      "url": "http://localhost/web_dinas/",
      "logo": "https://tse4.mm.bing.net/th?id=OIP.kN9Rodvz86zqoyN7QrYdagHaI0&pid=Api&P=0&h=220",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Jl. Merdeka No. 1",
        "addressLocality": "Kota Ternate",
        "addressRegion": "Maluku Utara",
        "postalCode": "97716",
        "addressCountry": "ID"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+62-921-3121111",
        "contactType": "customer service",
        "email": "kebudayaan@ternatekota.go.id"
      }
    }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN + Plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <script src="asset/js/tailwind-config.js"></script>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Print & Custom Styling -->
    <style>
        :root {
            --mocha-latar: #F6EFE6;
            --mocha-permukaan: #E9DCCB;
            --mocha-teks: #2B1D14;
            --mocha-aksen: #A8643A;
            --mocha-redup: #8C7B6B;
        }
        body {
            background-color: #F6EFE6 !important;
            color: #2B1D14 !important;
        }
        @media print {
            header, nav, footer, #searchModal, #lightboxModal, .no-print, button, form {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-size: 12pt !important;
            }
            .print-only {
                display: block !important;
            }
            .max-w-7xl {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }
        .print-only {
            display: none;
        }
    </style>

    <!-- App Enhanced Script -->
    <script src="asset/js/app-enhanced.js" defer></script>
</head>
<body class="bg-[#F6EFE6] text-[#2B1D14] font-sans antialiased selection:bg-[#A8643A] selection:text-white min-h-screen flex flex-col transition-colors duration-300">

    <!-- Official Kop Surat for Printing -->
    <div class="print-only mb-6 border-b-2 border-[#2B1D14] pb-4 text-center">
        <div class="flex items-center justify-center space-x-4">
            <img src="asset/gmbr/1.png" class="w-16 h-16 object-contain" alt="Logo Pemkot Ternate" onerror="this.src='asset/gmbr/diskeb.png';">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-widest text-[#8C7B6B]">Pemerintah Kota Ternate</h3>
                <h1 class="text-xl font-extrabold uppercase tracking-tight text-[#2B1D14]">Dinas Kebudayaan</h1>
                <p class="text-xs text-[#8C7B6B]">Jl. Merdeka No. 1, Kota Ternate, Maluku Utara | kebudayaan@ternatekota.go.id</p>
            </div>
        </div>
    </div>

    <!-- Topbar Info Resmi - Cream Mocha -->
    <header class="bg-[#2B1D14] border-b border-[#522B15] text-[#E9DCCB] text-xs hidden lg:block relative z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
            <div class="flex justify-between items-center text-[11px]">
                <div class="flex items-center space-x-4 xl:space-x-6">
                    <span class="hidden xl:flex items-center space-x-1.5 whitespace-nowrap">
                        <i class="bi bi-geo-alt-fill text-[#A8643A]"></i>
                        <span>Jl. Merdeka No. 1, Kota Ternate, Maluku Utara</span>
                    </span>
                    <span class="flex items-center space-x-1.5 whitespace-nowrap">
                        <i class="bi bi-envelope-fill text-[#A8643A]"></i>
                        <span>kebudayaan@ternatekota.go.id</span>
                    </span>
                    <span class="flex items-center space-x-1.5 whitespace-nowrap">
                        <i class="bi bi-clock-fill text-[#A8643A]"></i>
                        <span>Senin - Jumat: 08:00 - 16:30 WIT</span>
                    </span>
                </div>
                <div class="flex items-center space-x-3 xl:space-x-4">
                    <!-- Quick Accessibility Buttons -->
                    <div class="flex items-center space-x-1.5 border-r border-[#522B15] pr-3 text-[11px] whitespace-nowrap">
                        <button onclick="adjustFontSize(-1)" class="px-1.5 py-0.5 rounded hover:bg-[#38271E] text-[#E9DCCB]/80 hover:text-white" title="Perkecil Font">A-</button>
                        <button onclick="adjustFontSize(0)" class="px-1.5 py-0.5 rounded hover:bg-[#38271E] text-[#E9DCCB]/80 hover:text-white font-bold" title="Reset Font">A</button>
                        <button onclick="adjustFontSize(1)" class="px-1.5 py-0.5 rounded hover:bg-[#38271E] text-[#E9DCCB]/80 hover:text-white font-bold text-xs" title="Perbesar Font">A+</button>
                        <button onclick="toggleDarkMode()" class="ml-1.5 px-2 py-0.5 rounded-full bg-[#38271E] hover:bg-[#522B15] text-[#A8643A] hover:text-[#E2B895] transition" title="Ganti Mode Gelap / Terang">
                            <i class="bi bi-moon-stars-fill"></i>
                        </button>
                    </div>

                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] xl:text-[11px] font-semibold bg-[#38271E] text-[#E9DCCB] border border-[#522B15] whitespace-nowrap">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                        Portal Resmi Pemerintah
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Navigation Bar - Ultra-Polished Responsive Desktop Edition -->
    <nav class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#E9DCCB] shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-[66px]">
                <!-- Brand / Logo -->
                <a href="index.php" class="flex items-center space-x-2.5 xl:space-x-3 shrink-0 group">
                    <div class="relative w-9 h-9 xl:w-10 xl:h-10 flex items-center justify-center">
                        <img src="https://tse4.mm.bing.net/th?id=OIP.kN9Rodvz86zqoyN7QrYdagHaI0&pid=Api&P=0&h=220" alt="Logo Pemkot Ternate" class="w-9 h-9 xl:w-10 xl:h-10 object-contain drop-shadow group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] xl:text-[9.5px] font-bold tracking-widest text-[#8C7B6B] uppercase leading-none">Pemerintah Kota Ternate</span>
                        <h1 class="text-sm xl:text-base font-black tracking-tight text-[#2B1D14] leading-tight group-hover:text-[#A8643A] transition">
                            DINAS <span class="text-[#A8643A]">KEBUDAYAAN</span>
                        </h1>
                    </div>
                </a>

                <!-- Desktop Menu (Single-line, gracefully scaled, zero-wrap, zero-shift) -->
                <div class="hidden lg:flex items-center space-x-0.5 xl:space-x-1 shrink-0">
                    <!-- Beranda -->
                    <a href="index.php" class="inline-flex items-center px-2 xl:px-3 py-1.5 rounded-full text-xs xl:text-[13px] transition whitespace-nowrap <?= empty($current_page) ? 'text-[#A8643A] bg-[#E9DCCB]/60 font-bold border border-[#A8643A]/20 shadow-xs' : 'text-[#2B1D14]/80 hover:text-[#A8643A] hover:bg-[#E9DCCB]/30 border border-transparent font-medium' ?>">
                        <span>Beranda</span>
                    </a>

                    <!-- Dropdown Profil -->
                    <div class="relative group" id="profilDropdown">
                        <button type="button" class="inline-flex items-center px-2 xl:px-3 py-1.5 rounded-full text-xs xl:text-[13px] transition whitespace-nowrap <?= in_array($current_page, ['visi-misi', 'sejarah', 'struktur-organisasi', 'pejabat']) ? 'text-[#A8643A] bg-[#E9DCCB]/60 font-bold border border-[#A8643A]/20 shadow-xs' : 'text-[#2B1D14]/80 hover:text-[#A8643A] hover:bg-[#E9DCCB]/30 border border-transparent font-medium' ?>">
                            <span>Profil</span>
                            <i class="bi bi-chevron-down text-[9px] ml-1 opacity-70 transition-transform duration-200 group-hover:rotate-180"></i>
                        </button>
                        <div class="absolute left-0 top-full pt-1.5 hidden group-hover:block z-50">
                            <div class="w-56 rounded-2xl bg-white shadow-2xl border border-[#E9DCCB] p-2 space-y-0.5" style="background-color: #ffffff !important;">
                                <a href="index.php?halaman=visi-misi" class="flex items-center px-3 py-2 rounded-xl text-xs text-[#2B1D14] hover:bg-[#F6EFE6] hover:text-[#A8643A] transition font-medium">
                                    <span class="w-6 h-6 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center mr-2 text-xs"><i class="bi bi-bullseye"></i></span>
                                    <span>Visi & Misi</span>
                                </a>
                                <a href="index.php?halaman=sejarah" class="flex items-center px-3 py-2 rounded-xl text-xs text-[#2B1D14] hover:bg-[#F6EFE6] hover:text-[#A8643A] transition font-medium">
                                    <span class="w-6 h-6 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center mr-2 text-xs"><i class="bi bi-hourglass-split"></i></span>
                                    <span>Sejarah Kebudayaan</span>
                                </a>
                                <a href="index.php?halaman=struktur-organisasi" class="flex items-center px-3 py-2 rounded-xl text-xs text-[#2B1D14] hover:bg-[#F6EFE6] hover:text-[#A8643A] transition font-medium">
                                    <span class="w-6 h-6 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center mr-2 text-xs"><i class="bi bi-diagram-3-fill"></i></span>
                                    <span>Struktur Organisasi</span>
                                </a>
                                <a href="index.php?halaman=pejabat" class="flex items-center px-3 py-2 rounded-xl text-xs text-[#2B1D14] hover:bg-[#F6EFE6] hover:text-[#A8643A] transition font-medium">
                                    <span class="w-6 h-6 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center mr-2 text-xs"><i class="bi bi-people-fill"></i></span>
                                    <span>Pejabat Struktural</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Cagar Budaya -->
                    <a href="index.php?halaman=cagar-budaya" class="inline-flex items-center px-2 xl:px-3 py-1.5 rounded-full text-xs xl:text-[13px] transition whitespace-nowrap <?= $current_page == 'cagar-budaya' ? 'text-[#A8643A] bg-[#E9DCCB]/60 font-bold border border-[#A8643A]/20 shadow-xs' : 'text-[#2B1D14]/80 hover:text-[#A8643A] hover:bg-[#E9DCCB]/30 border border-transparent font-medium' ?>">
                        <span>Cagar Budaya</span>
                    </a>

                    <!-- Dropdown Layanan & Budaya Unggulan -->
                    <div class="relative group" id="layananBudayaDropdown">
                        <button type="button" class="inline-flex items-center px-2 xl:px-3 py-1.5 rounded-full text-xs xl:text-[13px] transition whitespace-nowrap <?= in_array($current_page, ['peta-budaya', 'kalender', 'sanggar', 'glosarium', 'survei']) ? 'text-[#A8643A] bg-[#E9DCCB]/60 font-bold border border-[#A8643A]/20 shadow-xs' : 'text-[#2B1D14]/80 hover:text-[#A8643A] hover:bg-[#E9DCCB]/30 border border-transparent font-medium' ?>">
                            <span class="xl:hidden">Layanan</span>
                            <span class="hidden xl:inline">Layanan & Budaya</span>
                            <i class="bi bi-chevron-down text-[9px] ml-1 opacity-70 transition-transform duration-200 group-hover:rotate-180"></i>
                        </button>
                        <div class="absolute left-0 top-full pt-1.5 hidden group-hover:block z-50">
                            <div class="w-64 rounded-2xl bg-white shadow-2xl border border-[#E9DCCB] p-2 space-y-0.5" style="background-color: #ffffff !important;">
                                <a href="index.php?halaman=peta-budaya" class="flex items-center px-3 py-2 rounded-xl text-xs text-[#2B1D14] hover:bg-[#F6EFE6] hover:text-[#A8643A] transition font-medium">
                                    <span class="w-7 h-7 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center mr-2.5 text-xs shrink-0"><i class="bi bi-geo-alt-fill"></i></span>
                                    <div>
                                        <div class="font-bold leading-tight">Peta Cagar Budaya (GIS)</div>
                                        <div class="text-[10px] text-[#8C7B6B]">WebGIS Sebaran Situs Sejarah</div>
                                    </div>
                                </a>
                                <a href="index.php?halaman=kalender" class="flex items-center px-3 py-2 rounded-xl text-xs text-[#2B1D14] hover:bg-[#F6EFE6] hover:text-[#A8643A] transition font-medium">
                                    <span class="w-7 h-7 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center mr-2.5 text-xs shrink-0"><i class="bi bi-calendar-event-fill"></i></span>
                                    <div>
                                        <div class="font-bold leading-tight">Kalender Festival & Event</div>
                                        <div class="text-[10px] text-[#8C7B6B]">Jadwal Perayaan & Countdown</div>
                                    </div>
                                </a>
                                <a href="index.php?halaman=sanggar" class="flex items-center px-3 py-2 rounded-xl text-xs text-[#2B1D14] hover:bg-[#F6EFE6] hover:text-[#A8643A] transition font-medium">
                                    <span class="w-7 h-7 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center mr-2.5 text-xs shrink-0"><i class="bi bi-people-fill"></i></span>
                                    <div>
                                        <div class="font-bold leading-tight">Fasilitasi Sanggar Seni</div>
                                        <div class="text-[10px] text-[#8C7B6B]">Direktori & Pendaftaran Komunitas</div>
                                    </div>
                                </a>
                                <a href="index.php?halaman=glosarium" class="flex items-center px-3 py-2 rounded-xl text-xs text-[#2B1D14] hover:bg-[#F6EFE6] hover:text-[#A8643A] transition font-medium">
                                    <span class="w-7 h-7 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center mr-2.5 text-xs shrink-0"><i class="bi bi-book-half"></i></span>
                                    <div>
                                        <div class="font-bold leading-tight">Glosarium Bahasa Ternate</div>
                                        <div class="text-[10px] text-[#8C7B6B]">Kamus Adat Se Atorang</div>
                                    </div>
                                </a>
                                <a href="index.php?halaman=survei" class="flex items-center px-3 py-2 rounded-xl text-xs text-[#2B1D14] hover:bg-[#F6EFE6] hover:text-[#A8643A] transition font-medium">
                                    <span class="w-7 h-7 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center mr-2.5 text-xs shrink-0"><i class="bi bi-star-fill"></i></span>
                                    <div>
                                        <div class="font-bold leading-tight">Survei Kepuasan (IKM)</div>
                                        <div class="text-[10px] text-[#8C7B6B]">Evaluasi Pelayanan SPBE</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Warta & Agenda -->
                    <a href="index.php?halaman=berita" class="inline-flex items-center px-2 xl:px-3 py-1.5 rounded-full text-xs xl:text-[13px] transition whitespace-nowrap <?= in_array($current_page, ['berita', 'halaman_berita']) ? 'text-[#A8643A] bg-[#E9DCCB]/60 font-bold border border-[#A8643A]/20 shadow-xs' : 'text-[#2B1D14]/80 hover:text-[#A8643A] hover:bg-[#E9DCCB]/30 border border-transparent font-medium' ?>">
                        <span class="xl:hidden">Warta</span>
                        <span class="hidden xl:inline">Warta & Agenda</span>
                    </a>

                    <!-- Pusat Unduhan -->
                    <a href="index.php?halaman=unduhan" class="inline-flex items-center px-2 xl:px-3 py-1.5 rounded-full text-xs xl:text-[13px] transition whitespace-nowrap <?= $current_page == 'unduhan' ? 'text-[#A8643A] bg-[#E9DCCB]/60 font-bold border border-[#A8643A]/20 shadow-xs' : 'text-[#2B1D14]/80 hover:text-[#A8643A] hover:bg-[#E9DCCB]/30 border border-transparent font-medium' ?>">
                        <span>Unduhan</span>
                    </a>

                    <!-- Dropdown Galeri -->
                    <div class="relative group" id="galeriDropdown">
                        <button type="button" class="inline-flex items-center px-2 xl:px-3 py-1.5 rounded-full text-xs xl:text-[13px] transition whitespace-nowrap <?= in_array($current_page, ['foto', 'video']) ? 'text-[#A8643A] bg-[#E9DCCB]/60 font-bold border border-[#A8643A]/20 shadow-xs' : 'text-[#2B1D14]/80 hover:text-[#A8643A] hover:bg-[#E9DCCB]/30 border border-transparent font-medium' ?>">
                            <span>Galeri</span>
                            <i class="bi bi-chevron-down text-[9px] ml-1 opacity-70 transition-transform duration-200 group-hover:rotate-180"></i>
                        </button>
                        <div class="absolute left-0 top-full pt-1.5 hidden group-hover:block z-50">
                            <div class="w-52 rounded-2xl bg-white shadow-2xl border border-[#E9DCCB] p-2 space-y-0.5" style="background-color: #ffffff !important;">
                                <a href="index.php?halaman=foto" class="flex items-center px-3 py-2 rounded-xl text-xs text-[#2B1D14] hover:bg-[#F6EFE6] hover:text-[#A8643A] transition font-medium">
                                    <span class="w-6 h-6 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center mr-2 text-xs"><i class="bi bi-images"></i></span>
                                    <span>Foto Dokumentasi</span>
                                </a>
                                <a href="index.php?halaman=video" class="flex items-center px-3 py-2 rounded-xl text-xs text-[#2B1D14] hover:bg-[#F6EFE6] hover:text-[#A8643A] transition font-medium">
                                    <span class="w-6 h-6 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center mr-2 text-xs"><i class="bi bi-play-circle-fill"></i></span>
                                    <span>Video Kegiatan</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Action Cluster: Search & CTA -->
                <div class="hidden lg:flex items-center space-x-1.5 xl:space-x-2 shrink-0">
                    <!-- Quick Search Trigger Button -->
                    <button type="button" onclick="openSearchModal()" class="flex items-center space-x-1.5 px-2 xl:px-2.5 py-1.5 rounded-full text-xs font-medium text-[#8C7B6B] bg-[#E9DCCB]/40 hover:bg-[#E9DCCB]/80 hover:text-[#2B1D14] transition border border-[#E9DCCB] whitespace-nowrap shadow-xs" title="Cari di website (Ctrl+K)">
                        <i class="bi bi-search text-xs text-[#A8643A]"></i>
                        <span class="hidden 2xl:inline text-[11px]">Cari</span>
                        <kbd class="hidden xl:inline px-1 py-0.2 text-[9px] font-mono bg-white border border-[#E9DCCB] rounded text-[#8C7B6B]">Ctrl K</kbd>
                    </button>

                    <!-- CTA Kontak -->
                    <a href="index.php?halaman=kontak" class="inline-flex items-center space-x-1.5 px-3 xl:px-3.5 py-1.5 rounded-full text-xs font-bold text-white bg-[#A8643A] hover:bg-[#94542E] shadow-sm hover:shadow transition transform hover:-translate-y-0.5 whitespace-nowrap">
                        <i class="bi bi-headset text-xs"></i>
                        <span class="hidden xl:inline">Kontak & Aduan</span>
                        <span class="xl:hidden">Kontak</span>
                    </a>
                </div>

                <!-- Mobile Menu Button & Mobile Search -->
                <div class="lg:hidden flex items-center space-x-2">
                    <button type="button" onclick="openSearchModal()" class="p-2 rounded-lg text-[#2B1D14] hover:bg-[#E9DCCB]/50" title="Cari">
                        <i class="bi bi-search text-xl"></i>
                    </button>
                    <button type="button" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="p-2 rounded-lg text-[#2B1D14] hover:bg-[#E9DCCB]/50" title="Menu">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Container -->
        <div id="mobileMenu" class="hidden lg:hidden border-t border-[#E9DCCB] bg-white px-4 pt-3 pb-6 space-y-1 shadow-lg">
            <a href="index.php" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-[#2B1D14] hover:bg-[#F6EFE6]">Beranda</a>
            <div class="border-t border-[#E9DCCB] pt-2 pb-1">
                <span class="px-3 text-xs font-bold text-[#8C7B6B] uppercase tracking-wider">Profil Dinas</span>
                <a href="index.php?halaman=visi-misi" class="block px-3 py-2 rounded-lg text-xs font-medium text-[#2B1D14] hover:bg-[#F6EFE6]">Visi & Misi</a>
                <a href="index.php?halaman=sejarah" class="block px-3 py-2 rounded-lg text-xs font-medium text-[#2B1D14] hover:bg-[#F6EFE6]">Sejarah Kebudayaan</a>
                <a href="index.php?halaman=struktur-organisasi" class="block px-3 py-2 rounded-lg text-xs font-medium text-[#2B1D14] hover:bg-[#F6EFE6]">Struktur Organisasi</a>
                <a href="index.php?halaman=pejabat" class="block px-3 py-2 rounded-lg text-xs font-medium text-[#2B1D14] hover:bg-[#F6EFE6]">Pejabat Struktural</a>
            </div>
            <a href="index.php?halaman=cagar-budaya" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-[#2B1D14] hover:bg-[#F6EFE6]">Cagar Budaya</a>
            <div class="border-t border-[#E9DCCB] pt-2 pb-1">
                <span class="px-3 text-xs font-bold text-[#8C7B6B] uppercase tracking-wider">Layanan & Budaya</span>
                <a href="index.php?halaman=peta-budaya" class="block px-3 py-2 rounded-lg text-xs font-medium text-[#2B1D14] hover:bg-[#F6EFE6]"><i class="bi bi-geo-alt-fill text-[#A8643A] mr-2"></i> Peta Cagar Budaya (GIS)</a>
                <a href="index.php?halaman=kalender" class="block px-3 py-2 rounded-lg text-xs font-medium text-[#2B1D14] hover:bg-[#F6EFE6]"><i class="bi bi-calendar-event-fill text-[#A8643A] mr-2"></i> Kalender Festival & Event</a>
                <a href="index.php?halaman=sanggar" class="block px-3 py-2 rounded-lg text-xs font-medium text-[#2B1D14] hover:bg-[#F6EFE6]"><i class="bi bi-people-fill text-[#A8643A] mr-2"></i> Fasilitasi Sanggar Seni</a>
                <a href="index.php?halaman=glosarium" class="block px-3 py-2 rounded-lg text-xs font-medium text-[#2B1D14] hover:bg-[#F6EFE6]"><i class="bi bi-book-half text-[#A8643A] mr-2"></i> Glosarium Bahasa Ternate</a>
                <a href="index.php?halaman=survei" class="block px-3 py-2 rounded-lg text-xs font-medium text-[#2B1D14] hover:bg-[#F6EFE6]"><i class="bi bi-star-fill text-[#A8643A] mr-2"></i> Survei Kepuasan (IKM)</a>
            </div>
            <a href="index.php?halaman=berita" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-[#2B1D14] hover:bg-[#F6EFE6]">Warta & Agenda</a>
            <a href="index.php?halaman=unduhan" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-[#2B1D14] hover:bg-[#F6EFE6]">Pusat Unduhan</a>
            <a href="index.php?halaman=foto" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-[#2B1D14] hover:bg-[#F6EFE6]">Galeri Foto</a>
            <a href="index.php?halaman=video" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-[#2B1D14] hover:bg-[#F6EFE6]">Galeri Video</a>
            <a href="index.php?halaman=kontak" class="block text-center mt-3 px-4 py-2.5 rounded-xl font-bold text-white bg-[#A8643A] hover:bg-[#94542E]">Kontak & Aduan</a>
        </div>
    </nav>

    <!-- Running Announcement Bar - Rich Mocha Gradient -->
    <div class="bg-gradient-to-r from-[#2B1D14] via-[#522B15] to-[#A8643A] text-[#F6EFE6] text-xs py-2 overflow-hidden shadow-inner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-bold bg-white/20 text-[#F6EFE6] uppercase tracking-wider text-[10px] mr-3 shrink-0 shadow-sm">
                <i class="bi bi-megaphone-fill mr-1.5 text-[#E9DCCB]"></i> Warta Terkini
            </span>
            <div class="overflow-hidden whitespace-nowrap w-full">
                <marquee behavior="scroll" direction="left" scrollamount="6" class="font-medium text-[#F6EFE6]">
                    Selamat datang di Portal Resmi Dinas Kebudayaan Kota Ternate • Merawat Warisan Budaya, Menjaga Sejarah Jalur Rempah, dan Meneguhkan Identitas Luhur Kesultanan Ternate sebagai Kota Pusaka Dunia.
                </marquee>
            </div>
        </div>
    </div>

    <!-- Quick Search Modal (Ctrl/Cmd + K) -->
    <div id="searchModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-start justify-center pt-20 px-4">
        <div class="bg-white dark:bg-slate-900 w-full max-w-xl rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden" onclick="event.stopPropagation()">
            <form action="index.php" method="GET" class="border-b border-slate-200 dark:border-slate-800 p-4 flex items-center space-x-3">
                <input type="hidden" name="halaman" value="berita">
                <i class="bi bi-search text-brand-700 text-lg"></i>
                <input type="text" id="searchModalInput" name="katakunci" autocomplete="off" placeholder="Ketik kata kunci untuk mencari secara instan..." class="w-full bg-transparent border-0 focus:ring-0 text-slate-900 dark:text-white placeholder-slate-400 text-sm">
                <button type="button" onclick="closeSearchModal()" class="text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded">ESC</button>
            </form>
            
            <!-- Live Results Container -->
            <div id="searchResultsBox" class="hidden max-h-80 overflow-y-auto p-2 divide-y divide-slate-100 dark:divide-slate-800"></div>

            <div class="p-4 space-y-2 text-xs text-slate-500 border-t border-slate-100 dark:border-slate-800">
                <span class="font-bold uppercase tracking-wider text-[10px] text-slate-400 block mb-1">Pencarian Populer</span>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="document.getElementById('searchModalInput').value='Benteng'; document.getElementById('searchModalInput').dispatchEvent(new Event('input'))" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-brand-50 hover:text-brand-700 transition">Benteng Kalamata</button>
                    <button type="button" onclick="document.getElementById('searchModalInput').value='Ela-Ela'; document.getElementById('searchModalInput').dispatchEvent(new Event('input'))" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-brand-50 hover:text-brand-700 transition">Festival Ela-Ela</button>
                    <button type="button" onclick="document.getElementById('searchModalInput').value='Jalur Rempah'; document.getElementById('searchModalInput').dispatchEvent(new Event('input'))" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-brand-50 hover:text-brand-700 transition">Jalur Rempah</button>
                    <button type="button" onclick="document.getElementById('searchModalInput').value='Visi'; document.getElementById('searchModalInput').dispatchEvent(new Event('input'))" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-brand-50 hover:text-brand-700 transition">Visi Misi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Lightbox Modal -->
    <div id="lightboxModal" class="hidden fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex flex-col items-center justify-center p-4">
        <button type="button" onclick="closeLightbox()" class="absolute top-5 right-5 text-white/70 hover:text-white text-3xl font-bold transition">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="max-w-4xl max-h-[85vh] flex flex-col items-center">
            <img id="lightboxImg" src="" alt="Pratinjau Foto" class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl">
            <p id="lightboxCaption" class="text-white text-sm font-semibold mt-3 text-center"></p>
        </div>
    </div>
