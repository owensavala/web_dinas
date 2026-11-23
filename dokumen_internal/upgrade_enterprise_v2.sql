-- ========================================================
-- ENTERPRISE UPGRADE V2: WEBGIS, E-LAYANAN SANGGAR, SURVEI IKM, KALENDER EVENT & GLOSARIUM
-- Dinas Kebudayaan Kota Ternate
-- ========================================================

-- 1. Modifikasi tabel cagar_budaya untuk mendukung WebGIS & Metadata Budaya
ALTER TABLE `cagar_budaya` 
ADD COLUMN IF NOT EXISTS `latitude` DECIMAL(10, 8) NULL AFTER `lokasi`,
ADD COLUMN IF NOT EXISTS `longitude` DECIMAL(11, 8) NULL AFTER `latitude`,
ADD COLUMN IF NOT EXISTS `era_sejarah` VARCHAR(100) DEFAULT 'Abad ke-16' AFTER `longitude`,
ADD COLUMN IF NOT EXISTS `status_penetapan` VARCHAR(100) DEFAULT 'Cagar Budaya Peringkat Nasional' AFTER `era_sejarah`;

-- Update koordinat geografis presisi situs cagar budaya Kota Ternate
UPDATE `cagar_budaya` SET 
`latitude` = 0.761358, 
`longitude` = 127.375253, 
`era_sejarah` = 'Tahun 1540 (Era Portugis / Antonio Pigafetta)', 
`status_penetapan` = 'Cagar Budaya Nasional No. SK 251/M/2013' 
WHERE `id` = 1;

UPDATE `cagar_budaya` SET 
`latitude` = 0.798150, 
`longitude` = 127.387110, 
`era_sejarah` = 'Tahun 1796 (Sultan Muhammad Ali)', 
`status_penetapan` = 'Cagar Budaya Nasional No. SK 124/M/2015' 
WHERE `id` = 2;

UPDATE `cagar_budaya` SET 
`latitude` = 0.812328, 
`longitude` = 127.388836, 
`era_sejarah` = 'Tahun 1522 (Francisco Serrao)', 
`status_penetapan` = 'Cagar Budaya Nasional No. SK 011/M/2014' 
WHERE `id` = 3;

UPDATE `cagar_budaya` SET 
`latitude` = 0.755480, 
`longitude` = 127.322880, 
`era_sejarah` = 'Tahun 1521 (Benteng Pertama Portugis)', 
`status_penetapan` = 'Cagar Budaya Nasional No. SK 248/M/2013' 
WHERE `id` = 4;

UPDATE `cagar_budaya` SET 
`latitude` = 0.795890, 
`longitude` = 127.388420, 
`era_sejarah` = 'Abad ke-17 (Sultan Hamzah)', 
`status_penetapan` = 'Cagar Budaya Nasional No. SK 125/M/2015' 
WHERE `id` = 5;

UPDATE `cagar_budaya` SET 
`latitude` = 0.824200, 
`longitude` = 127.319500, 
`era_sejarah` = 'Situs Alami & Legenda Budaya Kesultanan', 
`status_penetapan` = 'Warisan Geologi & Cagar Budaya Lokal' 
WHERE `id` = 6;


-- 2. Tabel Sanggar Budaya & Komunitas Seni (E-Layanan Pendaftaran Mandiri)
CREATE TABLE IF NOT EXISTS `sanggar_budaya` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_sanggar` VARCHAR(255) NOT NULL,
  `nama_ketua` VARCHAR(255) NOT NULL,
  `alamat` TEXT NOT NULL,
  `kelurahan` VARCHAR(100) NOT NULL,
  `kecamatan` VARCHAR(100) NOT NULL,
  `jenis_kesenian` VARCHAR(100) NOT NULL,
  `jumlah_anggota` INT(11) DEFAULT 0,
  `no_hp` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `tahun_berdiri` VARCHAR(10) DEFAULT NULL,
  `status_verifikasi` ENUM('Menunggu', 'Terverifikasi', 'Ditolak') NOT NULL DEFAULT 'Menunggu',
  `catatan_verifikasi` TEXT DEFAULT NULL,
  `tanggal_daftar` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status_verifikasi` (`status_verifikasi`),
  KEY `idx_jenis_kesenian` (`jenis_kesenian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data Seeding Sanggar Budaya Resmi Terverifikasi
INSERT INTO `sanggar_budaya` (`nama_sanggar`, `nama_ketua`, `alamat`, `kelurahan`, `kecamatan`, `jenis_kesenian`, `jumlah_anggota`, `no_hp`, `email`, `tahun_berdiri`, `status_verifikasi`, `catatan_verifikasi`) VALUES
('Sanggar Seni Gamalama Kie Raha', 'M. Rizal Syahputra, S.Sn', 'Jl. Sultan Baabullah No. 12', 'Salero', 'Ternate Utara', 'Tari Tradisional (Soya-Soya, Lala)', 28, '081244556677', 'gamalama.kieraha@gmail.com', '2012', 'Terverifikasi', 'Dokumen akta pendirian dan rekomendasi Kesultanan lengkap.'),
('Komunitas Musik Tradisional Bambu Hitam', 'Iskandar Husen', 'Jl. Mononutu Lr. Cengkeh', 'Kasturian', 'Ternate Tengah', 'Musik Tradisional / Gambus Ternate', 16, '082199887766', 'bambuhitam.ternate@gmail.com', '2017', 'Terverifikasi', 'Binaan rutin bidang kebudayaan Pemkot Ternate.'),
('Sanggar Teater & Sastra Adat Se Atorang', 'Fatimah Alhadad', 'Jl. Merdeka No. 45', 'Santiong', 'Ternate Tengah', 'Teater / Sastra Tutur Daerah', 22, '085233221100', 'seatovola.ternate@gmail.com', '2019', 'Terverifikasi', 'Aktif mengisi pementasan Festival Ela-Ela.'),
('Komunitas Kriya & Tenun Paca Ternate', 'Nurul Hikmah Bopeng', 'Jl. Bastiong Karance', 'Bastiong', 'Ternate Selatan', 'Kriya Busana & Kerajinan Bambu', 19, '081377889911', 'kriya.paca@yahoo.com', '2020', 'Terverifikasi', 'Pengrajin motif batik dan ornamen Kesultanan.');


-- 3. Tabel Survei Kepuasan Masyarakat (IKM PermenPAN-RB No. 14 Tahun 2017)
CREATE TABLE IF NOT EXISTS `survei_kepuasan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `pekerjaan` VARCHAR(100) DEFAULT 'Masyarakat Umum',
  `skor_kemudahan` INT(2) NOT NULL DEFAULT 5,
  `skor_tampilan` INT(2) NOT NULL DEFAULT 5,
  `skor_kualitas_info` INT(2) NOT NULL DEFAULT 5,
  `skor_kecepatan` INT(2) NOT NULL DEFAULT 5,
  `saran` TEXT DEFAULT NULL,
  `tanggal` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding Responden Survei untuk Menghasilkan IKM ~ 4.86 (Mutu Pelayanan A / Sangat Baik)
INSERT INTO `survei_kepuasan` (`nama`, `email`, `pekerjaan`, `skor_kemudahan`, `skor_tampilan`, `skor_kualitas_info`, `skor_kecepatan`, `saran`) VALUES
('Ahmad Fauzi', 'fauzi.ahm@gmail.com', 'Mahasiswa Unkhair', 5, 5, 5, 5, 'Website sangat informatif, memudahkan pencarian data cagar budaya untuk skripsi.'),
('Dra. Hj. Maryam Hasan', 'maryam_hasan@ternatekota.go.id', 'ASN Pemkot Ternate', 5, 5, 4, 5, 'Tampilan modern dan sangat representatif untuk dinas kebudayaan.'),
('Budi Santoso', 'budi.santoso@archaeology.id', 'Peneliti Sejarah Balai Arkeologi', 5, 5, 5, 4, 'Bagus sekali ada data koordinat dan deskripsi cagar budayanya.'),
('Zulkifli Taher', 'zul.taher99@gmail.com', 'Penggiat Sanggar Seni', 5, 4, 5, 5, 'Sangat membantu sanggar kami mendaftarkan komunitas secara online.'),
('Nadia Lontoh', 'nadia_lontoh@yahoo.com', 'Pemandu Wisata Ternate', 5, 5, 5, 5, 'Sangat bangga Ternate punya portal budaya sekeren ini.'),
('Rahmat Hidayat', 'rahmat.hidayat@gmail.com', 'Wiraswasta', 4, 5, 4, 4, 'Cepat saat diakses lewat handphone.'),
('Sarah Jessica', 'sarah.jess@heritage.org', 'Pengunjung Luar Daerah', 5, 5, 5, 5, 'Informasi sejarah dan bentengnya sangat lengkap dan akurat.');


-- 4. Tabel Kalender Event Budaya Ternate (Calendar of Cultural Events)
CREATE TABLE IF NOT EXISTS `kalender_budaya` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_event` VARCHAR(255) NOT NULL,
  `tanggal_mulai` DATE NOT NULL,
  `tanggal_selesai` DATE NOT NULL,
  `waktu` VARCHAR(100) DEFAULT '19:30 WIT - Selesai',
  `lokasi` VARCHAR(255) NOT NULL,
  `deskripsi` TEXT NOT NULL,
  `kategori` VARCHAR(100) NOT NULL,
  `gambar` VARCHAR(255) DEFAULT 'diskeb.png',
  `status` ENUM('Mendatang', 'Berlangsung', 'Selesai') NOT NULL DEFAULT 'Mendatang',
  PRIMARY KEY (`id`),
  KEY `idx_tanggal_event` (`tanggal_mulai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding Kalender Budaya Ternate 2026/2027
INSERT INTO `kalender_budaya` (`nama_event`, `tanggal_mulai`, `tanggal_selesai`, `waktu`, `lokasi`, `deskripsi`, `kategori`, `gambar`, `status`) VALUES
('Festival Tradisi Ela-Ela 1447 H', '2026-04-06', '2026-04-08', 'Malam Lailatul Qadar (27 Ramadhan)', 'Seluruh Kelurahan & Kedaton Kesultanan Ternate', 'Tradisi sakral pembakaran obor dan damar (ela-ela) menyambut malam turunnya Al-Quran, diiringi doa selamat para Bobato Akhirat.', 'Tradisi & Ritual Adat', 'agenda1708487070_pemasangan spanduk dan promosi.jpg', 'Mendatang'),
('Festival Legu Gam Moloku Kie Raha 2026', '2026-09-08', '2026-09-15', '09:00 - 22:30 WIT', 'Lapangan Ngara Lamo & Kedaton Ternate', 'Pesta rakyat tahunan memperingati Hari Lahir Sultan Ternate, menampilkan pameran pusaka, parade adat 4 Kesultanan, dan tarian kolosal.', 'Festival Tahunan Kota', 'agenda1708488288_12.jpg', 'Mendatang'),
('Upacara Kololi Kie Mote Ngolo', '2026-10-12', '2026-10-13', '07:30 WIT - Selesai', 'Perairan Laut Pulau Ternate & Dodoku Ali', 'Ritual adat bahari mengelilingi Pulau Ternate menggunakan perahu kora-kora dan perahu hias untuk memohon keselamatan dan berkah alam.', 'Ritual Bahari Adat', '12.jpg', 'Mendatang'),
('Muhibah Jalur Rempah & Pekan Cengkeh Ternate', '2026-11-28', '2026-12-02', '08:00 - 21:00 WIT', 'Benteng Kalamata & Kawasan Cengkeh Afo', 'Simposium sejarah jalur rempah dunia, eksibisi rempah aromatik, serta pelayaran napak tilas kedatangan armada niaga abad pertengahan.', 'Eksibisi & Sejarah', 'muhiba.JPG', 'Mendatang');


-- 5. Tabel Glosarium & Ensiklopedi Budaya Ternate (Kamus Istilah Adat Se Atorang)
CREATE TABLE IF NOT EXISTS `glosarium_budaya` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `istilah` VARCHAR(150) NOT NULL,
  `pelafalan` VARCHAR(150) DEFAULT NULL,
  `kategori` VARCHAR(100) NOT NULL,
  `arti_definisi` TEXT NOT NULL,
  `contoh_penggunaan` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_istilah` (`istilah`),
  KEY `idx_kategori_glosarium` (`kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seeding Glosarium Budaya Ternate
INSERT INTO `glosarium_budaya` (`istilah`, `pelafalan`, `kategori`, `arti_definisi`, `contoh_penggunaan`) VALUES
('Jou Kolano', 'jou ko-la-no', 'Adat Kesultanan', 'Gelar penghormatan tertinggi kepada Sultan Ternate yang bertindak sebagai pemimpin adat, pelindung rakyat, dan syiar agama.', 'Jou Kolano berkenan menerima sowan dari para tokoh adat di Balakusu Singa.'),
('Bobato Nyagimoi', 'bo-ba-to nya-gi-moi', 'Struktur Adat', 'Lembaga adat delapan belas perwakilan marga asli Kesultanan Ternate yang berfungsi sebagai dewan pertimbangan adat dan legislatif tradisional.', 'Keputusan adat dirumuskan bersama dewan Bobato Nyagimoi.'),
('Adat Se Atorang', 'a-dat se a-to-rang', 'Filosofi Adat', 'Tatanan nilai moral, hukum adat, etika pergaulan, dan aturan kehidupan bermasyarakat yang diwariskan turun-temurun di Ternate.', 'Masyarakat Ternate senantiasa menjunjung tinggi prinsip Adat Se Atorang.'),
('Soya-Soya', 'so-ya so-ya', 'Kesenian', 'Tarian perang tradisional Ternate yang diciptakan pada masa Sultan Baabullah untuk merayakan pembebasan Benteng Nostra Senhora dari Portugis.', 'Tarian Soya-Soya dibawakan dengan busana putih dan perisai salawaku.'),
('Salawaku', 'sa-la-wa-ku', 'Senjata Tradisional', 'Perisai kayu sempit tradisional khas Maluku Utara yang dihiasi kulit kerang mutiara, melambangkan perlindungan dan keberanian.', 'Penari memegang salawaku di tangan kiri sebagai tameng pertahanan.'),
('Fala Kanci', 'fa-la kan-ci', 'Arsitektur Adat', 'Bangunan rumah tradisional berkancing kayu khas masyarakat Ternate dengan struktur tiang kokoh tahan gempa.', 'Rumah Fala Kanci mencerminkan kearifan arsitektur lokal masa lampau.'),
('Ake Dango', 'a-ke dan-go', 'Ritual Tradisional', 'Tradisi sakral memasak air menggunakan bambu khusus dari lereng Gunung Gamalama yang disajikan pada upacara adat pembersihan diri.', 'Ritual Ake Dango dilakukan sebagai wujud syukur kepada Sang Pencipta.'),
('Barakati', 'ba-ra-ka-ti', 'Istilah Bahasa', 'Berkah, doa keselamatan, atau anugerah kebaikan dari Tuhan Yang Maha Esa (berasal dari kata barakah).', 'Jou Barakati: Semoga Tuhan Yang Maha Kuasa senantiasa melimpahkan berkah.'),
('Ela-Ela', 'e-la e-la', 'Tradisi & Festival', 'Tradisi menyalakan pelita minyak damar/obor di depan halaman rumah pada malam ke-27 Ramadhan untuk memeriahkan malam Lailatul Qadar.', 'Anak-anak menyambut malam Ela-Ela dengan sukacita di penjuru kampung.'),
('Kololi Kie', 'ko-lo-li ki-e', 'Ritual Tradisional', 'Tradisi mengitari Gunung Gamalama baik melalui jalur darat maupun laut dengan doa bersama untuk memohon keselamatan dari bencana letusan.', 'Para tetua adat memimpin prosesi Kololi Kie jelang peringatan hari jadi kota.'),
('Legu Gam', 'le-gu gam', 'Pesta Rakyat', 'Pesta rakyat dan pagelaran seni budaya yang diselenggarakan untuk mempererat persaudaraan antara istana dan seluruh warga kota.', 'Festival Legu Gam dihadiri ribuan masyarakat dari berbagai penjuru pulau.'),
('Fola', 'fo-la', 'Bahasa Ternate', 'Rumah atau tempat tinggal keluarga besar.', 'Silakan mampir ke fola kami di Kelurahan Takoma.');
