<?php
require_once __DIR__ . '/koneksi.php';

$sukses = "";
$error = "";

// Hapus Foto
if (isset($_GET['del_foto'])) {
    $del_id = (int)$_GET['del_foto'];
    $stmt_del = mysqli_prepare($koneksi, "DELETE FROM galeri_foto WHERE id = ?");
    mysqli_stmt_bind_param($stmt_del, "i", $del_id);
    if (mysqli_stmt_execute($stmt_del)) {
        $sukses = "Foto berhasil dihapus dari galeri.";
    }
}

// Hapus Video
if (isset($_GET['del_video'])) {
    $del_id = (int)$_GET['del_video'];
    $stmt_del = mysqli_prepare($koneksi, "DELETE FROM galeri_video WHERE id = ?");
    mysqli_stmt_bind_param($stmt_del, "i", $del_id);
    if (mysqli_stmt_execute($stmt_del)) {
        $sukses = "Video berhasil dihapus dari galeri.";
    }
}

// Tambah Foto Baru
if (isset($_POST['tambah_foto'])) {
    $judul_foto = trim($_POST['judul_foto'] ?? '');
    $kategori   = trim($_POST['kategori'] ?? 'Kegiatan');
    $tgl_foto   = $_POST['tanggal_foto'] ?? date('Y-m-d');

    if (!empty($_FILES['gambar_foto']['name']) && !empty($judul_foto)) {
        $orig_name = $_FILES['gambar_foto']['name'];
        $tmp_file  = $_FILES['gambar_foto']['tmp_name'];
        $ext       = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
        $valid_ext = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $valid_ext)) {
            $error = "Format foto harus JPG, JPEG, PNG, atau WEBP.";
        } else {
            $folder = "../asset/gmbr";
            if (!is_dir($folder)) mkdir($folder, 0777, true);
            $new_name = 'foto_' . time() . '_' . preg_replace('/[^a-zA-Z0-9_\.]/', '', $orig_name);
            if (move_uploaded_file($tmp_file, $folder . "/" . $new_name)) {
                $stmt_in = mysqli_prepare($koneksi, "INSERT INTO galeri_foto (judul, gambar, kategori, tanggal) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt_in, "ssss", $judul_foto, $new_name, $kategori, $tgl_foto);
                if (mysqli_stmt_execute($stmt_in)) {
                    $sukses = "Foto dokumentasi berhasil ditambahkan ke galeri publik.";
                } else {
                    $error = "Gagal menyimpan foto ke database.";
                }
            }
        }
    } else {
        $error = "Silakan masukkan judul dan pilih file foto.";
    }
}

// Tambah Video YouTube Baru
if (isset($_POST['tambah_video'])) {
    $judul_vid   = trim($_POST['judul_video'] ?? '');
    $youtube_id  = trim($_POST['youtube_id'] ?? '');
    $deskripsi   = trim($_POST['deskripsi_video'] ?? '');
    $tgl_vid     = $_POST['tanggal_video'] ?? date('Y-m-d');

    // Filter jika user memasukkan full url youtube
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $youtube_id, $matches)) {
        $youtube_id = $matches[1];
    }

    if (!empty($judul_vid) && !empty($youtube_id)) {
        $stmt_v = mysqli_prepare($koneksi, "INSERT INTO galeri_video (judul, youtube_id, deskripsi, tanggal) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_v, "ssss", $judul_vid, $youtube_id, $deskripsi, $tgl_vid);
        if (mysqli_stmt_execute($stmt_v)) {
            $sukses = "Video kebudayaan berhasil ditambahkan ke galeri publik.";
        } else {
            $error = "Gagal menyimpan video ke database.";
        }
    } else {
        $error = "Silakan lengkapi judul video dan ID YouTube.";
    }
}

$q_all_foto = mysqli_query($koneksi, "SELECT * FROM galeri_foto ORDER BY id DESC");
$q_all_vid  = mysqli_query($koneksi, "SELECT * FROM galeri_video ORDER BY id DESC");
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

    <!-- Grid Form Tambah Foto & Video -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Form Tambah Foto -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-brand-700"></span>
                <h3 class="text-sm font-bold text-slate-900 uppercase">Tambah Foto Dokumentasi</h3>
            </div>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Foto</label>
                    <input type="text" name="judul_foto" required placeholder="Contoh: Pentas Tarian Tradisional Soya-Soya" class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori</label>
                        <select name="kategori" class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                            <option value="Festival">Festival</option>
                            <option value="Kesenian">Kesenian</option>
                            <option value="Sejarah">Sejarah</option>
                            <option value="Kegiatan">Kegiatan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal</label>
                        <input type="date" name="tanggal_foto" value="<?= date('Y-m-d') ?>" class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih File Foto</label>
                    <input type="file" name="gambar_foto" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-800 border border-slate-200 rounded-xl p-1">
                </div>
                <button type="submit" name="tambah_foto" class="w-full py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow transition">
                    <i class="bi bi-upload mr-1.5"></i> Unggah Foto ke Galeri
                </button>
            </form>
        </div>

        <!-- Form Tambah Video YouTube -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                <h3 class="text-sm font-bold text-slate-900 uppercase">Tambah Video Kebudayaan</h3>
            </div>
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Video</label>
                    <input type="text" name="judul_video" required placeholder="Contoh: Semarak Tradisi Ela-Ela di Kota Ternate" class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Link atau Video ID YouTube</label>
                        <input type="text" name="youtube_id" required placeholder="Contoh: oIAgJEx66F4" class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal</label>
                        <input type="date" name="tanggal_video" value="<?= date('Y-m-d') ?>" class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Singkat Video</label>
                    <textarea name="deskripsi_video" rows="2" placeholder="Catatan singkat tentang video..." class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-2.5"></textarea>
                </div>
                <button type="submit" name="tambah_video" class="w-full py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-rose-700 hover:bg-rose-800 shadow transition">
                    <i class="bi bi-youtube mr-1.5"></i> Simpan Video ke Galeri
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Foto Aktif -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Koleksi Foto Galeri</h3>
            <span class="text-xs text-slate-400">Total <?= mysqli_num_rows($q_all_foto) ?> foto terdaftar</span>
        </div>
        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3 w-16">Foto</th>
                        <th class="px-5 py-3">Judul Foto</th>
                        <th class="px-5 py-3 w-28">Kategori</th>
                        <th class="px-5 py-3 w-28">Tanggal</th>
                        <th class="px-5 py-3 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (mysqli_num_rows($q_all_foto) > 0): ?>
                        <?php while ($f = mysqli_fetch_assoc($q_all_foto)): ?>
                            <?php
                            $path = "../asset/gmbr/" . $f['gambar'];
                            if (!file_exists($path)) $path = "../asset/img/" . $f['gambar'];
                            ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-3">
                                    <img src="<?= htmlspecialchars($path) ?>" alt="Cover" class="w-12 h-10 object-cover rounded-lg border" onerror="this.src='../asset/gmbr/diskeb.png';">
                                </td>
                                <td class="px-5 py-3 font-bold text-slate-900"><?= htmlspecialchars($f['judul']) ?></td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700"><?= htmlspecialchars($f['kategori']) ?></span>
                                </td>
                                <td class="px-5 py-3 text-slate-500"><?= date('d M Y', strtotime($f['tanggal'])) ?></td>
                                <td class="px-5 py-3 text-center">
                                    <a href="index.php?halaman=galeri&del_foto=<?= $f['id'] ?>" onclick="return confirm('Hapus foto ini?')" class="px-2.5 py-1 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 transition">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="px-5 py-6 text-center text-slate-400">Belum ada foto di galeri.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Daftar Video Aktif -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Koleksi Video Galeri</h3>
            <span class="text-xs text-slate-400">Total <?= mysqli_num_rows($q_all_vid) ?> video terdaftar</span>
        </div>
        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3">Judul Video</th>
                        <th class="px-5 py-3 w-36">Video ID</th>
                        <th class="px-5 py-3 w-28">Tanggal</th>
                        <th class="px-5 py-3 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (mysqli_num_rows($q_all_vid) > 0): ?>
                        <?php while ($v = mysqli_fetch_assoc($q_all_vid)): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-3">
                                    <strong class="font-bold text-slate-900 block"><?= htmlspecialchars($v['judul']) ?></strong>
                                    <span class="text-slate-400 text-[11px] truncate max-w-sm block"><?= htmlspecialchars($v['deskripsi']) ?></span>
                                </td>
                                <td class="px-5 py-3 font-mono text-slate-600">
                                    <a href="https://youtu.be/<?= htmlspecialchars($v['youtube_id']) ?>" target="_blank" class="text-rose-600 hover:underline">
                                        <i class="bi bi-play-btn mr-1"></i> <?= htmlspecialchars($v['youtube_id']) ?>
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-slate-500"><?= date('d M Y', strtotime($v['tanggal'])) ?></td>
                                <td class="px-5 py-3 text-center">
                                    <a href="index.php?halaman=galeri&del_video=<?= $v['id'] ?>" onclick="return confirm('Hapus video ini?')" class="px-2.5 py-1 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 transition">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="px-5 py-6 text-center text-slate-400">Belum ada video di galeri.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
