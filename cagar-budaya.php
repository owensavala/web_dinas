<?php
require_once __DIR__ . '/koneksi.php';

$kat = $_GET['kat'] ?? 'Semua';
$where = "";
if ($kat !== 'Semua') {
    $where = "WHERE kategori = '" . mysqli_real_escape_string($koneksi, $kat) . "'";
}

$sql_cb = "SELECT * FROM cagar_budaya $where ORDER BY id ASC";
$q_cb = mysqli_query($koneksi, $sql_cb);

$kategori_list = ['Semua', 'Benteng Kolonial', 'Keraton & Istana', 'Situs Religi & Sejarah', 'Kawasan Warisan Alam & Legenda'];
?>

<div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800 space-y-8">
    <div class="border-b border-slate-100 dark:border-slate-800 pb-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-700 dark:text-brand-400">Warisan Dunia & Kota Pusaka</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight font-serif">Katalog Cagar Budaya Kota Ternate</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Inventarisasi situs sejarah, benteng kolonial, dan keraton peninggalan Kesultanan Ternate.</p>
        </div>

        <div class="flex flex-wrap gap-1.5 p-1 bg-slate-100 dark:bg-slate-800 rounded-xl">
            <?php foreach ($kategori_list as $k): ?>
                <a href="index.php?halaman=cagar-budaya&kat=<?= urlencode($k) ?>" class="px-3 py-1 rounded-lg text-xs font-semibold transition <?= ($kat === $k) ? 'bg-white dark:bg-slate-900 text-brand-800 dark:text-brand-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' ?>">
                    <?= htmlspecialchars($k) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Grid Cagar Budaya -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if (mysqli_num_rows($q_cb) > 0): ?>
            <?php while ($c = mysqli_fetch_assoc($q_cb)): ?>
                <div id="<?= $c['id'] ?>" class="bg-slate-50/70 dark:bg-slate-800/50 rounded-2xl p-5 border border-slate-200/70 dark:border-slate-700/60 hover:shadow-card-hover transition group flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="h-48 rounded-xl overflow-hidden relative cursor-pointer shadow-sm" onclick="openLightbox('asset/gmbr/<?= htmlspecialchars($c['gambar']) ?>', '<?= htmlspecialchars($c['nama']) ?>')">
                            <img src="asset/gmbr/<?= htmlspecialchars($c['gambar']) ?>" alt="<?= htmlspecialchars($c['nama']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-950/80 text-gold-300 backdrop-blur-sm border border-slate-700">
                                <?= htmlspecialchars($c['kategori']) ?>
                            </span>
                            <span class="absolute bottom-3 right-3 w-7 h-7 rounded-full bg-black/60 text-white flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition shadow">
                                <i class="bi bi-arrows-fullscreen"></i>
                            </span>
                        </div>

                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 dark:text-white group-hover:text-brand-700 dark:group-hover:text-brand-400 transition leading-snug">
                                <?= htmlspecialchars($c['nama']) ?>
                            </h3>
                            <span class="text-xs text-slate-500 dark:text-slate-400 flex items-center mt-1">
                                <i class="bi bi-geo-alt-fill text-brand-600 mr-1.5"></i> <?= htmlspecialchars($c['lokasi']) ?>
                            </span>
                        </div>

                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed text-justify">
                            <?= htmlspecialchars($c['deskripsi']) ?>
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-200/60 dark:border-slate-700 flex items-center justify-between text-xs">
                        <span class="text-[11px] text-slate-400 font-semibold"><i class="bi bi-patch-check-fill text-gold-500 mr-1"></i> Terdata di Kemendikbud</span>
                        <a href="https://maps.google.com/?q=<?= urlencode($c['nama'] . ' ' . $c['lokasi']) ?>" target="_blank" class="font-bold text-brand-700 dark:text-brand-400 hover:underline flex items-center">
                            <span>Petunjuk Arah</span>
                            <i class="bi bi-box-arrow-up-right ml-1 text-[10px]"></i>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-2 py-12 text-center text-slate-400">
                <i class="bi bi-bank2 text-4xl mb-2 block"></i>
                <p class="text-sm">Belum ada cagar budaya pada kategori ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
