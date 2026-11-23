<?php
require_once __DIR__ . '/koneksi.php';

$sukses = "";
$error = "";
$admin_id = $_SESSION['admin_id'] ?? 1;

if (isset($_POST['ubah_password'])) {
    $pass_lama = $_POST['pass_lama'] ?? '';
    $pass_baru = $_POST['pass_baru'] ?? '';
    $pass_konf = $_POST['pass_konfirm'] ?? '';

    if (empty($pass_lama) || empty($pass_baru) || empty($pass_konf)) {
        $error = "Semua kolom kata sandi wajib diisi.";
    } elseif ($pass_baru !== $pass_konf) {
        $error = "Konfirmasi kata sandi baru tidak cocok.";
    } elseif (strlen($pass_baru) < 6) {
        $error = "Kata sandi baru minimal 6 karakter.";
    } else {
        $stmt_check = mysqli_prepare($koneksi, "SELECT pass FROM admin WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt_check, "i", $admin_id);
        mysqli_stmt_execute($stmt_check);
        $res_chk = mysqli_stmt_get_result($stmt_check);
        if ($row_admin = mysqli_fetch_assoc($res_chk)) {
            $current_hash = $row_admin['pass'];
            if (password_verify($pass_lama, $current_hash) || $current_hash === md5($pass_lama)) {
                $new_hash = password_hash($pass_baru, PASSWORD_BCRYPT);
                $stmt_up = mysqli_prepare($koneksi, "UPDATE admin SET pass = ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt_up, "si", $new_hash, $admin_id);
                if (mysqli_stmt_execute($stmt_up)) {
                    $sukses = "Kata sandi administrator berhasil diperbarui.";
                } else {
                    $error = "Gagal memperbarui kata sandi: " . mysqli_error($koneksi);
                }
            } else {
                $error = "Kata sandi lama yang Anda masukkan salah.";
            }
        }
    }
}

// Info Lingkungan Server
$php_version = phpversion();
$mysql_version = mysqli_get_server_info($koneksi);
$max_upload = ini_get('upload_max_filesize');
$post_max = ini_get('post_max_size');
$memory_limit = ini_get('memory_limit');
?>

<div class="space-y-8">
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Form Ganti Password -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-700">Keamanan Akses</span>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Perbarui Kata Sandi Administrator</h3>
                <p class="text-xs text-slate-500 mt-0.5">Pastikan menggunakan kombinasi kata sandi yang aman dan sulit ditebak.</p>
            </div>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username Saat Ini</label>
                    <input type="text" value="<?= htmlspecialchars($_SESSION['admin_username'] ?? 'admin') ?>" disabled class="w-full text-xs rounded-xl bg-slate-100 border-slate-200 p-2.5 font-bold text-slate-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi Lama</label>
                    <input type="password" name="pass_lama" required placeholder="Masukkan kata sandi lama" class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi Baru</label>
                        <input type="password" name="pass_baru" required placeholder="Minimal 6 karakter" class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="pass_konfirm" required placeholder="Ulangi kata sandi baru" class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" name="ubah_password" class="px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow transition transform hover:-translate-y-0.5">
                        <i class="bi bi-shield-lock-fill mr-1.5"></i> Simpan Kata Sandi Baru
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Sistem & Lingkungan Server & Cadangan Database -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Diagnostik Sistem</span>
                    <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Status & Spesifikasi Server</h3>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-slate-500">Versi PHP</span>
                        <strong class="font-mono text-slate-800"><?= $php_version ?></strong>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-slate-500">Database Engine</span>
                        <strong class="font-mono text-slate-800">MariaDB / MySQL <?= $mysql_version ?></strong>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-slate-500">Maksimum Upload Berkas</span>
                        <strong class="font-mono text-slate-800"><?= $max_upload ?></strong>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-slate-500">Batas Memori PHP</span>
                        <strong class="font-mono text-slate-800"><?= $memory_limit ?></strong>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-slate-500">Sistem Enkripsi</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Bcrypt Active</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-slate-500">Zona Waktu Lokal</span>
                        <strong class="font-mono text-slate-800">Asia/Jayapura (WIT)</strong>
                    </div>
                </div>
            </div>

            <!-- Card Cadangan Database -->
            <div class="bg-gradient-to-br from-brand-900 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-md border border-brand-800 space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-gold-400 text-slate-950 flex items-center justify-center font-bold text-lg shadow">
                        <i class="bi bi-database-down"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-gold-400">Pemulihan & Arsip</span>
                        <h4 class="text-lg font-extrabold text-white">Cadangan Basis Data</h4>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Amankan seluruh rekaman warta, cagar budaya, galeri multimedia, dan aduan aspirasi warga ke dalam format file SQL standar untuk arsip kedinasan.
                </p>
                <div class="pt-2">
                    <a href="index.php?halaman=pengaturan&action=backup_db" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl font-bold text-xs uppercase tracking-wider bg-gold-400 hover:bg-gold-500 text-slate-950 transition shadow-lg transform hover:-translate-y-0.5">
                        <i class="bi bi-cloud-arrow-down-fill mr-2 text-base"></i> Unduh Cadangan SQL (.sql)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
