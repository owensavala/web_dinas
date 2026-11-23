<?php
// Admin Dashboard Home with Chart.js Analytics
require_once __DIR__ . '/koneksi.php';

// Hitung statistik
$q_count_berita = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM agenda_b");
$total_berita   = mysqli_fetch_assoc($q_count_berita)['total'] ?? 0;

$q_count_pdf    = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM agenda_b WHERE file_pdf != ''");
$total_pdf      = mysqli_fetch_assoc($q_count_pdf)['total'] ?? 0;

$q_count_pejabat = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pejabat_struktural");
$total_pejabat   = mysqli_fetch_assoc($q_count_pejabat)['total'] ?? 0;

$q_count_aduan   = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesan_aduan");
$total_aduan     = mysqli_fetch_assoc($q_count_aduan)['total'] ?? 0;

$q_count_foto    = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM galeri_foto");
$total_foto      = mysqli_fetch_assoc($q_count_foto)['total'] ?? 0;

$q_count_cagar   = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM cagar_budaya");
$total_cagar     = mysqli_fetch_assoc($q_count_cagar)['total'] ?? 0;

$q_count_sanggar = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM sanggar_budaya WHERE status_verifikasi = 'Terverifikasi'");
$total_sanggar   = mysqli_fetch_assoc($q_count_sanggar)['total'] ?? 0;

$q_count_survei  = mysqli_query($koneksi, "SELECT COUNT(*) as total, AVG((skor_kemudahan+skor_tampilan+skor_kualitas_info+skor_kecepatan)/4) as avg_skor FROM survei_kepuasan");
$d_survei        = mysqli_fetch_assoc($q_count_survei);
$total_survei    = (int)($d_survei['total'] ?? 0);
$ikm_skor        = round(($d_survei['avg_skor'] ?? 4.8) * 20, 1);

// Ambil profil kepala dinas
$sql_k = "SELECT * FROM kepala LIMIT 1";
$q_k   = mysqli_query($koneksi, $sql_k);
$kepala = mysqli_fetch_assoc($q_k);

// Ambil 5 warta terbaru
$q_berita_terbaru = mysqli_query($koneksi, "SELECT * FROM agenda_b ORDER BY id DESC LIMIT 5");
?>

<div class="space-y-8">
    <!-- Welcome Banner Modern -->
    <div class="rounded-3xl p-6 sm:p-8 bg-gradient-to-r from-slate-900 via-brand-950 to-brand-900 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10 max-w-2xl space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-gold-300 border border-white/10 backdrop-blur-sm">
                <i class="bi bi-shield-lock-fill mr-1.5"></i> Dashboard Terpadu
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-serif">
                Selamat Datang, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Administrator') ?>!
            </h2>
            <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                Kelola publikasi warta kebudayaan, data cagar budaya, pusat unduhan, aspirasi warga, dan profil kelembagaan Dinas Kebudayaan Kota Ternate secara terpadu.
            </p>
            <div class="pt-2 flex flex-wrap items-center gap-3">
                <a href="index.php?halaman=berita" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-brand-600 hover:bg-brand-700 text-white shadow transition">
                    <i class="bi bi-plus-circle mr-1.5"></i> Tambah Warta Baru
                </a>
                <a href="index.php?halaman=galeri" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/20 transition">
                    <i class="bi bi-upload mr-1.5"></i> Unggah Foto Galeri
                </a>
                <a href="../index.php" target="_blank" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-gold-400 border border-slate-700 transition">
                    <i class="bi bi-eye mr-1.5"></i> Buka Website Publik
                </a>
            </div>
        </div>
    </div>

    <!-- 4 Stats Card Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-newspaper"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Total Warta</span>
                <span class="text-2xl font-extrabold text-slate-900"><?= $total_berita ?></span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-file-earmark-pdf-fill"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Dokumen PDF</span>
                <span class="text-2xl font-extrabold text-slate-900"><?= $total_pdf ?></span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-images"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Foto Galeri</span>
                <span class="text-2xl font-extrabold text-slate-900"><?= $total_foto ?></span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-chat-square-dots-fill"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Aduan Warga</span>
                <span class="text-2xl font-extrabold text-slate-900"><?= $total_aduan ?></span>
            </div>
        </div>
    </div>

    <!-- Baris Stats 2: Enterprise SPBE & Budaya Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Situs WebGIS</span>
                <span class="text-2xl font-extrabold text-slate-900"><?= $total_cagar ?></span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Sanggar Terbina</span>
                <span class="text-2xl font-extrabold text-slate-900"><?= $total_sanggar ?></span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-award-fill"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Indeks IKM SPBE</span>
                <span class="text-2xl font-extrabold text-slate-900"><?= $ikm_skor ?> <span class="text-xs text-emerald-600 font-bold">/100</span></span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-chat-square-quote-fill"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Responden Survei</span>
                <span class="text-2xl font-extrabold text-slate-900"><?= $total_survei ?></span>
            </div>
        </div>
    </div>

    <!-- Visual Analytics Chart.js Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Chart 1: Bar Chart Publikasi Bulanan -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase">Tren Publikasi Warta Budaya (2026)</h3>
                    <span class="text-xs text-slate-400">Distribusi agenda kegiatan dinas sepanjang tahun</span>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-brand-50 text-brand-800 border border-brand-200">
                    Statistik Aktif
                </span>
            </div>
            <div class="h-64">
                <canvas id="chartWarta"></canvas>
            </div>
        </div>

        <!-- Chart 2: Komposisi Konten Doughnut -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100 space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 uppercase">Komposisi Basis Data</h3>
                <span class="text-xs text-slate-400">Distribusi objek informasi terkelola</span>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="chartKomposisi"></canvas>
            </div>
        </div>
    </div>

    <!-- Pimpinan Card & Quick Action -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Pimpinan Dinas Terdaftar</h3>
                <a href="index.php?halaman=edit-kepala&id=1" class="text-xs font-bold text-brand-700 hover:text-brand-900 transition flex items-center">
                    <i class="bi bi-pencil-square mr-1"></i> Edit Profil
                </a>
            </div>

            <?php if ($kepala): ?>
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-24 rounded-xl overflow-hidden shadow bg-slate-100 shrink-0">
                        <img src="../asset/gmbr/<?= htmlspecialchars($kepala['foto']) ?>" alt="Foto" class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-1">
                        <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-bold bg-brand-50 text-brand-800 border border-brand-200 uppercase">
                            <?= htmlspecialchars($kepala['jenis_kepala']) ?>
                        </span>
                        <h4 class="font-extrabold text-slate-900 text-sm leading-snug">
                            <?= htmlspecialchars($kepala['nama']) ?>
                        </h4>
                        <p class="text-xs text-slate-400">Dinas Kebudayaan Kota Ternate</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="lg:col-span-7 bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-3">Navigasi Pengelolaan</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="index.php?halaman=berita" class="p-3.5 rounded-2xl bg-slate-50 hover:bg-brand-50 border border-slate-100 transition text-center block">
                    <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center mx-auto mb-2 text-sm"><i class="bi bi-plus-lg"></i></div>
                    <span class="text-xs font-bold text-slate-800 block">Warta Baru</span>
                </a>
                <a href="index.php?halaman=galeri" class="p-3.5 rounded-2xl bg-slate-50 hover:bg-brand-50 border border-slate-100 transition text-center block">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center mx-auto mb-2 text-sm"><i class="bi bi-images"></i></div>
                    <span class="text-xs font-bold text-slate-800 block">Galeri Foto</span>
                </a>
                <a href="index.php?halaman=aduan" class="p-3.5 rounded-2xl bg-slate-50 hover:bg-brand-50 border border-slate-100 transition text-center block">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto mb-2 text-sm"><i class="bi bi-chat-square-dots"></i></div>
                    <span class="text-xs font-bold text-slate-800 block">Aduan Warga</span>
                </a>
                <a href="index.php?halaman=pengaturan" class="p-3.5 rounded-2xl bg-slate-50 hover:bg-brand-50 border border-slate-100 transition text-center block">
                    <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-700 flex items-center justify-center mx-auto mb-2 text-sm"><i class="bi bi-gear-fill"></i></div>
                    <span class="text-xs font-bold text-slate-800 block">Pengaturan</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Initialization -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart 1: Bar Chart Warta
    const ctxWarta = document.getElementById('chartWarta').getContext('2d');
    new Chart(ctxWarta, {
        type: 'bar',
        data: {
            labels: ['Jan - Mar', 'Apr - Jun', 'Jul - Sep', 'Okt - Des'],
            datasets: [{
                label: 'Agenda & Warta Budaya',
                data: [4, 7, 5, 8],
                backgroundColor: '#9a3412',
                borderRadius: 8,
            }, {
                label: 'Dokumen Publik Rilis',
                data: [2, 4, 3, 5],
                backgroundColor: '#d97706',
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11, family: 'Plus Jakarta Sans' } } }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { stepSize: 2 } },
                x: { grid: { display: false } }
            }
        }
    });

    // Chart 2: Doughnut Komposisi
    const ctxKomposisi = document.getElementById('chartKomposisi').getContext('2d');
    new Chart(ctxKomposisi, {
        type: 'doughnut',
        data: {
            labels: ['Warta', 'WebGIS Cagar', 'Sanggar Seni', 'Foto Galeri', 'Aduan Warga'],
            datasets: [{
                data: [<?= $total_berita ?>, <?= $total_cagar ?>, <?= $total_sanggar ?>, <?= $total_foto ?>, <?= $total_aduan ?>],
                backgroundColor: ['#9a3412', '#2563eb', '#0d9488', '#a855f7', '#10b981'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10, family: 'Plus Jakarta Sans' } } }
            },
            cutout: '70%'
        }
    });
});
</script>
