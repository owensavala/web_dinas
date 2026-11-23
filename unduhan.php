<?php
require_once __DIR__ . '/koneksi.php';

$sql_dok = "SELECT id, judul, file_pdf, tanggal FROM agenda_b WHERE file_pdf != '' ORDER BY id DESC";
$q_dok   = mysqli_query($koneksi, $sql_dok);
?>

<div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
    <div class="border-b border-slate-100 pb-4">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-700">Keterbukaan Informasi Publik</span>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pusat Unduhan Dokumen Resmi</h2>
        <p class="text-sm text-slate-500 mt-1">Layanan akses dokumen laporan kebudayaan, regulasi, dan publikasi resmi Dinas Kebudayaan Kota Ternate.</p>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-100 shadow-sm">
        <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                <tr>
                    <th scope="col" class="px-5 py-3.5 w-14">No</th>
                    <th scope="col" class="px-5 py-3.5">Nama Dokumen</th>
                    <th scope="col" class="px-5 py-3.5 w-36">Tanggal Rilis</th>
                    <th scope="col" class="px-5 py-3.5 text-center w-28">Format</th>
                    <th scope="col" class="px-5 py-3.5 text-center w-36">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                <?php if (mysqli_num_rows($q_dok) > 0): ?>
                    <?php $no = 1; while ($doc = mysqli_fetch_assoc($q_dok)): ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 font-bold text-slate-400"><?= $no++ ?></td>
                            <td class="px-5 py-4">
                                <h5 class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($doc['judul']) ?></h5>
                                <span class="text-xs text-slate-400 font-mono"><?= htmlspecialchars($doc['file_pdf']) ?></span>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-500">
                                <i class="bi bi-calendar3 text-brand-600 mr-1"></i> <?= date('d M Y', strtotime($doc['tanggal'])) ?>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <i class="bi bi-file-earmark-pdf mr-1"></i> PDF
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <a href="asset/file/<?= htmlspecialchars($doc['file_pdf']) ?>" target="_blank" class="p-1.5 rounded-lg text-slate-500 hover:text-brand-800 hover:bg-slate-100 transition" title="Lihat Dokumen">
                                        <i class="bi bi-eye text-sm"></i>
                                    </a>
                                    <a href="asset/file/<?= htmlspecialchars($doc['file_pdf']) ?>" download class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold text-white bg-brand-800 hover:bg-brand-900 transition shadow-sm">
                                        <i class="bi bi-download mr-1"></i> Unduh
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada dokumen yang tersedia untuk diunduh.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
