<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/agenda_fungsi.php';

$katakunci = trim($_GET['katakunci'] ?? '');
$per_page  = 6;
$page      = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset    = ($page - 1) * $per_page;

$params = [];
$types  = "";
$where  = "";

if ($katakunci !== '') {
    $where = "WHERE judul LIKE ? ";
    $params[] = "%" . $katakunci . "%";
    $types .= "s";
}

// Hitung total berita
$count_query = "SELECT COUNT(*) as total FROM agenda_b " . $where;
$stmt_count = mysqli_prepare($koneksi, $count_query);
if ($katakunci !== '') {
    mysqli_stmt_bind_param($stmt_count, $types, ...$params);
}
mysqli_stmt_execute($stmt_count);
$res_count = mysqli_stmt_get_result($stmt_count);
$row_count = mysqli_fetch_assoc($res_count);
$total_rows = $row_count['total'] ?? 0;
$total_pages = ceil($total_rows / $per_page);

// Query data berita
$sql = "SELECT * FROM agenda_b " . $where . " ORDER BY id DESC LIMIT ?, ?";
$stmt = mysqli_prepare($koneksi, $sql);
if ($katakunci !== '') {
    $types .= "ii";
    $params[] = $offset;
    $params[] = $per_page;
    mysqli_stmt_bind_param($stmt, $types, ...$params);
} else {
    mysqli_stmt_bind_param($stmt, "ii", $offset, $per_page);
}
mysqli_stmt_execute($stmt);
$q1 = mysqli_stmt_get_result($stmt);
?>

<div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-700">Warta Terkini</span>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Agenda & Berita Kebudayaan</h2>
            <p class="text-sm text-slate-500 mt-0.5">Informasi resmi seputar festival dan kegiatan pelestarian budaya.</p>
        </div>

        <!-- Form Pencarian Berita -->
        <form action="" method="GET" class="flex items-center space-x-2 w-full sm:w-auto">
            <input type="hidden" name="halaman" value="berita">
            <div class="relative w-full sm:w-64">
                <input type="text" name="katakunci" value="<?= htmlspecialchars($katakunci) ?>" placeholder="Cari warta..." class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 pl-3 pr-8 py-2">
                <?php if ($katakunci !== ''): ?>
                    <a href="index.php?halaman=berita" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs"><i class="bi bi-x-circle-fill"></i></a>
                <?php endif; ?>
            </div>
            <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-brand-800 hover:bg-brand-900 transition">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <!-- Grid Warta -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <?php if ($total_rows > 0): ?>
            <?php while ($r1 = mysqli_fetch_assoc($q1)): ?>
                <?php
                $img_src = !empty($r1['gambar']) ? $r1['gambar'] : 'diskeb.png';
                $formatted_date = date('d F Y', strtotime($r1['tanggal']));
                ?>
                <article class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-card-hover hover:border-brand-200 transition duration-300 flex flex-col group">
                    <div class="relative h-48 overflow-hidden bg-slate-100">
                        <img src="asset/img/<?= htmlspecialchars($img_src) ?>" 
                             alt="<?= htmlspecialchars($r1['judul']) ?>" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                             onerror="this.src='asset/gmbr/diskeb.png';">
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md text-[11px] font-bold bg-brand-800/90 text-white backdrop-blur-sm shadow">
                            <i class="bi bi-tag-fill mr-1 text-gold-300"></i> Agenda Dinas
                        </span>
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center text-xs text-slate-400 space-x-2 mb-2">
                            <i class="bi bi-calendar-event text-brand-600"></i>
                            <span><?= $formatted_date ?></span>
                        </div>
                        <h4 class="font-bold text-slate-900 text-base leading-snug group-hover:text-brand-800 transition line-clamp-2 mb-4">
                            <a href="index.php?halaman=halaman_berita&id=<?= $r1['id'] ?>">
                                <?= htmlspecialchars($r1['judul']) ?>
                            </a>
                        </h4>
                        <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <a href="index.php?halaman=halaman_berita&id=<?= $r1['id'] ?>" class="font-bold text-brand-700 hover:text-brand-900 inline-flex items-center">
                                <span>Baca Selengkapnya</span>
                                <i class="bi bi-chevron-right ml-1 text-[10px]"></i>
                            </a>
                            <?php if (!empty($r1['file_pdf'])): ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <i class="bi bi-file-earmark-pdf mr-1"></i> PDF
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-2 py-12 text-center text-slate-400">
                <i class="bi bi-search text-3xl mb-2 block"></i>
                <p class="text-sm">Tidak ditemukan warta yang sesuai kata kunci pencarian Anda.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="flex justify-center items-center space-x-1 pt-6 border-t border-slate-100">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="index.php?halaman=berita&page=<?= $i ?><?= ($katakunci !== '') ? '&katakunci=' . urlencode($katakunci) : '' ?>" 
                   class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition <?= ($i == $page) ? 'bg-brand-800 text-white shadow' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
