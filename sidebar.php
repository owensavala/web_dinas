<?php
// Sidebar Widget - Tailwind CSS Edition
require_once __DIR__ . '/koneksi.php';

// Ambil data kepala dinas
$sql_kepala = "SELECT * FROM kepala LIMIT 1";
$q_kepala   = mysqli_query($koneksi, $sql_kepala);
$data_kepala = mysqli_fetch_assoc($q_kepala);

// Logika jam buka pelayanan dinas (Senin - Jumat 08.00 - 16.30 WIT)
// Server timezone adjustment jika perlu
$day_of_week = (int)date('N'); // 1 = Senin, 7 = Minggu
$current_hour = (int)date('G');
$is_open = ($day_of_week >= 1 && $day_of_week <= 5 && $current_hour >= 8 && $current_hour < 17);
?>

<div class="space-y-6">
    <!-- Widget Pimpinan Dinas - Cream Mocha -->
    <?php if ($data_kepala): ?>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#E9DCCB] relative overflow-hidden group hover:shadow-md transition">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-[#E9DCCB]/60 to-transparent rounded-bl-full pointer-events-none"></div>
        
        <div class="flex items-center space-x-2 pb-4 mb-4 border-b border-[#E9DCCB]">
            <span class="w-2 h-2 rounded-full bg-[#A8643A]"></span>
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C7B6B]">Pimpinan Instansi</h3>
        </div>

        <div class="text-center">
            <div class="relative inline-block mx-auto mb-4">
                <div class="w-36 h-44 rounded-xl overflow-hidden shadow-md ring-4 ring-[#E9DCCB] group-hover:ring-[#A8643A]/40 transition duration-300">
                    <img src="asset/gmbr/<?= htmlspecialchars($data_kepala['foto']) ?>" 
                         alt="<?= htmlspecialchars($data_kepala['nama']) ?>" 
                         class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                </div>
                <span class="absolute -bottom-2.5 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full text-[11px] font-bold bg-[#A8643A] text-white shadow uppercase tracking-wider whitespace-nowrap">
                    <?= htmlspecialchars($data_kepala['jenis_kepala']) ?>
                </span>
            </div>

            <h4 class="text-base font-bold text-[#2B1D14] mt-3 leading-snug">
                <?= htmlspecialchars($data_kepala['nama']) ?>
            </h4>
            <p class="text-xs text-[#8C7B6B] mt-1">Dinas Kebudayaan Kota Ternate</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Widget Status Layanan Hari Ini -->
    <div class="bg-[#2B1D14] rounded-2xl p-5 text-[#E9DCCB] shadow-sm border border-[#522B15] relative overflow-hidden">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-[#E9DCCB]">Status Pelayanan Kantor</span>
            <?php if ($is_open): ?>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span> Pelayanan Buka
                </span>
            <?php else: ?>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-[#A8643A]/20 text-[#E2B895] border border-[#A8643A]/40">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#A8643A] mr-1.5"></span> Di Luar Jam Kerja
                </span>
            <?php endif; ?>
        </div>
        <div class="space-y-1 text-xs text-[#E9DCCB]/90">
            <p class="flex justify-between"><span>Senin - Kamis:</span> <strong class="text-white">08:00 - 16:30 WIT</strong></p>
            <p class="flex justify-between"><span>Jumat:</span> <strong class="text-white">08:00 - 16:30 WIT</strong></p>
            <p class="flex justify-between text-[#8C7B6B]"><span>Sabtu - Minggu:</span> <span class="italic text-[#8C7B6B]">Tutup / Libur</span></p>
        </div>
    </div>

    <!-- Widget Navigasi Cepat -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#E9DCCB]">
        <div class="flex items-center space-x-2 pb-3 mb-3 border-b border-[#E9DCCB]">
            <span class="w-2 h-2 rounded-full bg-[#A8643A]"></span>
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C7B6B]">Layanan Informasi Cepat</h3>
        </div>
        <div class="space-y-2">
            <a href="index.php?halaman=berita" class="flex items-center justify-between p-3 rounded-xl hover:bg-[#F6EFE6] text-[#2B1D14] hover:text-[#A8643A] transition group border border-transparent hover:border-[#E9DCCB]">
                <span class="flex items-center space-x-3 text-sm font-semibold">
                    <span class="w-8 h-8 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center text-sm group-hover:scale-110 transition-transform"><i class="bi bi-newspaper"></i></span>
                    <span>Warta & Agenda Budaya</span>
                </span>
                <i class="bi bi-arrow-right text-xs text-[#8C7B6B] group-hover:translate-x-1 transition-transform"></i>
            </a>

            <a href="index.php?halaman=unduhan" class="flex items-center justify-between p-3 rounded-xl hover:bg-[#F6EFE6] text-[#2B1D14] hover:text-[#A8643A] transition group border border-transparent hover:border-[#E9DCCB]">
                <span class="flex items-center space-x-3 text-sm font-semibold">
                    <span class="w-8 h-8 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center text-sm group-hover:scale-110 transition-transform"><i class="bi bi-file-earmark-pdf"></i></span>
                    <span>Pusat Unduhan Dokumen</span>
                </span>
                <i class="bi bi-arrow-right text-xs text-[#8C7B6B] group-hover:translate-x-1 transition-transform"></i>
            </a>

            <a href="index.php?halaman=foto" class="flex items-center justify-between p-3 rounded-xl hover:bg-[#F6EFE6] text-[#2B1D14] hover:text-[#A8643A] transition group border border-transparent hover:border-[#E9DCCB]">
                <span class="flex items-center space-x-3 text-sm font-semibold">
                    <span class="w-8 h-8 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center text-sm group-hover:scale-110 transition-transform"><i class="bi bi-images"></i></span>
                    <span>Dokumentasi Budaya</span>
                </span>
                <i class="bi bi-arrow-right text-xs text-[#8C7B6B] group-hover:translate-x-1 transition-transform"></i>
            </a>

            <a href="index.php?halaman=kontak" class="flex items-center justify-between p-3 rounded-xl hover:bg-[#F6EFE6] text-[#2B1D14] hover:text-[#A8643A] transition group border border-transparent hover:border-[#E9DCCB]">
                <span class="flex items-center space-x-3 text-sm font-semibold">
                    <span class="w-8 h-8 rounded-lg bg-[#E9DCCB]/60 text-[#A8643A] flex items-center justify-center text-sm group-hover:scale-110 transition-transform"><i class="bi bi-chat-square-dots-fill"></i></span>
                    <span>Pengaduan Masyarakat</span>
                </span>
                <i class="bi bi-arrow-right text-xs text-[#8C7B6B] group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Widget Peta Lokasi Kantor -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#E9DCCB]">
        <div class="flex items-center space-x-2 pb-3 mb-3 border-b border-[#E9DCCB]">
            <span class="w-2 h-2 rounded-full bg-[#A8643A]"></span>
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C7B6B]">Lokasi Kantor Dinas</h3>
        </div>
        <div class="rounded-xl overflow-hidden shadow-inner border border-[#E9DCCB]">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.435825579421!2d127.3839724740361!3d0.7933727631137408!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x329cb1d8e26b478f%3A0xf99c4423bab7a2e!2sDinas%20Kebudayaan%20Kota%20Ternate!5e0!3m2!1sid!2sid!4v1705699554592!5m2!1sid!2sid" 
                    width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <p class="text-xs text-[#8C7B6B] mt-3 flex items-start space-x-2">
            <i class="bi bi-geo-alt-fill text-[#A8643A] shrink-0 mt-0.5"></i>
            <span>Gamalama, Kec. Ternate Tengah, Kota Ternate, Maluku Utara</span>
        </p>
    </div>
</div>
