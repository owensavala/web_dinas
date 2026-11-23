<?php
require_once __DIR__ . '/koneksi.php';

$cari = trim($_GET['q'] ?? '');
$filter_huruf = strtoupper(trim($_GET['huruf'] ?? ''));
$filter_kat = trim($_GET['kategori'] ?? '');

$where_glo = "WHERE 1=1 ";
$params_glo = [];
$types_glo  = "";

if (!empty($cari)) {
    $where_glo .= "AND (istilah LIKE ? OR arti_definisi LIKE ?) ";
    $params_glo[] = "%" . $cari . "%";
    $params_glo[] = "%" . $cari . "%";
    $types_glo .= "ss";
}

if (!empty($filter_huruf) && $filter_huruf !== 'ALL') {
    $where_glo .= "AND istilah LIKE ? ";
    $params_glo[] = $filter_huruf . "%";
    $types_glo .= "s";
}

if (!empty($filter_kat) && $filter_kat !== 'all') {
    $where_glo .= "AND kategori = ? ";
    $params_glo[] = $filter_kat;
    $types_glo .= "s";
}

$sql_glo = "SELECT * FROM glosarium_budaya " . $where_glo . " ORDER BY istilah ASC";
$stmt_glo = mysqli_prepare($koneksi, $sql_glo);
if (!empty($params_glo)) {
    mysqli_stmt_bind_param($stmt_glo, $types_glo, ...$params_glo);
}
mysqli_stmt_execute($stmt_glo);
$res_glo = mysqli_stmt_get_result($stmt_glo);
$total_istilah = mysqli_num_rows($res_glo);
?>

<div class="space-y-8">
    <!-- Header Page -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-5">
            <div>
                <span class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200/50 mb-2">
                    <i class="bi bi-book-half"></i>
                    <span>Ensiklopedi Digital Adat Se Atorang</span>
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight font-serif">
                    Glosarium Istilah Bahasa & Budaya Ternate
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-2xl leading-relaxed">
                    Kamus kosakata adat, pranata sosial kesultanan, seni tradisi, dan istilah kearifan lokal Moloku Kie Raha.
                </p>
            </div>

            <!-- Form Pencarian Glosarium -->
            <form action="" method="GET" class="flex items-center space-x-2 w-full md:w-80">
                <input type="hidden" name="halaman" value="glosarium">
                <div class="relative w-full">
                    <input type="text" name="q" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari istilah adat..." class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 pl-3 pr-8 py-2.5">
                    <?php if (!empty($cari)): ?>
                        <a href="index.php?halaman=glosarium" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs"><i class="bi bi-x-circle-fill"></i></a>
                    <?php endif; ?>
                </div>
                <button type="submit" class="px-3.5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-800 hover:bg-brand-900 transition shrink-0 shadow-sm">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <!-- Filter Huruf Alfabetis A - Z -->
        <div class="flex flex-wrap items-center gap-1.5 pt-4">
            <span class="text-xs font-bold text-slate-400 mr-2 uppercase tracking-wider">Indeks:</span>
            <a href="index.php?halaman=glosarium" class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold transition <?= empty($filter_huruf) || $filter_huruf === 'ALL' ? 'bg-brand-800 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-100' ?>">
                Semua
            </a>
            <?php foreach (range('A', 'Z') as $char): ?>
                <a href="index.php?halaman=glosarium&huruf=<?= $char ?>" class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-semibold transition <?= $filter_huruf === $char ? 'bg-brand-800 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-50 hover:text-brand-800' ?>">
                    <?= $char ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Grid Kartu Glosarium -->
    <div class="space-y-4">
        <div class="flex items-center justify-between px-1">
            <span class="text-xs text-slate-500 dark:text-slate-400">
                Menampilkan <strong><?= $total_istilah ?></strong> entri kosakata adat
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <?php if ($total_istilah > 0): ?>
                <?php while ($glo = mysqli_fetch_assoc($res_glo)): ?>
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 hover:border-gold-300 dark:hover:border-gold-500 transition duration-200 flex flex-col justify-between group">
                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white font-serif group-hover:text-brand-700 dark:group-hover:text-gold-400 transition">
                                        <?= htmlspecialchars($glo['istilah']) ?>
                                    </h3>
                                    <?php if (!empty($glo['pelafalan'])): ?>
                                        <span class="text-xs font-mono text-slate-400 tracking-wider">
                                            [<?= htmlspecialchars($glo['pelafalan']) ?>]
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800 shrink-0">
                                    <?= htmlspecialchars($glo['kategori']) ?>
                                </span>
                            </div>

                            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                <?= htmlspecialchars($glo['arti_definisi']) ?>
                            </p>

                            <?php if (!empty($glo['contoh_penggunaan'])): ?>
                                <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border-l-4 border-gold-400 text-xs text-slate-600 dark:text-slate-300 italic">
                                    <span class="font-bold not-italic text-slate-500 block mb-0.5 text-[10px] uppercase">Contoh Pemakaian:</span>
                                    "<?= htmlspecialchars($glo['contoh_penggunaan']) ?>"
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-2 bg-white dark:bg-slate-900 rounded-3xl p-10 text-center text-slate-400 border border-slate-100 dark:border-slate-800">
                    <i class="bi bi-search text-3xl block mb-2 opacity-40"></i>
                    Tidak ada istilah adat yang sesuai dengan pencarian "<strong><?= htmlspecialchars($cari . $filter_huruf) ?></strong>".
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
