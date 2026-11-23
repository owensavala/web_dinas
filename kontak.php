<?php
require_once __DIR__ . '/koneksi.php';

$status = "";
$error = "";
$generated_ticket = "";
$lacak_result = null;
$lacak_error = "";

// Kirim Pesan Baru
if (isset($_POST['kirim_pesan'])) {
    $nama     = trim($_POST['nama'] ?? '');
    $telepon  = trim($_POST['telepon'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'Lainnya');
    $pesan    = trim($_POST['pesan'] ?? '');

    if (!empty($nama) && !empty($telepon) && !empty($email) && !empty($pesan)) {
        // Generate Nomor Tiket Unik: DISBUD-YYYYMMDD-XXXX
        $ticket = 'DISBUD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));

        $stmt_msg = mysqli_prepare($koneksi, "INSERT INTO pesan_aduan (nomor_tiket, nama, telepon, email, kategori, pesan) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt_msg) {
            mysqli_stmt_bind_param($stmt_msg, "ssssss", $ticket, $nama, $telepon, $email, $kategori, $pesan);
            if (mysqli_stmt_execute($stmt_msg)) {
                $status = "success";
                $generated_ticket = $ticket;
            } else {
                $error = "Terjadi kesalahan sistem saat menyimpan pesan Anda.";
            }
        }
    } else {
        $error = "Silakan lengkapi seluruh isian formulir.";
    }
}

// Lacak Status Aduan Warga
if (isset($_GET['lacak_tiket'])) {
    $tiket_cari = trim($_GET['lacak_tiket']);
    if (!empty($tiket_cari)) {
        $stmt_trk = mysqli_prepare($koneksi, "SELECT * FROM pesan_aduan WHERE nomor_tiket = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt_trk, "s", $tiket_cari);
        mysqli_stmt_execute($stmt_trk);
        $res_trk = mysqli_stmt_get_result($stmt_trk);
        if ($lacak_result = mysqli_fetch_assoc($res_trk)) {
            // Berhasil ditemukan
        } else {
            $lacak_error = "Nomor tiket registrasi tidak ditemukan. Pastikan nomor tiket yang Anda masukkan sudah benar.";
        }
    }
}
?>

<div class="space-y-8">
    <!-- Formulir Aduan Utama -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800 space-y-6">
        <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-700 dark:text-brand-400">Layanan Aspirasi</span>
            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight font-serif">Kontak & Layanan Pengaduan Masyarakat</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Sampaikan aspirasi, saran, pertanyaan, atau aduan masyarakat secara langsung kepada dinas.</p>
        </div>

        <?php if ($status === "success"): ?>
            <div class="p-6 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200 space-y-3">
                <div class="flex items-center space-x-3">
                    <i class="bi bi-check-circle-fill text-emerald-600 text-2xl shrink-0"></i>
                    <div>
                        <strong class="font-bold text-base block">Aspirasi Berhasil Disampaikan!</strong>
                        <p class="text-xs text-emerald-700 dark:text-emerald-300">Pesan Anda telah tersimpan secara resmi ke sistem Dinas Kebudayaan.</p>
                    </div>
                </div>
                <div class="p-4 bg-white dark:bg-slate-900 rounded-xl border border-emerald-200 dark:border-emerald-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Nomor Registrasi Tiket Anda:</span>
                        <strong class="font-mono text-lg text-brand-800 dark:text-brand-400 font-bold select-all tracking-wider"><?= htmlspecialchars($generated_ticket) ?></strong>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="navigator.clipboard.writeText('<?= htmlspecialchars($generated_ticket) ?>'); alert('Nomor tiket berhasil disalin!');" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-200 transition">
                            <i class="bi bi-copy mr-1"></i> Salin
                        </button>
                        <button onclick="window.print()" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-brand-800 hover:bg-brand-900 text-white transition shadow-sm">
                            <i class="bi bi-printer mr-1"></i> Cetak Bukti
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 flex items-center space-x-3 text-xs">
                <i class="bi bi-exclamation-triangle-fill text-rose-600 text-lg shrink-0"></i>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <!-- Formulir Kirim -->
            <div class="md:col-span-7">
                <form action="" method="POST" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                            <input type="text" name="nama" required placeholder="Nama Anda" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">No. WhatsApp / HP</label>
                            <input type="tel" name="telepon" required placeholder="08xxxxxxxxxx" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Alamat Email</label>
                        <input type="email" name="email" required placeholder="email@contoh.com" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kategori Pelayanan</label>
                        <select name="kategori" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                            <option value="Informasi Cagar Budaya">Informasi Cagar Budaya & Permuseuman</option>
                            <option value="Rekomendasi Pentas Seni">Rekomendasi Izin Pentas Seni / Festival</option>
                            <option value="Pengaduan Masyarakat">Pengaduan Kerusakan / Pelestarian Budaya</option>
                            <option value="Kemitraan Komunitas">Kemitraan Sanggar & Komunitas Seni</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Isi Pesan / Aspirasi</label>
                        <textarea name="pesan" rows="4" required placeholder="Tuliskan pesan Anda secara jelas dan lengkap..." class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5"></textarea>
                    </div>

                    <button type="submit" name="kirim_pesan" class="w-full py-3 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-gradient-to-r from-brand-900 to-brand-700 hover:from-brand-950 hover:to-brand-800 shadow transition transform hover:-translate-y-0.5">
                        <i class="bi bi-send-fill mr-1.5"></i> Kirim Pesan Aspirasi
                    </button>
                </form>
            </div>

            <!-- Panel Lacak Tiket & Info Kantor -->
            <div class="md:col-span-5 space-y-4">
                <!-- Fitur Lacak Tiket Mandiri -->
                <div class="p-5 rounded-2xl bg-brand-50/70 dark:bg-brand-950/40 border border-brand-200/60 dark:border-brand-800/60 space-y-3">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-600"></span>
                        <h4 class="font-bold text-slate-900 dark:text-white text-xs uppercase tracking-wider">Lacak Status Aduan</h4>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Sudah pernah mengirim aduan? Masukkan nomor tiket registrasi Anda untuk melihat status tindak lanjut:
                    </p>
                    <form action="" method="GET" class="space-y-2">
                        <input type="hidden" name="halaman" value="kontak">
                        <div class="flex items-center space-x-2">
                            <input type="text" name="lacak_tiket" required placeholder="DISBUD-2026xxxx-xxxx" value="<?= htmlspecialchars($_GET['lacak_tiket'] ?? '') ?>" class="w-full text-xs rounded-xl bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 font-mono p-2">
                            <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-brand-800 hover:bg-brand-900 transition shrink-0">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <?php if (!empty($lacak_error)): ?>
                        <div class="p-2.5 rounded-lg bg-rose-50 text-rose-700 text-[11px] border border-rose-200">
                            <?= htmlspecialchars($lacak_error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($lacak_result): ?>
                        <div class="p-3.5 bg-white dark:bg-slate-900 rounded-xl border border-brand-200 dark:border-brand-800 space-y-2 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="font-mono text-slate-400 text-[10px]"><?= htmlspecialchars($lacak_result['nomor_tiket']) ?></span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                    Status: <?= htmlspecialchars($lacak_result['status']) ?>
                                </span>
                            </div>
                            <div class="text-slate-700 dark:text-slate-300">
                                <strong>Kategori:</strong> <?= htmlspecialchars($lacak_result['kategori']) ?><br>
                                <strong>Tanggal:</strong> <?= date('d M Y, H:i', strtotime($lacak_result['tanggal'])) ?> WIT
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-3 text-xs text-slate-600 dark:text-slate-300">
                    <h4 class="font-bold text-slate-900 dark:text-white text-sm">Kantor Pelayanan</h4>
                    <p class="flex items-start space-x-2">
                        <i class="bi bi-geo-alt-fill text-brand-700 shrink-0 mt-0.5"></i>
                        <span>Gamalama, Kec. Ternate Tengah, Kota Ternate, Maluku Utara</span>
                    </p>
                    <p class="flex items-center space-x-2">
                        <i class="bi bi-envelope-fill text-brand-700 shrink-0"></i>
                        <span>kebudayaan@ternatekota.go.id</span>
                    </p>
                    <p class="flex items-center space-x-2">
                        <i class="bi bi-clock-fill text-gold-500 shrink-0"></i>
                        <span>Senin - Jumat (08:00 - 16:30 WIT)</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
