<?php
require_once __DIR__ . '/koneksi.php';

$sql_v = "SELECT * FROM galeri_video ORDER BY id DESC";
$q_v   = mysqli_query($koneksi, $sql_v);
?>

<div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800 space-y-6">
    <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-700 dark:text-brand-400">Audio Visual</span>
        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Galeri Video Kebudayaan</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Dokumentasi video pertunjukan seni dan pelestarian sejarah.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <?php if (mysqli_num_rows($q_v) > 0): ?>
            <?php while ($v = mysqli_fetch_assoc($q_v)): ?>
                <div class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col">
                    <div class="aspect-video w-full bg-slate-950">
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/<?= htmlspecialchars($v['youtube_id']) ?>" title="<?= htmlspecialchars($v['judul']) ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="p-5 flex flex-col flex-grow space-y-2">
                        <span class="inline-flex items-center text-[10px] text-slate-400">
                            <i class="bi bi-calendar-event text-brand-600 mr-1.5"></i> <?= date('d M Y', strtotime($v['tanggal'])) ?>
                        </span>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white leading-snug">
                            <?= htmlspecialchars($v['judul']) ?>
                        </h4>
                        <?php if (!empty($v['deskripsi'])): ?>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                <?= htmlspecialchars($v['deskripsi']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-2 py-12 text-center text-slate-400">
                <i class="bi bi-play-circle text-3xl mb-2 block"></i>
                <p class="text-sm">Belum ada video kebudayaan yang ditambahkan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
