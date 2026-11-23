<?php
require_once __DIR__ . '/koneksi.php';

$js_kepala   = '';
$gambar      = '';
$nama        = '';
$error       = '';
$sukses      = '';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

if ($id > 0) {
    $stmt_k = mysqli_prepare($koneksi, "SELECT * FROM kepala WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt_k, "i", $id);
    mysqli_stmt_execute($stmt_k);
    $res_k = mysqli_stmt_get_result($stmt_k);
    if ($r1 = mysqli_fetch_assoc($res_k)) {
        $js_kepala = $r1['jenis_kepala'];
        $nama      = $r1['nama'];
        $gambar    = $r1['foto'];
    }
}

if (isset($_POST['simpan'])) {
    $js_kepala = trim($_POST['jenis_kepala'] ?? '');
    $nama      = trim($_POST['nama'] ?? '');

    if (empty($js_kepala) || empty($nama)) {
        $error = "Silakan lengkapi jenis jabatan dan nama pimpinan.";
    }

    $new_gambar = $gambar;
    if (!empty($_FILES['gambar']['name'])) {
        $gambar_file = $_FILES['gambar']['tmp_name'];
        $orig_name   = $_FILES['gambar']['name'];
        $ext         = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
        $valid_ext   = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $valid_ext)) {
            $error = "Format foto harus JPG, JPEG, PNG, atau WEBP.";
        } else {
            $folder = "../asset/gmbr";
            if (!is_dir($folder)) { mkdir($folder, 0777, true); }
            $new_gambar = 'kepala' . time() . '_' . preg_replace('/[^a-zA-Z0-9_\.]/', '', $orig_name);
            move_uploaded_file($gambar_file, $folder . "/" . $new_gambar);
        }
    }

    if (empty($error)) {
        if ($id > 0) {
            $stmt_up = mysqli_prepare($koneksi, "UPDATE kepala SET jenis_kepala = ?, foto = ?, nama = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt_up, "sssi", $js_kepala, $new_gambar, $nama, $id);
            if (mysqli_stmt_execute($stmt_up)) {
                $sukses = "Profil pimpinan berhasil diperbarui.";
                $gambar = $new_gambar;
            } else {
                $error = "Gagal memperbarui profil: " . mysqli_error($koneksi);
            }
        } else {
            $stmt_in = mysqli_prepare($koneksi, "INSERT INTO kepala (jenis_kepala, foto, nama) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt_in, "sss", $js_kepala, $new_gambar, $nama);
            if (mysqli_stmt_execute($stmt_in)) {
                $sukses = "Data pimpinan berhasil disimpan.";
                $gambar = $new_gambar;
            } else {
                $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
    <div class="border-b border-slate-100 pb-4">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-700">Profil Kelembagaan</span>
        <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Kelola Profil Pimpinan Dinas</h3>
        <p class="text-sm text-slate-500 mt-1">Informasi ini ditampilkan pada widget utama dan sambutan beranda publik.</p>
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

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
            <!-- Preview Foto -->
            <div class="md:col-span-4 text-center">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Foto Pimpinan Saat Ini</label>
                <div class="w-40 h-52 rounded-2xl overflow-hidden shadow-md ring-4 ring-slate-100 mx-auto bg-slate-100">
                    <img src="../asset/gmbr/<?= htmlspecialchars($gambar ?: 'diskeb.png') ?>" alt="Foto Pimpinan" class="w-full h-full object-cover">
                </div>
                <div class="mt-4">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Ganti Foto Resmi</label>
                    <input type="file" name="gambar" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-800 border border-slate-200 rounded-xl p-1">
                </div>
            </div>

            <!-- Isian Form -->
            <div class="md:col-span-8 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jabatan / Jenis Kepala</label>
                    <input type="text" name="jenis_kepala" value="<?= htmlspecialchars($js_kepala) ?>" required placeholder="Contoh: KEPALA DINAS" class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-3 font-semibold uppercase">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap & Gelar</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required placeholder="Contoh: DR. MUSLIM GANI, S.Pd., M.Pd." class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-3 font-semibold text-slate-900">
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center space-x-3">
                    <button type="submit" name="simpan" class="px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow transition transform hover:-translate-y-0.5">
                        <i class="bi bi-check2-circle mr-1.5"></i> Simpan Pembaruan
                    </button>
                    <a href="index.php" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
