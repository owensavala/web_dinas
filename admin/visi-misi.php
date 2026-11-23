<?php
require_once __DIR__ . '/koneksi.php';

$error = "";
$sukses = "";

// Ambil data pertama
$q = mysqli_query($koneksi, "SELECT * FROM visimisi LIMIT 1");
$data = mysqli_fetch_assoc($q);
$id = $data['id_visimis'] ?? 1;
$visi = $data['visi'] ?? '';
$misi = $data['misi'] ?? '';

if (isset($_POST['simpan'])) {
    $visi = trim($_POST['visi'] ?? '');
    $misi = trim($_POST['misi'] ?? '');

    if (empty($visi) || empty($misi)) {
        $error = "Visi dan Misi tidak boleh kosong.";
    } else {
        if ($data) {
            $stmt = mysqli_prepare($koneksi, "UPDATE visimisi SET visi = ?, misi = ? WHERE id_visimis = ?");
            mysqli_stmt_bind_param($stmt, "ssi", $visi, $misi, $id);
            if (mysqli_stmt_execute($stmt)) {
                $sukses = "Visi dan Misi berhasil diperbarui.";
            } else {
                $error = "Gagal memperbarui: " . mysqli_error($koneksi);
            }
        } else {
            $stmt = mysqli_prepare($koneksi, "INSERT INTO visimisi (visi, misi) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ss", $visi, $misi);
            if (mysqli_stmt_execute($stmt)) {
                $sukses = "Visi dan Misi berhasil disimpan.";
            } else {
                $error = "Gagal menyimpan: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
    <div class="border-b border-slate-100 pb-4">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-700">Rencana Pembangunan</span>
        <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Kelola Visi & Misi</h3>
        <p class="text-sm text-slate-500 mt-1">Atur teks visi utama dan butir-butir misi Dinas Kebudayaan Kota Ternate.</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center space-x-3 text-xs">
            <i class="bi bi-exclamation-triangle-fill text-rose-600 text-lg shrink-0"></i>
            <div><?= htmlspecialchars($error) ?></div>
        </div>
    <?php endif; ?>

    <?php if (!empty($sukses)): ?>
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center space-x-3 text-xs">
            <i class="bi bi-check-circle-fill text-emerald-600 text-lg shrink-0"></i>
            <div><?= htmlspecialchars($sukses) ?></div>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-5">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pernyataan Visi Utama</label>
            <textarea name="visi" rows="3" required placeholder="Tuliskan pernyataan visi..." class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-3 leading-relaxed"><?= htmlspecialchars($visi) ?></textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Butir-Butir Misi (Pisahkan per baris baru)</label>
            <textarea name="misi" rows="6" required placeholder="Tuliskan poin misi, satu poin per baris..." class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-3 leading-relaxed"><?= htmlspecialchars($misi) ?></textarea>
            <span class="text-[11px] text-slate-400 mt-1 block">Setiap baris baru akan otomatis ditampilkan sebagai nomor urut di website publik.</span>
        </div>

        <div class="pt-3 border-t border-slate-100 flex items-center space-x-3">
            <button type="submit" name="simpan" class="px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow transition transform hover:-translate-y-0.5">
                <i class="bi bi-check2-circle mr-1.5"></i> Simpan Visi Misi
            </button>
            <a href="index.php" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                Kembali ke Dashboard
            </a>
        </div>
    </form>
</div>
