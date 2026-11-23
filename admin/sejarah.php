<?php
require_once __DIR__ . '/koneksi.php';

$error = "";
$sukses = "";

$q = mysqli_query($koneksi, "SELECT * FROM sejarah LIMIT 1");
$data = mysqli_fetch_assoc($q);
$id = $data['id_sejarah'] ?? 1;
$sejarah = $data['sejarah'] ?? '';

if (isset($_POST['simpan'])) {
    $sejarah = trim($_POST['sejarah'] ?? '');

    if (empty($sejarah)) {
        $error = "Teks narasi sejarah tidak boleh kosong.";
    } else {
        if ($data) {
            $stmt = mysqli_prepare($koneksi, "UPDATE sejarah SET sejarah = ? WHERE id_sejarah = ?");
            mysqli_stmt_bind_param($stmt, "si", $sejarah, $id);
            if (mysqli_stmt_execute($stmt)) {
                $sukses = "Narasi sejarah berhasil diperbarui.";
            } else {
                $error = "Gagal memperbarui: " . mysqli_error($koneksi);
            }
        } else {
            $stmt = mysqli_prepare($koneksi, "INSERT INTO sejarah (sejarah) VALUES (?)");
            mysqli_stmt_bind_param($stmt, "s", $sejarah);
            if (mysqli_stmt_execute($stmt)) {
                $sukses = "Narasi sejarah berhasil disimpan.";
            } else {
                $error = "Gagal menyimpan: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
    <div class="border-b border-slate-100 pb-4">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-700">Sejarah & Rekam Jejak</span>
        <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Kelola Narasi Sejarah Dinas Kebudayaan</h3>
        <p class="text-sm text-slate-500 mt-1">Atur narasi sejarah berdirinya dinas serta kronologi penetapan Kota Pusaka Ternate.</p>
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
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teks Narasi Sejarah</label>
            <textarea name="sejarah" rows="10" required class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-3 leading-relaxed"><?= htmlspecialchars($sejarah) ?></textarea>
        </div>

        <div class="pt-3 border-t border-slate-100 flex items-center space-x-3">
            <button type="submit" name="simpan" class="px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow transition transform hover:-translate-y-0.5">
                <i class="bi bi-check2-circle mr-1.5"></i> Simpan Sejarah
            </button>
            <a href="index.php" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                Kembali ke Dashboard
            </a>
        </div>
    </form>
</div>
