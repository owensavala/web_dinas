<?php
require_once __DIR__ . '/koneksi.php';

$sql = "SELECT * FROM pejabat_struktural ORDER BY id ASC";
$result = mysqli_query($koneksi, $sql);
?>

<div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
    <div class="border-b border-slate-100 pb-4">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-700">Aparatur Sipil Negara</span>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pejabat Struktural</h2>
        <p class="text-sm text-slate-500 mt-1">Daftar pejabat struktural pada Dinas Kebudayaan Kota Ternate.</p>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-100 shadow-sm">
        <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                <tr>
                    <th scope="col" class="px-5 py-3.5 w-16">No</th>
                    <th scope="col" class="px-5 py-3.5">Nama Pejabat</th>
                    <th scope="col" class="px-5 py-3.5">Jabatan Struktural</th>
                    <th scope="col" class="px-5 py-3.5 text-center w-28">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 font-bold text-slate-400"><?= $no++ ?></td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900 flex items-center space-x-2">
                                    <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center text-xs"><i class="bi bi-person-fill"></i></span>
                                    <span><?= htmlspecialchars($row['nama_pejabat']) ?></span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    <?= htmlspecialchars($row['jabatan']) ?>
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Aktif
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-slate-400">Data pejabat belum tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
