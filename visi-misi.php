<?php
require_once __DIR__ . '/koneksi.php';

$sql = "SELECT * FROM visimisi LIMIT 1";
$result = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($result);

$visi = $data['visi'] ?? 'Terwujudnya Pelestarian Kebudayaan dan Penguatan Nilai-Nilai Sejarah Lokal Menuju Kota Ternate yang Mandiri dan Berkeadaban.';
$misi = $data['misi'] ?? '';
?>

<div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
    <div class="border-b border-slate-100 pb-4">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-700">Rencana Strategis</span>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Visi & Misi Dinas Kebudayaan</h2>
        <p class="text-sm text-slate-500 mt-1">Landasan arah kebijakan pembangunan dan pelestarian kebudayaan di Kota Ternate.</p>
    </div>

    <!-- Kartu Visi Mewah -->
    <div class="rounded-2xl p-6 sm:p-8 bg-gradient-to-br from-brand-950 via-brand-900 to-amber-900 text-white shadow-md relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/5 rounded-full pointer-events-none"></div>
        <div class="relative z-10 text-center max-w-2xl mx-auto space-y-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gold-500 text-slate-950 uppercase tracking-widest shadow">
                <i class="bi bi-bullseye mr-1.5"></i> Visi Utama
            </span>
            <h3 class="text-xl sm:text-2xl font-extrabold leading-snug tracking-tight text-white font-serif">
                "<?= htmlspecialchars($visi) ?>"
            </h3>
        </div>
    </div>

    <!-- Misi Strategis -->
    <div class="space-y-4 pt-2">
        <div class="flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-brand-600"></span>
            <h3 class="text-base font-bold text-slate-900">Misi Pembangunan Kebudayaan</h3>
        </div>

        <div class="space-y-3">
            <?php if (!empty($misi)): ?>
                <?php 
                $misi_lines = explode("\n", str_replace("\r", "", $misi));
                $idx = 1;
                foreach ($misi_lines as $line): 
                    $line = trim($line);
                    if (empty($line)) continue;
                ?>
                    <div class="flex items-start space-x-3.5 p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-brand-200 transition">
                        <span class="w-7 h-7 rounded-lg bg-brand-800 text-white font-bold flex items-center justify-center shrink-0 text-xs shadow-sm">
                            <?= $idx++ ?>
                        </span>
                        <p class="text-slate-700 text-sm leading-relaxed pt-0.5">
                            <?= htmlspecialchars($line) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="flex items-start space-x-3.5 p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="w-7 h-7 rounded-lg bg-brand-800 text-white font-bold flex items-center justify-center shrink-0 text-xs shadow-sm">1</span>
                    <p class="text-slate-700 text-sm leading-relaxed pt-0.5">Meningkatkan perlindungan, pengembangan, dan pemanfaatan cagar budaya dan situs bersejarah Kota Ternate.</p>
                </div>
                <div class="flex items-start space-x-3.5 p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="w-7 h-7 rounded-lg bg-brand-800 text-white font-bold flex items-center justify-center shrink-0 text-xs shadow-sm">2</span>
                    <p class="text-slate-700 text-sm leading-relaxed pt-0.5">Menggali dan melestarikan tradisi, kesenian lokal, serta adat se atoran Kesultanan Ternate.</p>
                </div>
                <div class="flex items-start space-x-3.5 p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="w-7 h-7 rounded-lg bg-brand-800 text-white font-bold flex items-center justify-center shrink-0 text-xs shadow-sm">3</span>
                    <p class="text-slate-700 text-sm leading-relaxed pt-0.5">Mengoptimalkan diplomasi kebudayaan jalur rempah sebagai identitas kebanggaan peradaban bahari.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
