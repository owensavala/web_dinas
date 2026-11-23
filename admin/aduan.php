<?php
require_once __DIR__ . '/koneksi.php';

// Fitur Export CSV untuk Laporan Kedinasan
if (isset($_GET['action']) && $_GET['action'] == 'export_csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=Laporan_Aduan_Warga_Disbud_Ternate_' . date('Ymd_His') . '.csv');

    $output = fopen('php://output', 'w');
    // BOM for Excel UTF-8
    fputs($output, "\xEF\xBB\xBF");
    fputcsv($output, ['No', 'Nomor Tiket', 'Nama Pelapor', 'No. WhatsApp', 'Email', 'Kategori', 'Isi Aspirasi/Aduan', 'Status', 'Tanggal Masuk']);

    $q_exp = mysqli_query($koneksi, "SELECT * FROM pesan_aduan ORDER BY id DESC");
    $no = 1;
    while ($row_e = mysqli_fetch_assoc($q_exp)) {
        fputcsv($output, [
            $no++,
            $row_e['nomor_tiket'],
            $row_e['nama'],
            $row_e['telepon'],
            $row_e['email'],
            $row_e['kategori'],
            $row_e['pesan'],
            $row_e['status'],
            $row_e['tanggal']
        ]);
    }
    fclose($output);
    exit();
}

$sukses = "";
$error = "";

// Ubah Status Aduan
if (isset($_POST['update_status'])) {
    $aduan_id   = (int)$_POST['aduan_id'];
    $new_status = $_POST['status'];
    $stmt_up = mysqli_prepare($koneksi, "UPDATE pesan_aduan SET status = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt_up, "si", $new_status, $aduan_id);
    if (mysqli_stmt_execute($stmt_up)) {
        $sukses = "Status aduan berhasil diperbarui.";
    }
}

// Hapus Aduan
if (isset($_GET['del_id'])) {
    $del_id = (int)$_GET['del_id'];
    $stmt_del = mysqli_prepare($koneksi, "DELETE FROM pesan_aduan WHERE id = ?");
    mysqli_stmt_bind_param($stmt_del, "i", $del_id);
    if (mysqli_stmt_execute($stmt_del)) {
        $sukses = "Pesan aduan berhasil dihapus.";
    }
}

$status_filter = $_GET['status'] ?? 'Semua';
$where = "";
if ($status_filter !== 'Semua') {
    $where = "WHERE status = '" . mysqli_real_escape_string($koneksi, $status_filter) . "'";
}

$q_aduan = mysqli_query($koneksi, "SELECT * FROM pesan_aduan $where ORDER BY id DESC");
$count_baru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesan_aduan WHERE status = 'Baru'"))['total'] ?? 0;
?>

<div class="space-y-8">
    <?php if (!empty($sukses)): ?>
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center space-x-3 text-xs">
            <i class="bi bi-check-circle-fill text-emerald-600 text-lg shrink-0"></i>
            <div><?= htmlspecialchars($sukses) ?></div>
        </div>
    <?php endif; ?>

    <!-- Header & Action Controls -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-700">Layanan Aspirasi</span>
                <?php if ($count_baru > 0): ?>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white animate-pulse">
                        <?= $count_baru ?> Baru
                    </span>
                <?php endif; ?>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kotak Masuk Aduan & Pesan Warga</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar aspirasi dan aduan yang masuk melalui formulir kontak portal publik.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Tombol Export Excel/CSV -->
            <a href="index.php?halaman=aduan&action=export_csv" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition shadow-sm">
                <i class="bi bi-file-earmark-excel-fill mr-1.5 text-emerald-600"></i> Export Laporan (CSV/Excel)
            </a>

            <!-- Filter Status -->
            <div class="flex items-center space-x-1 p-1 bg-slate-100 rounded-xl text-xs font-semibold">
                <a href="index.php?halaman=aduan&status=Semua" class="px-3 py-1.5 rounded-lg transition <?= ($status_filter === 'Semua') ? 'bg-white text-brand-800 shadow-sm' : 'text-slate-500 hover:text-slate-900' ?>">Semua</a>
                <a href="index.php?halaman=aduan&status=Baru" class="px-3 py-1.5 rounded-lg transition <?= ($status_filter === 'Baru') ? 'bg-white text-brand-800 shadow-sm' : 'text-slate-500 hover:text-slate-900' ?>">Baru</a>
                <a href="index.php?halaman=aduan&status=Diproses" class="px-3 py-1.5 rounded-lg transition <?= ($status_filter === 'Diproses') ? 'bg-white text-brand-800 shadow-sm' : 'text-slate-500 hover:text-slate-900' ?>">Diproses</a>
                <a href="index.php?halaman=aduan&status=Selesai" class="px-3 py-1.5 rounded-lg transition <?= ($status_filter === 'Selesai') ? 'bg-white text-brand-800 shadow-sm' : 'text-slate-500 hover:text-slate-900' ?>">Selesai</a>
            </div>
        </div>
    </div>

    <!-- Daftar Aduan Cards -->
    <div class="space-y-4">
        <?php if (mysqli_num_rows($q_aduan) > 0): ?>
            <?php while ($ad = mysqli_fetch_assoc($q_aduan)): ?>
                <?php
                $wa_phone = preg_replace('/[^0-9]/', '', $ad['telepon']);
                if (str_starts_with($wa_phone, '0')) {
                    $wa_phone = '62' . substr($wa_phone, 1);
                }
                $badge_colors = [
                    'Baru' => 'bg-rose-50 text-rose-700 border-rose-200',
                    'Diproses' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'Selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                ];
                $badge_class = $badge_colors[$ad['status']] ?? 'bg-slate-100 text-slate-700';
                ?>
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition space-y-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-slate-100 pb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-700 font-bold flex items-center justify-center text-sm shadow-sm shrink-0">
                                <?= strtoupper(substr($ad['nama'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h4 class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($ad['nama']) ?></h4>
                                    <?php if (!empty($ad['nomor_tiket'])): ?>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            <?= htmlspecialchars($ad['nomor_tiket']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <span class="text-[11px] text-slate-400"><?= htmlspecialchars($ad['email']) ?> • <?= htmlspecialchars($ad['telepon']) ?></span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border <?= $badge_class ?>">
                                <?= htmlspecialchars($ad['status']) ?>
                            </span>
                            <span class="text-xs text-slate-400">
                                <?= date('d M Y, H:i', strtotime($ad['tanggal'])) ?> WIT
                            </span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">
                            <?= htmlspecialchars($ad['kategori']) ?>
                        </span>
                        <p class="text-xs text-slate-700 leading-relaxed pt-1 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                            <?= nl2br(htmlspecialchars($ad['pesan'])) ?>
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pt-2">
                        <form action="" method="POST" class="flex items-center space-x-2 text-xs">
                            <input type="hidden" name="aduan_id" value="<?= $ad['id'] ?>">
                            <span class="text-slate-400 font-semibold">Ubah Status:</span>
                            <select name="status" class="text-xs rounded-lg border-slate-200 py-1 px-2.5 focus:border-brand-600 focus:ring-brand-600">
                                <option value="Baru" <?= $ad['status'] === 'Baru' ? 'selected' : '' ?>>Baru</option>
                                <option value="Diproses" <?= $ad['status'] === 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                                <option value="Selesai" <?= $ad['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                            <button type="submit" name="update_status" class="px-2.5 py-1 rounded-lg text-xs font-bold text-white bg-brand-800 hover:bg-brand-900 transition">
                                Update
                            </button>
                        </form>

                        <div class="flex items-center space-x-2">
                            <a href="https://wa.me/<?= $wa_phone ?>?text=<?= urlencode('Halo ' . $ad['nama'] . ', kami dari Dinas Kebudayaan Kota Ternate menindaklanjuti pesan/aduan Anda nomor tiket: ' . $ad['nomor_tiket']) ?>" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-sm">
                                <i class="bi bi-whatsapp mr-1.5"></i> Hubungi Warga
                            </a>
                            <a href="index.php?halaman=aduan&del_id=<?= $ad['id'] ?>" onclick="return confirm('Hapus pesan aduan ini?')" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition" title="Hapus Aduan">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="bg-white rounded-3xl p-12 text-center text-slate-400 border border-slate-100">
                <i class="bi bi-chat-square-dots text-4xl mb-2 block"></i>
                <p class="text-sm">Tidak ada pesan aduan dalam status ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
