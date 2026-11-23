<?php
require_once __DIR__ . '/koneksi.php';

$kat = $_GET['kat'] ?? 'Semua';
$where = "";
if ($kat !== 'Semua') {
    $where = "WHERE kategori = '" . mysqli_real_escape_string($koneksi, $kat) . "'";
}

$sql_foto = "SELECT * FROM galeri_foto $where ORDER BY id DESC";
$q_foto = mysqli_query($koneksi, $sql_foto);

$categories = ['Semua', 'Festival', 'Kesenian', 'Sejarah', 'Kegiatan'];
?>

<div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800 space-y-6">
    <div class="border-b border-slate-100 dark:border-slate-800 pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-700 dark:text-brand-400">Dokumentasi Visual</span>
            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Galeri Foto Kebudayaan</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Dokumentasi kegiatan dan pelestarian cagar budaya di Kota Ternate.</p>
        </div>

        <!-- Filter Kategori Tabs -->
        <div class="flex flex-wrap gap-1.5 p-1 bg-slate-100 dark:bg-slate-800 rounded-xl">
            <?php foreach ($categories as $c): ?>
                <a href="index.php?halaman=foto&kat=<?= urlencode($c) ?>" class="px-3 py-1 rounded-lg text-xs font-semibold transition <?= ($kat === $c) ? 'bg-white dark:bg-slate-900 text-brand-800 dark:text-brand-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' ?>">
                    <?= htmlspecialchars($c) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Grid Foto dengan Lightbox Pop-up -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php if (mysqli_num_rows($q_foto) > 0): ?>
            <?php while ($f = mysqli_fetch_assoc($q_foto)): ?>
                <?php
                $img_path = "asset/gmbr/" . $f['gambar'];
                if (!file_exists($img_path)) {
                    $img_path = "asset/img/" . $f['gambar'];
                }
                ?>
                <div class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-card-hover transition group cursor-pointer" onclick="openLightbox('<?= htmlspecialchars($img_path) ?>', '<?= htmlspecialchars($f['judul']) ?>')">
                    <div class="h-48 overflow-hidden bg-slate-100 dark:bg-slate-800 relative">
                        <img src="<?= htmlspecialchars($img_path) ?>" alt="<?= htmlspecialchars($f['judul']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='asset/gmbr/diskeb.png';">
                        <span class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-black/60 text-white flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition shadow">
                            <i class="bi bi-arrows-fullscreen"></i>
                        </span>
                        <span class="absolute bottom-2.5 left-2.5 px-2 py-0.5 rounded text-[10px] font-bold bg-brand-900/80 text-white backdrop-blur-sm">
                            <?= htmlspecialchars($f['kategori']) ?>
                        </span>
                    </div>
                    <div class="p-4 space-y-1">
                        <span class="text-[10px] text-slate-400 block"><i class="bi bi-calendar3 mr-1 text-brand-600"></i> <?= date('d M Y', strtotime($f['tanggal'])) ?></span>
                        <h5 class="text-xs font-bold text-slate-900 dark:text-white leading-snug line-clamp-2"><?= htmlspecialchars($f['judul']) ?></h5>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-3 py-12 text-center text-slate-400">
                <i class="bi bi-images text-3xl mb-2 block"></i>
                <p class="text-sm">Belum ada foto dalam kategori ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
