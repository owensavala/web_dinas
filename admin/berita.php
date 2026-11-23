<?php
require_once __DIR__ . '/koneksi.php';

$judul          = "";
$gambar         = "";
$tanggal        = date('Y-m-d');
$error          = "";
$sukses         = "";
$gambar_name    = "";
$file_name      = "";

// Hapus Warta
if (isset($_GET['op']) && $_GET['op'] == 'delete' && !empty($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    $stmt_del = mysqli_prepare($koneksi, "DELETE FROM agenda_b WHERE id = ?");
    mysqli_stmt_bind_param($stmt_del, "i", $del_id);
    if (mysqli_stmt_execute($stmt_del)) {
        $sukses = "Warta berhasil dihapus dari sistem.";
    } else {
        $error = "Gagal menghapus warta: " . mysqli_error($koneksi);
    }
}

// Edit Mode
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $stmt_get = mysqli_prepare($koneksi, "SELECT * FROM agenda_b WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt_get, "i", $id);
    mysqli_stmt_execute($stmt_get);
    $res_get = mysqli_stmt_get_result($stmt_get);
    if ($row_edit = mysqli_fetch_assoc($res_get)) {
        $judul     = $row_edit['judul'];
        $gambar    = $row_edit['gambar'];
        $tanggal   = $row_edit['tanggal'];
        $file_name = $row_edit['file_pdf'];
    }
}

// Simpan Data (Tambah / Update)
if (isset($_POST['simpan'])) {
    $judul   = trim($_POST['judul'] ?? '');
    $tanggal = $_POST['tanggal'] ?? date('Y-m-d');

    if (empty($judul)) {
        $error = "Judul warta wajib diisi.";
    }

    // Validasi Upload Gambar
    $new_gambar = $gambar;
    if (!empty($_FILES['gambar']['name'])) {
        $gambar_file = $_FILES['gambar']['tmp_name'];
        $orig_name   = $_FILES['gambar']['name'];
        $ext         = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
        $valid_ext   = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $valid_ext)) {
            $error = "Format gambar harus JPG, JPEG, PNG, atau WEBP.";
        } else {
            $folder = "../asset/img";
            if (!is_dir($folder)) { mkdir($folder, 0777, true); }
            $new_gambar = 'agenda' . time() . '_' . preg_replace('/[^a-zA-Z0-9_\.]/', '', $orig_name);
            move_uploaded_file($gambar_file, $folder . "/" . $new_gambar);
        }
    }

    // Validasi Upload PDF
    $new_pdf = $file_name;
    if (!empty($_FILES['pdf_file']['name'])) {
        $pdf_tmp   = $_FILES['pdf_file']['tmp_name'];
        $orig_pdf  = $_FILES['pdf_file']['name'];
        $pdf_ext   = strtolower(pathinfo($orig_pdf, PATHINFO_EXTENSION));

        if ($pdf_ext !== 'pdf') {
            $error = "File dokumen lampiran harus berformat PDF.";
        } else {
            $folder_pdf = "../asset/file";
            if (!is_dir($folder_pdf)) { mkdir($folder_pdf, 0777, true); }
            $new_pdf = 'agenda' . time() . '_' . preg_replace('/[^a-zA-Z0-9_\.]/', '', $orig_pdf);
            move_uploaded_file($pdf_tmp, $folder_pdf . "/" . $new_pdf);
        }
    }

    // Eksekusi DB
    if (empty($error)) {
        if ($id > 0) {
            $stmt_up = mysqli_prepare($koneksi, "UPDATE agenda_b SET judul = ?, gambar = ?, file_pdf = ?, tanggal = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt_up, "ssssi", $judul, $new_gambar, $new_pdf, $tanggal, $id);
            if (mysqli_stmt_execute($stmt_up)) {
                $sukses = "Warta berhasil diperbarui.";
                $gambar = $new_gambar;
                $file_name = $new_pdf;
            } else {
                $error = "Gagal memperbarui warta: " . mysqli_error($koneksi);
            }
        } else {
            $stmt_in = mysqli_prepare($koneksi, "INSERT INTO agenda_b (judul, gambar, file_pdf, tanggal) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt_in, "ssss", $judul, $new_gambar, $new_pdf, $tanggal);
            if (mysqli_stmt_execute($stmt_in)) {
                $sukses = "Warta baru berhasil dipublikasikan.";
                $judul = "";
                $gambar = "";
                $file_name = "";
                $id = 0;
            } else {
                $error = "Gagal menambahkan warta: " . mysqli_error($koneksi);
            }
        }
    }
}

// Ambil seluruh warta untuk tabel
$q_list = mysqli_query($koneksi, "SELECT * FROM agenda_b ORDER BY id DESC");
?>

<div class="space-y-8">
    <!-- Feedback Alerts -->
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

    <!-- Form Input / Edit Warta -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
        <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-700">Formulir Publikasi</span>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    <?= ($id > 0) ? 'Edit Warta / Agenda Budaya' : 'Tambah Warta & Agenda Baru' ?>
                </h3>
            </div>
            <?php if ($id > 0): ?>
                <a href="index.php?halaman=berita" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                    <i class="bi bi-plus-lg mr-1"></i> Mode Tambah Baru
                </a>
            <?php endif; ?>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Warta / Kegiatan</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($judul) ?>" required placeholder="Contoh: Pembukaan Festival Budaya Ela-Ela Tahun 2026" class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-3 font-semibold text-slate-800">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Pelaksanaan / Rilis</label>
                    <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>" required class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Cover Gambar (Foto)</label>
                    <input type="file" name="gambar" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-800 hover:file:bg-brand-100 border border-slate-200 rounded-xl p-1.5">
                    <?php if (!empty($gambar)): ?>
                        <span class="text-[11px] text-slate-400 mt-1 block truncate">Cover saat ini: <?= htmlspecialchars($gambar) ?></span>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Lampiran Dokumen PDF (Opsional)</label>
                    <input type="file" name="pdf_file" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-800 hover:file:bg-brand-100 border border-slate-200 rounded-xl p-1.5">
                    <?php if (!empty($file_name)): ?>
                        <span class="text-[11px] text-slate-400 mt-1 block truncate">PDF saat ini: <?= htmlspecialchars($file_name) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="pt-2 flex items-center space-x-3">
                <button type="submit" name="simpan" class="px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow transition transform hover:-translate-y-0.5">
                    <i class="bi bi-check2-circle mr-1.5"></i> <?= ($id > 0) ? 'Perbarui Warta' : 'Publikasikan Warta' ?>
                </button>
                <?php if ($id > 0): ?>
                    <a href="index.php?halaman=berita" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                        Batal
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Seluruh Warta -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-4">
        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Katalog Seluruh Warta & Agenda</h3>
            <span class="text-xs text-slate-400">Total <?= mysqli_num_rows($q_list) ?> publikasi</span>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-100 shadow-sm">
            <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3.5 w-16">Cover</th>
                        <th class="px-5 py-3.5">Judul Warta</th>
                        <th class="px-5 py-3.5 w-32">Tanggal</th>
                        <th class="px-5 py-3.5 w-32">Berkas PDF</th>
                        <th class="px-5 py-3.5 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (mysqli_num_rows($q_list) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($q_list)): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-3.5">
                                    <img src="../asset/img/<?= htmlspecialchars($row['gambar'] ?: 'diskeb.png') ?>" alt="Cover" class="w-12 h-10 object-cover rounded-lg border" onerror="this.src='../asset/gmbr/diskeb.png';">
                                </td>
                                <td class="px-5 py-3.5">
                                    <strong class="font-bold text-slate-900 text-sm block"><?= htmlspecialchars($row['judul']) ?></strong>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500">
                                    <i class="bi bi-calendar-event text-brand-700 mr-1"></i> <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                </td>
                                <td class="px-5 py-3.5">
                                    <?php if (!empty($row['file_pdf'])): ?>
                                        <a href="../asset/file/<?= htmlspecialchars($row['file_pdf']) ?>" target="_blank" class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 transition">
                                            <i class="bi bi-file-pdf mr-1"></i> Buka PDF
                                        </a>
                                    <?php else: ?>
                                        <span class="text-slate-400 italic text-[11px]">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="index.php?halaman=berita&id=<?= $row['id'] ?>" class="px-2.5 py-1 rounded-lg text-xs font-bold text-brand-800 bg-brand-50 hover:bg-brand-100 transition">
                                            <i class="bi bi-pencil-square mr-1"></i> Edit
                                        </a>
                                        <a href="index.php?halaman=berita&op=delete&id=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus warta ini?')" class="px-2.5 py-1 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 transition">
                                            <i class="bi bi-trash mr-1"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada warta terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
