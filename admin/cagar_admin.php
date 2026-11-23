<?php
require_once __DIR__ . '/koneksi.php';

$sukses = "";
$error  = "";

// Handle Simpan / Update Cagar Budaya
if (isset($_POST['simpan_cagar'])) {
    $cagar_id  = (int)($_POST['cagar_id'] ?? 0);
    $nama      = trim($_POST['nama'] ?? '');
    $kategori  = trim($_POST['kategori'] ?? 'Benteng Bersejarah');
    $lokasi    = trim($_POST['lokasi'] ?? '');
    $lat       = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
    $lng       = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;
    $era       = trim($_POST['era_sejarah'] ?? 'Abad ke-16');
    $status_p  = trim($_POST['status_penetapan'] ?? 'Cagar Budaya Nasional');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    // Handle Upload Gambar jika ada
    $gambar = "";
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $new_name = "cagar_" . time() . "_" . uniqid() . "." . $ext;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . "/../asset/gmbr/" . $new_name)) {
                $gambar = $new_name;
            }
        }
    }

    if ($cagar_id > 0) {
        // Update
        if (!empty($gambar)) {
            $stmt = mysqli_prepare($koneksi, "UPDATE cagar_budaya SET nama = ?, kategori = ?, lokasi = ?, latitude = ?, longitude = ?, era_sejarah = ?, status_penetapan = ?, deskripsi = ?, gambar = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sssddssssi", $nama, $kategori, $lokasi, $lat, $lng, $era, $status_p, $deskripsi, $gambar, $cagar_id);
        } else {
            $stmt = mysqli_prepare($koneksi, "UPDATE cagar_budaya SET nama = ?, kategori = ?, lokasi = ?, latitude = ?, longitude = ?, era_sejarah = ?, status_penetapan = ?, deskripsi = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sssddsssi", $nama, $kategori, $lokasi, $lat, $lng, $era, $status_p, $deskripsi, $cagar_id);
        }
        if (mysqli_stmt_execute($stmt)) {
            $sukses = "Data situs cagar budaya berhasil diperbarui.";
        } else {
            $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
        }
    } else {
        // Insert Baru
        $img_final = !empty($gambar) ? $gambar : 'diskeb.png';
        $stmt = mysqli_prepare($koneksi, "INSERT INTO cagar_budaya (nama, kategori, lokasi, latitude, longitude, era_sejarah, status_penetapan, deskripsi, gambar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssddssss", $nama, $kategori, $lokasi, $lat, $lng, $era, $status_p, $deskripsi, $img_final);
        if (mysqli_stmt_execute($stmt)) {
            $sukses = "Situs cagar budaya baru berhasil ditambahkan ke WebGIS dan katalog.";
        } else {
            $error = "Gagal menambahkan cagar budaya: " . mysqli_error($koneksi);
        }
    }
}

// Hapus Cagar Budaya
if (isset($_GET['action']) && $_GET['action'] === 'hapus' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    $stmt_del = mysqli_prepare($koneksi, "DELETE FROM cagar_budaya WHERE id = ?");
    mysqli_stmt_bind_param($stmt_del, "i", $del_id);
    if (mysqli_stmt_execute($stmt_del)) {
        $sukses = "Situs cagar budaya berhasil dihapus.";
    }
}

$q_cagar_all = mysqli_query($koneksi, "SELECT * FROM cagar_budaya ORDER BY id ASC");
$total_cagar = mysqli_num_rows($q_cagar_all);
?>

<div class="space-y-8">
    <?php if (!empty($sukses)): ?>
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center space-x-3 text-xs">
            <i class="bi bi-check-circle-fill text-emerald-600 text-lg shrink-0"></i>
            <div><?= htmlspecialchars($sukses) ?></div>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center space-x-3 text-xs">
            <i class="bi bi-exclamation-triangle-fill text-rose-600 text-lg shrink-0"></i>
            <div><?= htmlspecialchars($error) ?></div>
        </div>
    <?php endif; ?>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-blue-700">WebGIS & Warisan Budaya</span>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Manajemen Cagar Budaya & Titik Koordinat Peta</h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola informasi situs sejarah, deskripsi narasi, dan koordinat latitude/longitude untuk peta interaktif.</p>
        </div>
        <button onclick="document.getElementById('modalTambahCagar').classList.toggle('hidden')" class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow transition transform hover:-translate-y-0.5 shrink-0">
            <i class="bi bi-plus-circle-fill mr-1.5"></i> Tambah Situs Baru
        </button>
    </div>

    <!-- Modal Form Tambah / Edit Cagar Budaya -->
    <div id="modalTambahCagar" class="hidden bg-slate-900/60 fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 space-y-4 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h4 class="font-extrabold text-slate-900 text-base" id="modalTitle">Tambah Cagar Budaya Baru</h4>
                <button onclick="document.getElementById('modalTambahCagar').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-3">
                <input type="hidden" name="cagar_id" id="formCagarId" value="0">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Cagar Budaya *</label>
                        <input type="text" name="nama" id="formNama" required placeholder="Contoh: Benteng Kalamata" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Situs</label>
                        <select name="kategori" id="formKategori" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                            <option value="Benteng Bersejarah">Benteng Bersejarah</option>
                            <option value="Kedaton Kesultanan">Kedaton Kesultanan</option>
                            <option value="Tempat Ibadah Kuno">Tempat Ibadah Kuno</option>
                            <option value="Situs Alam & Tradisi">Situs Alam & Tradisi</option>
                            <option value="Makam & Prasasti">Makam & Prasasti</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Lokasi / Kelurahan</label>
                        <input type="text" name="lokasi" id="formLokasi" required placeholder="Contoh: Kayu Merah, Ternate Selatan" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Penetapan</label>
                        <input type="text" name="status_penetapan" id="formStatusP" placeholder="Contoh: Cagar Budaya Nasional No. SK 251/M/2013" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Latitude (Lintang)</label>
                        <input type="number" step="any" name="latitude" id="formLat" placeholder="Contoh: 0.761358" class="w-full text-xs rounded-xl border-slate-200 p-2.5 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Longitude (Bujur)</label>
                        <input type="number" step="any" name="longitude" id="formLng" placeholder="Contoh: 127.375253" class="w-full text-xs rounded-xl border-slate-200 p-2.5 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Periode / Era Sejarah</label>
                        <input type="text" name="era_sejarah" id="formEra" placeholder="Contoh: Tahun 1540" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi Narasi Sejarah</label>
                    <textarea name="deskripsi" id="formDeskripsi" rows="3" required placeholder="Uraikan nilai historis, arsitektur, dan fungsi cagar budaya..." class="w-full text-xs rounded-xl border-slate-200 p-2.5"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Foto Dokumentasi (JPG / PNG)</label>
                    <input type="file" name="gambar" accept="image/*" class="w-full text-xs rounded-xl border border-slate-200 p-2">
                </div>

                <div class="pt-3">
                    <button type="submit" name="simpan_cagar" class="w-full py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow">
                        Simpan Data Situs
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Cagar Budaya -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-4 py-3 w-12">No</th>
                        <th class="px-4 py-3">Nama Situs & Foto</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Koordinat WebGIS</th>
                        <th class="px-4 py-3">Status & Era</th>
                        <th class="px-4 py-3 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if ($total_cagar > 0): ?>
                        <?php $no = 1; while ($c = mysqli_fetch_assoc($q_cagar_all)): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5 font-bold text-slate-400"><?= $no++ ?></td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center space-x-3">
                                        <img src="../asset/gmbr/<?= htmlspecialchars($c['gambar']) ?>" alt="" class="w-12 h-12 rounded-xl object-cover shadow-sm shrink-0" onerror="this.src='../asset/gmbr/diskeb.png';">
                                        <div>
                                            <strong class="font-bold text-slate-900 block"><?= htmlspecialchars($c['nama']) ?></strong>
                                            <span class="text-[11px] text-slate-400"><?= htmlspecialchars($c['lokasi']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-50 text-brand-800 border border-brand-200">
                                        <?= htmlspecialchars($c['kategori']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 font-mono text-[11px] text-slate-600">
                                    <?php if ($c['latitude'] && $c['longitude']): ?>
                                        <a href="https://www.google.com/maps?q=<?= $c['latitude'] ?>,<?= $c['longitude'] ?>" target="_blank" class="text-blue-600 hover:underline">
                                            <?= $c['latitude'] ?>, <?= $c['longitude'] ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-slate-300">Belum diset</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3.5 text-slate-500">
                                    <div class="font-semibold text-slate-800"><?= htmlspecialchars($c['era_sejarah']) ?></div>
                                    <div class="text-[10px] text-slate-400"><?= htmlspecialchars($c['status_penetapan']) ?></div>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <a href="index.php?halaman=cagar_admin&action=hapus&id=<?= $c['id'] ?>" onclick="return confirm('Hapus situs cagar budaya ini?');" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
