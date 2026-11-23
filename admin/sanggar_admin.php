<?php
require_once __DIR__ . '/koneksi.php';

$sukses = "";
$error  = "";

// Ubah Status Verifikasi Sanggar
if (isset($_POST['ubah_status'])) {
    $sanggar_id = (int)$_POST['sanggar_id'];
    $status_baru = $_POST['status_verifikasi'];
    $catatan     = trim($_POST['catatan_verifikasi'] ?? '');

    $stmt_up = mysqli_prepare($koneksi, "UPDATE sanggar_budaya SET status_verifikasi = ?, catatan_verifikasi = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt_up, "ssi", $status_baru, $catatan, $sanggar_id);
    if (mysqli_stmt_execute($stmt_up)) {
        $sukses = "Status verifikasi sanggar berhasil diperbarui menjadi '$status_baru'.";
    } else {
        $error = "Gagal memperbarui status: " . mysqli_error($koneksi);
    }
}

// Tambah Sanggar Baru dari Admin
if (isset($_POST['tambah_sanggar'])) {
    $nama_sanggar   = trim($_POST['nama_sanggar'] ?? '');
    $nama_ketua     = trim($_POST['nama_ketua'] ?? '');
    $alamat         = trim($_POST['alamat'] ?? '');
    $kelurahan      = trim($_POST['kelurahan'] ?? '');
    $kecamatan      = trim($_POST['kecamatan'] ?? 'Ternate Tengah');
    $jenis_kesenian = trim($_POST['jenis_kesenian'] ?? 'Tari Tradisional');
    $jumlah_anggota = (int)($_POST['jumlah_anggota'] ?? 10);
    $no_hp          = trim($_POST['no_hp'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $tahun_berdiri  = trim($_POST['tahun_berdiri'] ?? '');

    if (!empty($nama_sanggar) && !empty($nama_ketua)) {
        $stmt_ins = mysqli_prepare($koneksi, "INSERT INTO sanggar_budaya (nama_sanggar, nama_ketua, alamat, kelurahan, kecamatan, jenis_kesenian, jumlah_anggota, no_hp, email, tahun_berdiri, status_verifikasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Terverifikasi')");
        mysqli_stmt_bind_param($stmt_ins, "ssssssisss", $nama_sanggar, $nama_ketua, $alamat, $kelurahan, $kecamatan, $jenis_kesenian, $jumlah_anggota, $no_hp, $email, $tahun_berdiri);
        if (mysqli_stmt_execute($stmt_ins)) {
            $sukses = "Sanggar budaya baru berhasil ditambahkan dan langsung terverifikasi.";
        } else {
            $error = "Gagal menambah sanggar: " . mysqli_error($koneksi);
        }
    }
}

// Hapus Sanggar
if (isset($_GET['action']) && $_GET['action'] === 'hapus' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    $stmt_del = mysqli_prepare($koneksi, "DELETE FROM sanggar_budaya WHERE id = ?");
    mysqli_stmt_bind_param($stmt_del, "i", $del_id);
    if (mysqli_stmt_execute($stmt_del)) {
        $sukses = "Data sanggar berhasil dihapus dari sistem.";
    }
}

$q_all_sgr = mysqli_query($koneksi, "SELECT * FROM sanggar_budaya ORDER BY id DESC");
$total_sanggar = mysqli_num_rows($q_all_sgr);
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

    <!-- Header Action -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-700">SPBE E-Layanan Budaya</span>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Manajemen & Verifikasi Sanggar Seni Budaya</h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data direktori komunitas pelestari kesenian dan verifikasi pendaftaran mandiri warga.</p>
        </div>
        <button onclick="document.getElementById('modalTambahSanggar').classList.toggle('hidden')" class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow transition transform hover:-translate-y-0.5 shrink-0">
            <i class="bi bi-plus-circle-fill mr-1.5"></i> Tambah Sanggar Resmi
        </button>
    </div>

    <!-- Modal Form Tambah Sanggar Admin -->
    <div id="modalTambahSanggar" class="hidden bg-slate-900/60 fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-4 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h4 class="font-extrabold text-slate-900 text-base">Tambah Sanggar Seni Binaan Baru</h4>
                <button onclick="document.getElementById('modalTambahSanggar').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="" method="POST" class="space-y-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Sanggar</label>
                    <input type="text" name="nama_sanggar" required placeholder="Contoh: Sanggar Seni Kieraha" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Pimpinan / Ketua</label>
                        <input type="text" name="nama_ketua" required class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Kesenian</label>
                        <input type="text" name="jenis_kesenian" required placeholder="Tari / Musik / Teater" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kecamatan</label>
                        <input type="text" name="kecamatan" value="Ternate Tengah" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kelurahan</label>
                        <input type="text" name="kelurahan" required class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Lengkap</label>
                    <input type="text" name="alamat" required class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. WhatsApp</label>
                        <input type="tel" name="no_hp" required placeholder="08xxxxxxxxxx" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jumlah Anggota</label>
                        <input type="number" name="jumlah_anggota" value="20" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tahun Berdiri</label>
                        <input type="text" name="tahun_berdiri" value="2020" class="w-full text-xs rounded-xl border-slate-200 p-2.5">
                    </div>
                </div>
                <div class="pt-3">
                    <button type="submit" name="tambah_sanggar" class="w-full py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow">
                        Simpan & Publikasikan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Sanggar -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-4 py-3 w-12">No</th>
                        <th class="px-4 py-3">Nama Sanggar & Pimpinan</th>
                        <th class="px-4 py-3">Kesenian</th>
                        <th class="px-4 py-3">Wilayah</th>
                        <th class="px-4 py-3">Kontak WhatsApp</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center w-36">Aksi & Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if ($total_sanggar > 0): ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($q_all_sgr)): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5 font-bold text-slate-400"><?= $no++ ?></td>
                                <td class="px-4 py-3.5">
                                    <strong class="font-bold text-slate-900 block"><?= htmlspecialchars($row['nama_sanggar']) ?></strong>
                                    <span class="text-[11px] text-slate-400">Ketua: <?= htmlspecialchars($row['nama_ketua']) ?> (<?= (int)$row['jumlah_anggota'] ?> org)</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-50 text-brand-800 border border-brand-200">
                                        <?= htmlspecialchars($row['jenis_kesenian']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-slate-500">
                                    <?= htmlspecialchars($row['kelurahan']) ?>, <?= htmlspecialchars($row['kecamatan']) ?>
                                </td>
                                <td class="px-4 py-3.5">
                                    <?php if (!empty($row['no_hp'])): ?>
                                        <a href="https://api.whatsapp.com/send?phone=<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $row['no_hp'])) ?>" target="_blank" class="inline-flex items-center text-emerald-700 hover:underline font-semibold">
                                            <i class="bi bi-whatsapp mr-1 text-emerald-600"></i> <?= htmlspecialchars($row['no_hp']) ?>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <?php if ($row['status_verifikasi'] === 'Terverifikasi'): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Terverifikasi</span>
                                    <?php elseif ($row['status_verifikasi'] === 'Menunggu'): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">Menunggu</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <form action="" method="POST" class="inline-flex items-center space-x-1">
                                        <input type="hidden" name="sanggar_id" value="<?= $row['id'] ?>">
                                        <?php if ($row['status_verifikasi'] !== 'Terverifikasi'): ?>
                                            <button type="submit" name="ubah_status" onclick="this.form.status_verifikasi.value='Terverifikasi'" class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 transition" title="Setujui Sanggar">
                                                <i class="bi bi-check-lg text-base"></i>
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($row['status_verifikasi'] !== 'Ditolak'): ?>
                                            <button type="submit" name="ubah_status" onclick="this.form.status_verifikasi.value='Ditolak'" class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50 transition" title="Tolak">
                                                <i class="bi bi-x-lg text-base"></i>
                                            </button>
                                        <?php endif; ?>
                                        <input type="hidden" name="status_verifikasi" value="">
                                        <a href="index.php?halaman=sanggar_admin&action=hapus&id=<?= $row['id'] ?>" onclick="return confirm('Hapus permanen sanggar ini?');" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
