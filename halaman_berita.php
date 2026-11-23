<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/agenda_fungsi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "<div class='bg-white rounded-3xl p-8 text-center text-slate-500'><h4>Warta tidak ditemukan.</h4><a href='index.php?halaman=berita' class='mt-3 inline-block px-4 py-2 bg-brand-800 text-white rounded-xl text-xs font-bold'>Kembali ke Daftar Berita</a></div>";
    return;
}

$stmt = mysqli_prepare($koneksi, "SELECT * FROM agenda_b WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$berita = mysqli_fetch_assoc($res);

if (!$berita) {
    echo "<div class='bg-white rounded-3xl p-8 text-center text-slate-500'><h4>Warta tidak ditemukan.</h4><a href='index.php?halaman=berita' class='mt-3 inline-block px-4 py-2 bg-brand-800 text-white rounded-xl text-xs font-bold'>Kembali ke Daftar Berita</a></div>";
    return;
}

$judul     = $berita['judul'];
$gambar    = !empty($berita['gambar']) ? $berita['gambar'] : 'diskeb.png';
$tanggal   = date('d F Y', strtotime($berita['tanggal']));
$file_pdf  = $berita['file_pdf'];
?>

<div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs text-slate-400">
        <a href="index.php" class="hover:text-slate-600 transition">Beranda</a>
        <i class="bi bi-chevron-right text-[10px]"></i>
        <a href="index.php?halaman=berita" class="hover:text-slate-600 transition">Berita</a>
        <i class="bi bi-chevron-right text-[10px]"></i>
        <span class="text-slate-600 font-semibold truncate max-w-xs"><?= htmlspecialchars($judul) ?></span>
    </nav>

    <div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
            <?= htmlspecialchars($judul) ?>
        </h2>
        <div class="flex items-center space-x-4 mt-3 text-xs text-slate-400 pb-4 border-b border-slate-100">
            <span class="flex items-center space-x-1.5"><i class="bi bi-calendar-event text-brand-600"></i><span><?= $tanggal ?></span></span>
            <span class="flex items-center space-x-1.5"><i class="bi bi-building text-brand-600"></i><span>Dinas Kebudayaan Kota Ternate</span></span>
        </div>
    </div>

    <!-- Foto Berita -->
    <div class="rounded-2xl overflow-hidden shadow-sm bg-slate-100 max-h-[460px]">
        <img src="asset/img/<?= htmlspecialchars($gambar) ?>" alt="<?= htmlspecialchars($judul) ?>" class="w-full h-full object-cover" onerror="this.src='asset/gmbr/diskeb.png';">
    </div>

    <!-- Lampiran PDF -->
    <?php if (!empty($file_pdf)): ?>
        <div class="rounded-2xl p-5 bg-orange-50/60 border border-brand-200/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-brand-800 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                </div>
                <div>
                    <h5 class="font-bold text-slate-900 text-sm">Dokumen Publikasi Resmi (PDF)</h5>
                    <p class="text-xs text-slate-500 font-mono truncate max-w-xs"><?= htmlspecialchars($file_pdf) ?></p>
                </div>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <a href="asset/file/<?= htmlspecialchars($file_pdf) ?>" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-bold text-brand-800 bg-white border border-brand-200 hover:bg-brand-50 transition shadow-sm">
                    <i class="bi bi-eye mr-1"></i> Baca
                </a>
                <a href="asset/file/<?= htmlspecialchars($file_pdf) ?>" download class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-brand-800 hover:bg-brand-900 transition shadow-sm">
                    <i class="bi bi-download mr-1"></i> Unduh
                </a>
            </div>
        </div>

        <div class="rounded-2xl overflow-hidden shadow-inner border border-slate-200 h-[600px] bg-slate-50">
            <iframe src="asset/file/<?= htmlspecialchars($file_pdf) ?>#toolbar=0" title="Viewer Dokumen" class="w-full h-full border-none"></iframe>
        </div>
    <?php endif; ?>

    <!-- Share & Print Actions -->
    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <a href="index.php?halaman=berita" class="text-xs font-bold text-slate-600 hover:text-brand-800 transition flex items-center">
            <i class="bi bi-arrow-left mr-1.5"></i> Kembali ke Daftar Warta
        </a>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition" title="Cetak Berita">
                <i class="bi bi-printer-fill mr-1.5"></i> Cetak
            </button>
            <button onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan warta berhasil disalin ke papan klip!');" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition" title="Salin Tautan">
                <i class="bi bi-link-45deg mr-1.5"></i> Salin Link
            </button>
            <a href="https://api.whatsapp.com/send?text=<?= urlencode($judul . ' - Portal Resmi Dinas Kebudayaan Kota Ternate: ') ?>&url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center text-xs transition shadow-sm" title="Bagikan ke WhatsApp">
                <i class="bi bi-whatsapp"></i>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" class="w-8 h-8 rounded-lg bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center text-xs transition shadow-sm" title="Bagikan ke Facebook">
                <i class="bi bi-facebook"></i>
            </a>
        </div>
    </div>
</div>
