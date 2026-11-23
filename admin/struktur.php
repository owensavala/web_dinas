<?php
require_once __DIR__ . '/koneksi.php';

$id = "";
$nama_pejabat = "";
$jabatan = "";
$sukses = "";
$error = "";

// Handle Hapus
if (isset($_GET['op']) && $_GET['op'] == 'delete' && !empty($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    $stmt_del = mysqli_prepare($koneksi, "DELETE FROM pejabat_struktural WHERE id = ?");
    mysqli_stmt_bind_param($stmt_del, "i", $del_id);
    if (mysqli_stmt_execute($stmt_del)) {
        $sukses = "Data pejabat berhasil dihapus.";
    } else {
        $error = "Gagal menghapus data: " . mysqli_error($koneksi);
    }
}

// Handle Edit Mode
if (isset($_GET['op']) && $_GET['op'] == 'edit' && !empty($_GET['id'])) {
    $edit_id = (int)$_GET['id'];
    $stmt_get = mysqli_prepare($koneksi, "SELECT * FROM pejabat_struktural WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt_get, "i", $edit_id);
    mysqli_stmt_execute($stmt_get);
    $res = mysqli_stmt_get_result($stmt_get);
    if ($row = mysqli_fetch_assoc($res)) {
        $id = $row['id'];
        $nama_pejabat = $row['nama_pejabat'];
        $jabatan = $row['jabatan'];
    }
}

// Handle Form Submit
if (isset($_POST['simpan'])) {
    $nama_pejabat = trim($_POST['nama_pejabat'] ?? '');
    $jabatan      = trim($_POST['jabatan'] ?? '');
    $form_id      = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if (!empty($nama_pejabat) && !empty($jabatan)) {
        if ($form_id > 0) {
            $stmt_up = mysqli_prepare($koneksi, "UPDATE pejabat_struktural SET nama_pejabat = ?, jabatan = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt_up, "ssi", $nama_pejabat, $jabatan, $form_id);
            if (mysqli_stmt_execute($stmt_up)) {
                $sukses = "Data pejabat berhasil diperbarui.";
                $id = "";
                $nama_pejabat = "";
                $jabatan = "";
            } else {
                $error = "Gagal memperbarui: " . mysqli_error($koneksi);
            }
        } else {
            $stmt_in = mysqli_prepare($koneksi, "INSERT INTO pejabat_struktural (nama_pejabat, jabatan) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt_in, "ss", $nama_pejabat, $jabatan);
            if (mysqli_stmt_execute($stmt_in)) {
                $sukses = "Data pejabat berhasil ditambahkan.";
                $nama_pejabat = "";
                $jabatan = "";
            } else {
                $error = "Gagal menambahkan: " . mysqli_error($koneksi);
            }
        }
    } else {
        $error = "Silakan lengkapi nama pejabat dan jabatan struktural.";
    }
}

$sql_pejabat = "SELECT * FROM pejabat_struktural ORDER BY id ASC";
$result_pejabat = mysqli_query($koneksi, $sql_pejabat);
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

    <!-- Form Input / Edit Pejabat -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
        <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-700">Aparatur Dinas</span>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    <?= (!empty($id)) ? 'Edit Pejabat Struktural' : 'Tambah Pejabat Struktural Baru' ?>
                </h3>
            </div>
            <?php if (!empty($id)): ?>
                <a href="index.php?halaman=struktur" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                    <i class="bi bi-plus-lg mr-1"></i> Mode Tambah Baru
                </a>
            <?php endif; ?>
        </div>

        <form action="" method="POST" class="space-y-5">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Pejabat</label>
                    <input type="text" name="nama_pejabat" value="<?= htmlspecialchars($nama_pejabat) ?>" required placeholder="Contoh: Drs. H. Ngatmin, MA" class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-3 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jabatan Struktural</label>
                    <input type="text" name="jabatan" value="<?= htmlspecialchars($jabatan) ?>" required placeholder="Contoh: Kepala Sub Bagian Tata Usaha" class="w-full text-xs rounded-xl border-slate-200 focus:border-brand-600 focus:ring-brand-600 p-3 font-semibold text-slate-900">
                </div>
            </div>

            <div class="pt-2 flex items-center space-x-3">
                <button type="submit" name="simpan" class="px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow transition transform hover:-translate-y-0.5">
                    <i class="bi bi-check2-circle mr-1.5"></i> <?= (!empty($id)) ? 'Simpan Perubahan' : 'Tambahkan Pejabat' ?>
                </button>
                <?php if (!empty($id)): ?>
                    <a href="index.php?halaman=struktur" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                        Batal
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Pejabat -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-4">
        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Daftar Pejabat Struktural Terdaftar</h3>
            <span class="text-xs text-slate-400">Total <?= mysqli_num_rows($result_pejabat) ?> pejabat</span>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-100 shadow-sm">
            <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3.5 w-16">No</th>
                        <th class="px-5 py-3.5">Nama Pejabat</th>
                        <th class="px-5 py-3.5">Jabatan Struktural</th>
                        <th class="px-5 py-3.5 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (mysqli_num_rows($result_pejabat) > 0): ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result_pejabat)): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-3.5 font-bold text-slate-400"><?= $no++ ?></td>
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-900 text-sm flex items-center space-x-2">
                                        <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center text-xs"><i class="bi bi-person-fill"></i></span>
                                        <span><?= htmlspecialchars($row['nama_pejabat']) ?></span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        <?= htmlspecialchars($row['jabatan']) ?>
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="index.php?halaman=struktur&op=edit&id=<?= $row['id'] ?>" class="px-2.5 py-1 rounded-lg text-xs font-bold text-brand-800 bg-brand-50 hover:bg-brand-100 transition">
                                            <i class="bi bi-pencil-square mr-1"></i> Edit
                                        </a>
                                        <a href="index.php?halaman=struktur&op=delete&id=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data pejabat ini?')" class="px-2.5 py-1 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 transition">
                                            <i class="bi bi-trash mr-1"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada pejabat struktural yang terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
