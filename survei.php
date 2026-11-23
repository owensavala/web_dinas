<?php
require_once __DIR__ . '/koneksi.php';

// Hitung data agregat Survei Kepuasan Masyarakat (IKM)
$sql_ikm = "SELECT 
    COUNT(*) as total_responden,
    AVG(skor_kemudahan) as avg_kemudahan,
    AVG(skor_tampilan) as avg_tampilan,
    AVG(skor_kualitas_info) as avg_info,
    AVG(skor_kecepatan) as avg_kecepatan
FROM survei_kepuasan";

$res_ikm = mysqli_query($koneksi, $sql_ikm);
$d_ikm = mysqli_fetch_assoc($res_ikm);

$total_responden = (int)($d_ikm['total_responden'] ?? 0);
$avg_kemudahan   = round((float)($d_ikm['avg_kemudahan'] ?? 5.0), 2);
$avg_tampilan    = round((float)($d_ikm['avg_tampilan'] ?? 5.0), 2);
$avg_info        = round((float)($d_ikm['avg_info'] ?? 5.0), 2);
$avg_kecepatan   = round((float)($d_ikm['avg_kecepatan'] ?? 5.0), 2);

$skor_keseluruhan = round(($avg_kemudahan + $avg_tampilan + $avg_info + $avg_kecepatan) / 4, 2);
// Konversi ke skala standar IKM PermenPAN-RB 100
$skor_ikm_100 = round(($skor_keseluruhan / 5) * 100, 2);

if ($skor_ikm_100 >= 88.31) {
    $predikat_ikm = "A (Sangat Baik)";
    $badge_class  = "bg-emerald-50 text-emerald-800 border-emerald-300 dark:bg-emerald-950 dark:text-emerald-300";
} elseif ($skor_ikm_100 >= 76.61) {
    $predikat_ikm = "B (Baik)";
    $badge_class  = "bg-blue-50 text-blue-800 border-blue-300 dark:bg-blue-950 dark:text-blue-300";
} else {
    $predikat_ikm = "C (Kurang Baik)";
    $badge_class  = "bg-amber-50 text-amber-800 border-amber-300 dark:bg-amber-950 dark:text-amber-300";
}

// Ambil 5 ulasan terbaru
$res_ulasan = mysqli_query($koneksi, "SELECT nama, pekerjaan, saran, tanggal FROM survei_kepuasan WHERE saran != '' ORDER BY id DESC LIMIT 5");
?>

<div class="space-y-8">
    <!-- Header Page -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
            <span class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200/50 mb-2">
                <i class="bi bi-shield-check"></i>
                <span>Standar Pelayanan Publik PermenPAN-RB No. 14/2017</span>
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight font-serif">
                Survei Indeks Kepuasan Masyarakat (IKM)
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-2xl leading-relaxed">
                Transparansi penilaian masyarakat terhadap mutu keterbukaan informasi dan layanan digital Dinas Kebudayaan Kota Ternate.
            </p>
        </div>
    </div>

    <!-- Ringkasan Nilai IKM Eksekutif -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-stretch">
        <!-- Skor Utama (5 Kolom) -->
        <div class="md:col-span-5 bg-gradient-to-br from-brand-900 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-lg border border-brand-800 flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-gold-400">Nilai Akumulasi IKM</span>
                <div class="flex items-baseline space-x-3 mt-2">
                    <span class="text-5xl sm:text-6xl font-black font-mono tracking-tight text-white"><?= $skor_ikm_100 ?></span>
                    <span class="text-sm text-slate-300 font-semibold">/ 100</span>
                </div>
                <div class="mt-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border <?= $badge_class ?>">
                        <i class="bi bi-award-fill mr-1.5 text-gold-400"></i> Mutu Pelayanan: <?= $predikat_ikm ?>
                    </span>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-800 text-xs text-slate-300 flex items-center justify-between">
                <span>Skor Skala Bintang: <strong><?= $skor_keseluruhan ?> / 5.00</strong></span>
                <span>Total: <strong><?= $total_responden ?> Responden</strong></span>
            </div>
        </div>

        <!-- 4 Indikator Detail (7 Kolom) -->
        <div class="md:col-span-7 bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800 space-y-4">
            <h4 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                Rincian 4 Unsur Penilaian Layanan
            </h4>

            <div class="space-y-3 text-xs">
                <div>
                    <div class="flex justify-between font-semibold mb-1">
                        <span class="text-slate-700 dark:text-slate-300">Kemudahan Akses Informasi</span>
                        <strong class="font-mono text-brand-800 dark:text-brand-400"><?= $avg_kemudahan ?> / 5.0</strong>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-brand-700 h-full rounded-full" style="width: <?= ($avg_kemudahan/5)*100 ?>%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between font-semibold mb-1">
                        <span class="text-slate-700 dark:text-slate-300">Kualitas & Estetika Tampilan Web</span>
                        <strong class="font-mono text-brand-800 dark:text-brand-400"><?= $avg_tampilan ?> / 5.0</strong>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-gold-500 h-full rounded-full" style="width: <?= ($avg_tampilan/5)*100 ?>%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between font-semibold mb-1">
                        <span class="text-slate-700 dark:text-slate-300">Keakuratan Data Cagar Budaya & Warta</span>
                        <strong class="font-mono text-brand-800 dark:text-brand-400"><?= $avg_info ?> / 5.0</strong>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-emerald-600 h-full rounded-full" style="width: <?= ($avg_info/5)*100 ?>%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between font-semibold mb-1">
                        <span class="text-slate-700 dark:text-slate-300">Kecepatan Respon Akses (Responsivitas)</span>
                        <strong class="font-mono text-brand-800 dark:text-brand-400"><?= $avg_kecepatan ?> / 5.0</strong>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-indigo-600 h-full rounded-full" style="width: <?= ($avg_kecepatan/5)*100 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Partisipasi Survei -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800 space-y-6">
        <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-700 dark:text-brand-400">Partisipasi Publik</span>
            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">Berikan Penilaian Anda terhadap Website Dinas</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Penilaian objektif Anda membantu kami terus menyempurnakan kualitas pelayanan publik digital.</p>
        </div>

        <form id="formIKM" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Anda</label>
                    <input type="text" name="nama" required placeholder="Nama lengkap atau inisial" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Email (Opsional)</label>
                    <input type="email" name="email" placeholder="alamat@email.com" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Profesi / Status</label>
                    <select name="pekerjaan" class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5">
                        <option value="Masyarakat Umum">Masyarakat Umum</option>
                        <option value="Pelajar / Mahasiswa">Pelajar / Mahasiswa</option>
                        <option value="ASN / Pegawai Instansi">ASN / Pegawai Instansi</option>
                        <option value="Pegiat Budaya / Seniman">Pegiat Budaya / Seniman</option>
                        <option value="Peneliti / Akademisi">Peneliti / Akademisi</option>
                        <option value="Wisatawan">Wisatawan</option>
                    </select>
                </div>
            </div>

            <!-- Rating 4 Aspek dengan Bintang Interaktif -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Kemudahan Akses Informasi</span>
                        <span class="text-[11px] text-slate-400">Navigasi dan pencarian warta</span>
                    </div>
                    <select name="kemudahan" class="text-xs font-bold rounded-lg border-slate-200 p-1.5 text-brand-800">
                        <option value="5">★★★★★ (5 - Sangat Baik)</option>
                        <option value="4">★★★★☆ (4 - Baik)</option>
                        <option value="3">★★★☆☆ (3 - Cukup)</option>
                        <option value="2">★★☆☆☆ (2 - Kurang)</option>
                        <option value="1">★☆☆☆☆ (1 - Buruk)</option>
                    </select>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Kualitas Desain & Estetika</span>
                        <span class="text-[11px] text-slate-400">Kenyamanan tampilan antarmuka</span>
                    </div>
                    <select name="tampilan" class="text-xs font-bold rounded-lg border-slate-200 p-1.5 text-brand-800">
                        <option value="5">★★★★★ (5 - Sangat Baik)</option>
                        <option value="4">★★★★☆ (4 - Baik)</option>
                        <option value="3">★★★☆☆ (3 - Cukup)</option>
                        <option value="2">★★☆☆☆ (2 - Kurang)</option>
                        <option value="1">★☆☆☆☆ (1 - Buruk)</option>
                    </select>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Keakuratan Konten Budaya</span>
                        <span class="text-[11px] text-slate-400">Kesesuaian sejarah dan cagar budaya</span>
                    </div>
                    <select name="kualitas_info" class="text-xs font-bold rounded-lg border-slate-200 p-1.5 text-brand-800">
                        <option value="5">★★★★★ (5 - Sangat Baik)</option>
                        <option value="4">★★★★☆ (4 - Baik)</option>
                        <option value="3">★★★☆☆ (3 - Cukup)</option>
                        <option value="2">★★☆☆☆ (2 - Kurang)</option>
                        <option value="1">★☆☆☆☆ (1 - Buruk)</option>
                    </select>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Kecepatan & Responsivitas</span>
                        <span class="text-[11px] text-slate-400">Performa buka halaman di HP</span>
                    </div>
                    <select name="kecepatan" class="text-xs font-bold rounded-lg border-slate-200 p-1.5 text-brand-800">
                        <option value="5">★★★★★ (5 - Sangat Baik)</option>
                        <option value="4">★★★★☆ (4 - Baik)</option>
                        <option value="3">★★★☆☆ (3 - Cukup)</option>
                        <option value="2">★★☆☆☆ (2 - Kurang)</option>
                        <option value="1">★☆☆☆☆ (1 - Buruk)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Saran & Masukan Konstruktif</label>
                <textarea name="saran" rows="3" placeholder="Tuliskan masukan untuk peningkatan kualitas website..." class="w-full text-xs rounded-xl bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 focus:border-brand-600 focus:ring-brand-600 p-2.5"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" id="btnSubmitIKM" class="px-8 py-3 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-brand-800 hover:bg-brand-900 shadow-md transition transform hover:-translate-y-0.5">
                    <i class="bi bi-star-fill mr-1.5 text-gold-400"></i> Kirim Penilaian Survei
                </button>
            </div>
        </form>
    </div>

    <!-- Testimoni / Ulasan Responden Terbaru -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800 space-y-4">
        <h4 class="text-base font-extrabold text-slate-900 dark:text-white">Aspirasi & Ulasan Masyarakat Terkini</h4>
        <div class="space-y-3">
            <?php while ($ul = mysqli_fetch_assoc($res_ulasan)): ?>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 space-y-1.5 text-xs">
                    <div class="flex items-center justify-between">
                        <strong class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($ul['nama']) ?> <span class="text-slate-400 font-normal">(<?= htmlspecialchars($ul['pekerjaan']) ?>)</span></strong>
                        <span class="text-[11px] text-slate-400"><?= date('d M Y', strtotime($ul['tanggal'])) ?></span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 italic">"<?= htmlspecialchars($ul['saran']) ?>"</p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("formIKM");
    if (!form) return;

    form.addEventListener("submit", function(e) {
        e.preventDefault();
        const btn = document.getElementById("btnSubmitIKM");
        btn.disabled = true;
        btn.innerHTML = "<i class='bi bi-hourglass-split animate-spin mr-1'></i> Mengirim...";

        const formData = new FormData(form);

        fetch("api_survei.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                window.location.reload();
            } else {
                alert("Galat: " + data.message);
                btn.disabled = false;
                btn.innerHTML = "<i class='bi bi-star-fill mr-1.5 text-gold-400'></i> Kirim Penilaian Survei";
            }
        })
        .catch(err => {
            alert("Terjadi gangguan koneksi ke server.");
            btn.disabled = false;
            btn.innerHTML = "<i class='bi bi-star-fill mr-1.5 text-gold-400'></i> Kirim Penilaian Survei";
        });
    });
});
</script>
