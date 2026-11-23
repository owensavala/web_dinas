<?php
require_once __DIR__ . '/koneksi.php';

// Export CSV Survei IKM
if (isset($_GET['action']) && $_GET['action'] === 'export_ikm') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=Laporan_IKM_Disbud_Ternate_' . date('Ymd_His') . '.csv');
    $out = fopen('php://output', 'w');
    fputs($out, "\xEF\xBB\xBF");
    fputcsv($out, ['No', 'Nama Responden', 'Email', 'Profesi/Status', 'Kemudahan (1-5)', 'Desain Tampilan (1-5)', 'Kualitas Info (1-5)', 'Kecepatan Akses (1-5)', 'Rata-rata Responden', 'Saran & Masukan', 'Tanggal']);

    $q_exp = mysqli_query($koneksi, "SELECT * FROM survei_kepuasan ORDER BY id DESC");
    $no = 1;
    while ($r = mysqli_fetch_assoc($q_exp)) {
        $avg_indiv = round(($r['skor_kemudahan'] + $r['skor_tampilan'] + $r['skor_kualitas_info'] + $r['skor_kecepatan']) / 4, 2);
        fputcsv($out, [
            $no++,
            $r['nama'],
            $r['email'],
            $r['pekerjaan'],
            $r['skor_kemudahan'],
            $r['skor_tampilan'],
            $r['skor_kualitas_info'],
            $r['skor_kecepatan'],
            $avg_indiv,
            $r['saran'],
            $r['tanggal']
        ]);
    }
    fclose($out);
    exit;
}

// Hapus Respon Survei
if (isset($_GET['action']) && $_GET['action'] === 'hapus' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    mysqli_query($koneksi, "DELETE FROM survei_kepuasan WHERE id = $del_id");
}

// Statistik Agregat IKM
$q_stat = mysqli_query($koneksi, "SELECT 
    COUNT(*) as total,
    AVG(skor_kemudahan) as avg_kemudahan,
    AVG(skor_tampilan) as avg_tampilan,
    AVG(skor_kualitas_info) as avg_info,
    AVG(skor_kecepatan) as avg_kecepatan
FROM survei_kepuasan");
$stat = mysqli_fetch_assoc($q_stat);

$total_responden = (int)($stat['total'] ?? 0);
$avg_kemudahan   = round((float)($stat['avg_kemudahan'] ?? 5.0), 2);
$avg_tampilan    = round((float)($stat['avg_tampilan'] ?? 5.0), 2);
$avg_info        = round((float)($stat['avg_info'] ?? 5.0), 2);
$avg_kecepatan   = round((float)($stat['avg_kecepatan'] ?? 5.0), 2);

$avg_total_skor  = round(($avg_kemudahan + $avg_tampilan + $avg_info + $avg_kecepatan) / 4, 2);
$skor_ikm_100    = round(($avg_total_skor / 5) * 100, 2);

$q_list = mysqli_query($koneksi, "SELECT * FROM survei_kepuasan ORDER BY id DESC");
?>

<div class="space-y-8">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-purple-700">PermenPAN-RB No. 14/2017</span>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Rekapitulasi Indeks Kepuasan Masyarakat (IKM)</h3>
            <p class="text-xs text-slate-500 mt-0.5">Laporan evaluasi kepuasan pengguna terhadap keterbukaan informasi dan pelayanan publik digital.</p>
        </div>
        <a href="index.php?halaman=survei_admin&action=export_ikm" class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-emerald-700 hover:bg-emerald-800 shadow transition transform hover:-translate-y-0.5 shrink-0">
            <i class="bi bi-file-earmark-excel-fill mr-1.5"></i> Ekspor Laporan Excel / CSV
        </a>
    </div>

    <!-- Ringkasan IKM -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-gradient-to-br from-brand-900 to-slate-900 text-white rounded-3xl p-6 shadow-md border border-brand-800">
            <span class="text-xs font-bold uppercase text-gold-400">Skor IKM (Konversi 100)</span>
            <div class="flex items-baseline space-x-2 mt-2">
                <span class="text-4xl font-black font-mono"><?= $skor_ikm_100 ?></span>
                <span class="text-xs text-slate-300">/ 100</span>
            </div>
            <span class="mt-2 inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                Mutu A (Sangat Baik)
            </span>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-1">
            <span class="text-xs font-bold uppercase text-slate-400">Total Responden</span>
            <div class="text-3xl font-extrabold font-mono text-slate-900"><?= $total_responden ?></div>
            <span class="text-[11px] text-slate-400">Warga, Peneliti, & ASN</span>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-1">
            <span class="text-xs font-bold uppercase text-slate-400">Rata-rata Bintang</span>
            <div class="text-3xl font-extrabold font-mono text-gold-500">★ <?= $avg_total_skor ?></div>
            <span class="text-[11px] text-slate-400">Skala 1.00 s/d 5.00</span>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-1">
            <span class="text-xs font-bold uppercase text-slate-400">Standar Pelayanan</span>
            <div class="text-base font-extrabold text-brand-800 mt-1">SPBE Instansi</div>
            <span class="text-[11px] text-emerald-600 font-semibold">Tersertifikasi MenPAN-RB</span>
        </div>
    </div>

    <!-- Tabel Detail Responden Survei -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 overflow-hidden">
        <h4 class="font-extrabold text-slate-900 text-sm mb-4">Daftar Tanggapan & Masukan Responden</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-4 py-3 w-12">No</th>
                        <th class="px-4 py-3">Nama & Profesi</th>
                        <th class="px-4 py-3 text-center">Kemudahan</th>
                        <th class="px-4 py-3 text-center">Tampilan</th>
                        <th class="px-4 py-3 text-center">Keakuratan</th>
                        <th class="px-4 py-3 text-center">Kecepatan</th>
                        <th class="px-4 py-3">Saran & Masukan</th>
                        <th class="px-4 py-3 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if ($total_responden > 0): ?>
                        <?php $no = 1; while ($r = mysqli_fetch_assoc($q_list)): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5 font-bold text-slate-400"><?= $no++ ?></td>
                                <td class="px-4 py-3.5">
                                    <strong class="font-bold text-slate-900 block"><?= htmlspecialchars($r['nama']) ?></strong>
                                    <span class="text-[11px] text-slate-400"><?= htmlspecialchars($r['pekerjaan']) ?> • <?= date('d M Y', strtotime($r['tanggal'])) ?></span>
                                </td>
                                <td class="px-4 py-3.5 text-center font-mono font-bold text-slate-700"><?= $r['skor_kemudahan'] ?>/5</td>
                                <td class="px-4 py-3.5 text-center font-mono font-bold text-slate-700"><?= $r['skor_tampilan'] ?>/5</td>
                                <td class="px-4 py-3.5 text-center font-mono font-bold text-slate-700"><?= $r['skor_kualitas_info'] ?>/5</td>
                                <td class="px-4 py-3.5 text-center font-mono font-bold text-slate-700"><?= $r['skor_kecepatan'] ?>/5</td>
                                <td class="px-4 py-3.5 text-slate-600 italic max-w-xs truncate">
                                    <?= !empty($r['saran']) ? '"' . htmlspecialchars($r['saran']) . '"' : '<span class="text-slate-300 not-italic">-</span>' ?>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <a href="index.php?halaman=survei_admin&action=hapus&id=<?= $r['id'] ?>" onclick="return confirm('Hapus respon ini?');" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
