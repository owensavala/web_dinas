<?php
$page_title = "404 - Halaman Tidak Ditemukan | Dinas Kebudayaan Kota Ternate";
http_response_code(404);
include_once __DIR__ . '/header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-16 sm:py-24 text-center">
    <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-brand-50 dark:bg-brand-950/60 text-brand-800 dark:text-brand-400 mb-6 border border-brand-200/60 dark:border-brand-800 shadow-inner">
        <i class="bi bi-compass text-5xl animate-spin" style="animation-duration: 10s;"></i>
    </div>
    
    <span class="block text-xs font-bold uppercase tracking-widest text-brand-600 dark:text-brand-400 mb-2">Galat 404 - Not Found</span>
    <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight">Halaman Tidak Ditemukan</h1>
    
    <p class="mt-4 text-base text-slate-600 dark:text-slate-400 max-w-lg mx-auto leading-relaxed">
        Mohon maaf, halaman atau berkas yang Anda cari tidak dapat ditemukan atau telah dipindahkan ke alamat baru.
    </p>

    <!-- Quick Actions -->
    <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
        <a href="index.php" class="inline-flex items-center px-6 py-3 rounded-2xl text-xs font-bold uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow-md transition transform hover:-translate-y-0.5">
            <i class="bi bi-house-door-fill mr-2"></i> Kembali ke Beranda
        </a>
        <a href="index.php?halaman=berita" class="inline-flex items-center px-6 py-3 rounded-2xl text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm">
            <i class="bi bi-newspaper mr-2"></i> Jelajahi Warta
        </a>
        <a href="index.php?halaman=kontak" class="inline-flex items-center px-6 py-3 rounded-2xl text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm">
            <i class="bi bi-envelope-fill mr-2"></i> Hubungi Kami
        </a>
    </div>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>
