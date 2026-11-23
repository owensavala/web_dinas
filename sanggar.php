<?php
require_once __DIR__ . '/koneksi.php';

$status_post = "";
$error_post  = "";
$reg_code    = "";

// Handle Formulir Pendaftaran Sanggar Baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['daftar_sanggar'])) {
    $nama_sanggar   = trim($_POST['nama_sanggar'] ?? '');
    $nama_ketua     = trim($_POST['nama_ketua'] ?? '');
    $alamat         = trim($_POST['alamat'] ?? '');
    $kelurahan      = trim($_POST['kelurahan'] ?? '');
    $kecamatan      = trim($_POST['kecamatan'] ?? '');
    $jenis_kesenian = trim($_POST['jenis_kesenian'] ?? '');
    $jumlah_anggota = (int)($_POST['jumlah_anggota'] ?? 0);
    $no_hp          = trim($_POST['no_hp'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $tahun_berdiri  = trim($_POST['tahun_berdiri'] ?? '');

    if (empty($nama_sanggar) || empty($nama_ketua) || empty($no_hp) || empty($jenis_kesenian)) {
        $error_post = "Mohon lengkapi kolom bertanda bintang (*) sebelum mengirim pendaftaran.";
    } else {
        $stmt_sgr = mysqli_prepare($koneksi, "INSERT INTO sanggar_budaya (nama_sanggar, nama_ketua, alamat, kelurahan, kecamatan, jenis_kesenian, jumlah_anggota, no_hp, email, tahun_berdiri, status_verifikasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Menunggu')");
        mysqli_stmt_bind_param($stmt_sgr, "ssssssisss", $nama_sanggar, $nama_ketua, $alamat, $kelurahan, $kecamatan, $jenis_kesenian, $jumlah_anggota, $no_hp, $email, $tahun_berdiri);
        
        if (mysqli_stmt_execute($stmt_sgr)) {
            $status_post = "success";
            $reg_code = "SGR-" . date('Ymd') . "-" . strtoupper(substr(md5(uniqid()), 0, 4));
        } else {
            $error_post = "Gagal memproses pendaftaran sanggar: " . mysqli_error($koneksi);
        }
    }
}

// Filter Direktori Sanggar
$filter_jenis = trim($_GET['jenis'] ?? '');
$params_sgr = [];
$types_sgr  = "";
$where_sgr  = "WHERE status_verifikasi = 'Terverifikasi' ";

if (!empty($filter_jenis) && $filter_jenis !== 'all') {
    $where_sgr .= "AND jenis_kesenian LIKE ? ";
    $params_sgr[] = "%" . $filter_jenis . "%";
    $types_sgr .= "s";
}

$sql_sanggar = "SELECT * FROM sanggar_budaya " . $where_sgr . " ORDER BY id DESC";
$stmt_list = mysqli_prepare($koneksi, $sql_sanggar);
if (!empty($params_sgr)) {
    mysqli_stmt_bind_param($stmt_list, $types_sgr, ...$params_sgr);
}
mysqli_stmt_execute($stmt_list);
$res_sanggar = mysqli_stmt_get_result($stmt_list);
$total_sanggar_verified = mysqli_num_rows($res_sanggar);
?>

<div class="space-y-8">
    <!-- Banner Header -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-5">
            <div>
                <span class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-bold bg-gold-50 dark:bg-gold-950/60 text-gold-700 dark:text-gold-400 border border-gold-200/50 mb-2">
                    <i class="bi bi-people-fill"></i>
                    <span>E-Layanan Pemberdayaan Komunitas Seni & Budaya</span>
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight font-serif">
                    Direktori & Fasilitasi Sanggar Budaya Kota Ternate
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-2xl leading-relaxed">
                    Pendataan resmi sanggar seni tari, musik tradisional, teater tutur, dan komunitas pelestari adat di bawah binaan Dinas Kebudayaan Kota Ternate.
                </p>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <a href="#form-daftar" class="inline-flex items-center px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-800 hover:bg-brand-900 shadow-md transition transform hover:-translate-y-0.5">
                    <i class="bi bi-plus-circle-fill mr-1.5"></i> Daftarkan Sanggar Baru
                </a>
            </div>
        </div>

        <!-- Filter Tab Kategori Kesenian -->
        <div class="flex flex-wrap items-center gap-2 pt-4">
            <span class="text-xs font-bold text-slate-400 mr-2 uppercase tracking-wider">Kategori Seni:</span>
            <a href="index.php?halaman=sanggar" class="px-3 py-1.5 rounded-lg text-xs font-bold transition <?= empty($filter_jenis) || $filter_jenis === 'all' ? 'bg-brand-800 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-50' ?>">
                Semua Kesenian
            </a>
            <a href="index.php?halaman=sanggar&jenis=Tari" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= $filter_jenis === 'Tari' ? 'bg-brand-800 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-50' ?>">
                Tari Tradisional
            </a>
            <a href="index.php?halaman=sanggar&jenis=Musik" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= $filter_jenis === 'Musik' ? 'bg-brand-800 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-50' ?>">
                Musik Tradisional
            </a>
            <a href="index.php?halaman=sanggar&jenis=Teater" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= $filter_jenis === 'Teater' ? 'bg-brand-800 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-50' ?>">
                Teater & Sastra Tutur
            </a>
            <a href="index.php?halaman=sanggar&jenis=Kriya" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= $filter_jenis === 'Kriya' ? 'bg-brand-800 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-50' ?>">
                Kriya & Tenun
            </a>
        </div>
    </div>

    <!-- Feedback Notifikasi Sukses Pendaftaran -->
    <?php if ($status_post === "success"): ?>
        <div class="p-6 rounded-3xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200 space-y-3">
            <div class="flex items-center space-x-3">
                <i class="bi bi-check-circle-fill text-emerald-600 text-3xl shrink-0"></i>
                <div>
                    <h4 class="font-extrabold text-base">Pendaftaran Sanggar Berhasil Dikirimkan!</h4>
                    <p class="text-xs text-emerald-700 dark:text-emerald-300 mt-0.5">Berkas sanggar Anda sedang diverifikasi oleh Tim Teknis Dinas Kebudayaan Kota Ternate.</p>
                </div>
            </div>
            <div class="p-4 bg-white dark:bg-slate-900 rounded-2xl border border-emerald-200 dark:border-emerald-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Kode Registrasi Permohonan:</span>
                    <strong class="font-mono text-base text-brand-800 dark:text-brand-400 font-bold select-all"><?= htmlspecialchars($reg_code) ?></strong>
                </div>
                <button onclick="navigator.clipboard.writeText('<?= htmlspecialchars($reg_code) ?>'); alert('Kode registrasi berhasil disalin!');" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-200 transition">
                    <i class="bi bi-copy mr-1"></i> Salin Kode
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_post)): ?>
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 flex items-center space-x-3 text-xs">
            <i class="bi bi-exclamation-triangle-fill text-rose-600 text-lg shrink-0"></i>
            <div><?= htmlspecialchars($error_post) ?></div>
        </div>
    <?php endif; ?>

    <!-- Direktori Grid Sanggar Terverifikasi -->
    <div>
        <div class="flex items-center justify-between mb-4 px-1">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center space-x-2">
                <span>Daftar Sanggar Terdaftar Resmi</span>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                    <?= $total_sanggar_verified ?> Terverifikasi
                </span>
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php if ($total_sanggar_verified > 0): ?>
                <?php while ($sgr = mysqli_fetch_assoc($res_sanggar)): ?>
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-card-hover hover:border-gold-300 dark:hover:border-gold-500 transition duration-300 flex flex-col justify-between group">
                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/50">
                                        <?= htmlspecialchars($sgr['jenis_kesenian']) ?>
                                    </span>
                                    <h4 class="text-base font-extrabold text-slate-900 dark:text-white mt-1.5 group-hover:text-brand-700 dark:group-hover:text-gold-400 transition">
                                        <?= htmlspecialchars($sgr['nama_sanggar']) ?>
                                    </h4>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 shrink-0">
                                    <i class="bi bi-patch-check-fill mr-1"></i> Terbina
                                </span>
                            </div>

                            <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center">
                                <i class="bi bi-person-fill text-slate-400 mr-1.5"></i>
                                <span>Ketua / Pimpinan: <strong><?= htmlspecialchars($sgr['nama_ketua']) ?></strong></span>
                            </p>

                            <p class="text-xs text-slate-500 dark:text-slate-400 flex items-start">
                                <i class="bi bi-geo-alt-fill text-rose-500 mr-1.5 shrink-0 mt-0.5"></i>
                                <span><?= htmlspecialchars($sgr['alamat']) ?>, Kel. <?= htmlspecialchars($sgr['kelurahan']) ?>, <?= htmlspecialchars($sgr['kecamatan']) ?></span>
                            </p>
                        </div>

                        <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                            <span class="text-slate-400">
                                <i class="bi bi-people mr-1"></i> <?= (int)$sgr['jumlah_anggota'] ?> Anggota Aktif
                            </span>
                            <?php if (!empty($sgr['no_hp'])): ?>
                                <a href="https://api.whatsapp.com/send?phone=<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $sgr['no_hp'])) ?>" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-lg text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100 transition font-semibold">
                                    <i class="bi bi-whatsapp mr-1.5"></i> Hubungi Sanggar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-2 bg-white dark:bg-slate-900 rounded-3xl p-8 text-center text-slate-400 border border-slate-100 dark:border-slate-800">
                    <i class="bi bi-inbox text-3xl block mb-2 opacity-50"></i>
                    Belum ada sanggar terdaftar untuk kategori ini.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Formulir Pendaftaran Mandiri Sanggar Baru -->
    <div id="form-daftar" class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800 space-y-6">
        <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-700 dark:text-brand-400">Layanan Fasilitasi</span>
            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">Formulir Pendaftaran Komunitas / Sanggar Budaya Baru</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftarkan sanggar Anda untuk mendapatkan pembinaan resmi, fasilitasi pementasan, dan sertifikasi dinas.</p>
        </div>

        <form action="#form-daftar" method="POST" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Sanggar / Komunitas *</label>
                    <input type="text" name="nama_sanggar" required placeholder="Contoh: Sanggar Seni Kieraha" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Ketua / Penanggung Jawab *</label>
                    <input type="text" name="nama_ketua" required placeholder="Nama lengkap pimpinan sanggar" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Jenis Kesenian *</label>
                    <select name="jenis_kesenian" required class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                        <option value="">Pilih Kategori Seni</option>
                        <option value="Tari Tradisional (Soya-Soya, Lala, Salai)">Tari Tradisional</option>
                        <option value="Musik Tradisional / Gambus Ternate">Musik Tradisional</option>
                        <option value="Teater / Sastra Tutur Daerah">Teater / Sastra Tutur</option>
                        <option value="Kriya Busana & Kerajinan Bambu">Kriya & Busana Adat</option>
                        <option value="Pelestari Cagar Budaya & Sejarah">Komunitas Cagar Budaya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kecamatan *</label>
                    <select name="kecamatan" required class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                        <option value="Ternate Utara">Ternate Utara</option>
                        <option value="Ternate Tengah">Ternate Tengah</option>
                        <option value="Ternate Selatan">Ternate Selatan</option>
                        <option value="Ternate Pulau">Ternate Pulau</option>
                        <option value="Pulau Moti">Pulau Moti</option>
                        <option value="Pulau Hiri">Pulau Hiri</option>
                        <option value="Batang Dua">Batang Dua</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kelurahan *</label>
                    <input type="text" name="kelurahan" required placeholder="Contoh: Salero, Soa-Sio, Takoma" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Alamat Sekretariat / Sanggar *</label>
                <textarea name="alamat" rows="2" required placeholder="Alamat jalan atau nomor fola sekretariat sanggar..." class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">No. WhatsApp / HP *</label>
                    <input type="tel" name="no_hp" required placeholder="08xxxxxxxxxx" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Email Komunitas (Opsional)</label>
                    <input type="email" name="email" placeholder="sanggar@gmail.com" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Jumlah Anggota Aktif</label>
                    <input type="number" name="jumlah_anggota" min="1" value="15" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" name="daftar_sanggar" class="w-full sm:w-auto px-8 py-3 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow-md transition transform hover:-translate-y-0.5">
                    <i class="bi bi-send-check-fill mr-1.5"></i> Kirim Permohonan Pendaftaran Sanggar
                </button>
            </div>
        </form>
    </div>
</div>
