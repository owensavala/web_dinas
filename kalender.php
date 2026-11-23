<?php
require_once __DIR__ . '/koneksi.php';

// Ambil event budaya mendatang terdekat untuk Featured Countdown
$sql_featured = "SELECT * FROM kalender_budaya WHERE tanggal_mulai >= CURDATE() ORDER BY tanggal_mulai ASC LIMIT 1";
$res_feat = mysqli_query($koneksi, $sql_featured);
$featured_event = mysqli_fetch_assoc($res_feat);

// Jika tidak ada event mendatang, ambil event terbaru
if (!$featured_event) {
    $res_feat_fallback = mysqli_query($koneksi, "SELECT * FROM kalender_budaya ORDER BY tanggal_mulai DESC LIMIT 1");
    $featured_event = mysqli_fetch_assoc($res_feat_fallback);
}

// Ambil semua daftar agenda budaya
$sql_all_events = "SELECT * FROM kalender_budaya ORDER BY tanggal_mulai ASC";
$res_events = mysqli_query($koneksi, $sql_all_events);
?>

<div class="space-y-8">
    <!-- Header Page -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
            <span class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200/50 mb-2">
                <i class="bi bi-calendar-check-fill"></i>
                <span>Calendar of Events (CoE) Kota Ternate</span>
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight font-serif">
                Kalender Festival & Pagelaran Budaya Kota Ternate
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-2xl leading-relaxed">
                Jadwal resmi perayaan adat kesultanan, tradisi ela-ela, ritual bahari kololi kie, dan festival seni budaya sepanjang tahun.
            </p>
        </div>
    </div>

    <!-- Featured Festival + Live Countdown Timer -->
    <?php if ($featured_event): ?>
        <?php
        $tgl_mulai_format = date('d F Y', strtotime($featured_event['tanggal_mulai']));
        $tgl_selesai_format = date('d F Y', strtotime($featured_event['tanggal_selesai']));
        $target_countdown = $featured_event['tanggal_mulai'] . " 19:30:00";
        $img_event = !empty($featured_event['gambar']) ? $featured_event['gambar'] : 'diskeb.png';
        ?>
        <div class="relative bg-gradient-to-br from-brand-950 via-slate-900 to-brand-900 text-white rounded-3xl overflow-hidden shadow-xl border border-brand-800/60 p-6 sm:p-10">
            <!-- Background Ornaments -->
            <div class="absolute -right-16 -top-16 w-80 h-80 rounded-full bg-gold-400/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-16 -bottom-16 w-80 h-80 rounded-full bg-brand-500/10 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Info Acara -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="flex items-center space-x-2.5">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-gold-400 text-slate-950 uppercase tracking-wider">
                            <i class="bi bi-star-fill mr-1"></i> Agenda Utama
                        </span>
                        <span class="text-xs text-slate-300 font-semibold">
                            <i class="bi bi-tag-fill text-gold-400 mr-1"></i> <?= htmlspecialchars($featured_event['kategori']) ?>
                        </span>
                    </div>

                    <h3 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight font-serif leading-tight">
                        <?= htmlspecialchars($featured_event['nama_event']) ?>
                    </h3>

                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-xl">
                        <?= htmlspecialchars($featured_event['deskripsi']) ?>
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-xs text-slate-200">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-calendar-event text-gold-400 text-base"></i>
                            <span><?= $tgl_mulai_format ?> - <?= $tgl_selesai_format ?></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-clock text-gold-400 text-base"></i>
                            <span><?= htmlspecialchars($featured_event['waktu']) ?></span>
                        </div>
                        <div class="flex items-center space-x-2 sm:col-span-2">
                            <i class="bi bi-geo-alt-fill text-rose-400 text-base"></i>
                            <span><?= htmlspecialchars($featured_event['lokasi']) ?></span>
                        </div>
                    </div>

                    <!-- Tombol Tambah ke Google Calendar -->
                    <?php
                    $gcal_start = date('Ymd\THis', strtotime($featured_event['tanggal_mulai'] . " 19:30:00"));
                    $gcal_end   = date('Ymd\THis', strtotime($featured_event['tanggal_selesai'] . " 23:00:00"));
                    $gcal_url   = "https://calendar.google.com/calendar/render?action=TEMPLATE&text=" . urlencode($featured_event['nama_event']) . "&dates=" . $gcal_start . "/" . $gcal_end . "&details=" . urlencode($featured_event['deskripsi']) . "&location=" . urlencode($featured_event['lokasi']);
                    ?>
                    <div class="pt-3 flex flex-wrap items-center gap-3">
                        <a href="<?= $gcal_url ?>" target="_blank" class="inline-flex items-center px-4 py-2.5 rounded-xl text-xs font-bold bg-white text-slate-900 hover:bg-slate-100 shadow transition transform hover:-translate-y-0.5">
                            <i class="bi bi-calendar-plus-fill mr-1.5 text-brand-700"></i> Simpan ke Google Calendar
                        </a>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($featured_event['lokasi'] . ' Ternate') ?>" target="_blank" class="inline-flex items-center px-4 py-2.5 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/20 text-white backdrop-blur-sm border border-white/20 transition">
                            <i class="bi bi-map mr-1.5 text-gold-400"></i> Petunjuk Lokasi
                        </a>
                    </div>
                </div>

                <!-- Countdown Timer Card -->
                <div class="lg:col-span-5 bg-white/10 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-white/10 text-center space-y-4">
                    <span class="text-xs font-bold uppercase tracking-widest text-gold-400 block">Menuju Pelaksanaan:</span>
                    
                    <div class="grid grid-cols-4 gap-2" id="countdownBox" data-target="<?= $target_countdown ?>">
                        <div class="bg-black/30 rounded-2xl p-2.5 sm:p-3">
                            <span id="cd-days" class="text-2xl sm:text-3xl font-extrabold text-white font-mono block">00</span>
                            <span class="text-[10px] text-slate-400 uppercase font-semibold">Hari</span>
                        </div>
                        <div class="bg-black/30 rounded-2xl p-2.5 sm:p-3">
                            <span id="cd-hours" class="text-2xl sm:text-3xl font-extrabold text-white font-mono block">00</span>
                            <span class="text-[10px] text-slate-400 uppercase font-semibold">Jam</span>
                        </div>
                        <div class="bg-black/30 rounded-2xl p-2.5 sm:p-3">
                            <span id="cd-mins" class="text-2xl sm:text-3xl font-extrabold text-white font-mono block">00</span>
                            <span class="text-[10px] text-slate-400 uppercase font-semibold">Menit</span>
                        </div>
                        <div class="bg-black/30 rounded-2xl p-2.5 sm:p-3">
                            <span id="cd-secs" class="text-2xl sm:text-3xl font-extrabold text-gold-400 font-mono block animate-pulse">00</span>
                            <span class="text-[10px] text-slate-400 uppercase font-semibold">Detik</span>
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-300 italic">
                        "Saksikan keagungan tradisi Moloku Kie Raha di Kota Pusaka Ternate."
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Timeline Seluruh Agenda Budaya -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800 space-y-6">
        <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">Katalog Agenda & Festival Budaya Kota</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar agenda resmi dinas kebudayaan yang diselenggarakan sepanjang tahun kalender.</p>
        </div>

        <div class="space-y-4">
            <?php while ($ev = mysqli_fetch_assoc($res_events)): ?>
                <?php
                $tgl_start = date('d M Y', strtotime($ev['tanggal_mulai']));
                $tgl_end   = date('d M Y', strtotime($ev['tanggal_selesai']));
                $img_ev    = !empty($ev['gambar']) ? $ev['gambar'] : 'diskeb.png';
                ?>
                <div class="p-5 rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-gold-300 dark:hover:border-gold-500 bg-slate-50/60 dark:bg-slate-800/40 hover:bg-white dark:hover:bg-slate-800 transition duration-200 flex flex-col md:flex-row items-start md:items-center justify-between gap-5 group">
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 rounded-2xl bg-brand-800 text-white shrink-0 flex flex-col items-center justify-center font-mono shadow-sm group-hover:scale-105 transition-transform">
                            <span class="text-[10px] uppercase tracking-wider text-gold-300 font-bold"><?= date('M', strtotime($ev['tanggal_mulai'])) ?></span>
                            <span class="text-xl font-extrabold leading-none"><?= date('d', strtotime($ev['tanggal_mulai'])) ?></span>
                        </div>
                        <div class="space-y-1">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-brand-100 dark:bg-brand-950 text-brand-800 dark:text-brand-300">
                                <?= htmlspecialchars($ev['kategori']) ?>
                            </span>
                            <h4 class="text-base font-extrabold text-slate-900 dark:text-white group-hover:text-brand-700 dark:group-hover:text-gold-400 transition">
                                <?= htmlspecialchars($ev['nama_event']) ?>
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 max-w-xl">
                                <?= htmlspecialchars($ev['deskripsi']) ?>
                            </p>
                            <div class="flex flex-wrap items-center gap-3 text-xs text-slate-400 pt-1">
                                <span class="flex items-center"><i class="bi bi-clock mr-1 text-slate-500"></i> <?= htmlspecialchars($ev['waktu']) ?></span>
                                <span class="flex items-center"><i class="bi bi-geo-alt-fill mr-1 text-rose-500"></i> <?= htmlspecialchars($ev['lokasi']) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="shrink-0 flex items-center space-x-2 w-full md:w-auto justify-end">
                        <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text=<?= urlencode($ev['nama_event']) ?>&details=<?= urlencode($ev['deskripsi']) ?>&location=<?= urlencode($ev['lokasi']) ?>" target="_blank" class="px-3 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 hover:bg-slate-50 transition shadow-sm" title="Simpan ke Kalender">
                            <i class="bi bi-calendar-plus mr-1"></i> Kalender
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const box = document.getElementById("countdownBox");
    if (!box) return;

    const targetDateStr = box.getAttribute("data-target");
    const targetDate = new Date(targetDateStr).getTime();

    function updateTimer() {
        const now = new Date().getTime();
        const diff = targetDate - now;

        if (diff <= 0) {
            document.getElementById("cd-days").innerText = "00";
            document.getElementById("cd-hours").innerText = "00";
            document.getElementById("cd-mins").innerText = "00";
            document.getElementById("cd-secs").innerText = "00";
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const secs = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById("cd-days").innerText = String(days).padStart(2, '0');
        document.getElementById("cd-hours").innerText = String(hours).padStart(2, '0');
        document.getElementById("cd-mins").innerText = String(mins).padStart(2, '0');
        document.getElementById("cd-secs").innerText = String(secs).padStart(2, '0');
    }

    updateTimer();
    setInterval(updateTimer, 1000);
});
</script>
