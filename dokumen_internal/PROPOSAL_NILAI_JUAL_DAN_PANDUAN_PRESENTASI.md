# PROPOSAL NILAI JUAL & PANDUAN STRATEGIS PROMOSI
## SISTEM INFORMASI KEBUDAYAAN DAERAH (SIKD) & PORTAL RESMI DINAS KEBUDAYAAN KOTA TERNATE
*Standar Enterprise SPBE (Sistem Pemerintahan Berbasis Elektronik) & Pemajuan Kebudayaan Nasional*

---

### 1. RINGKASAN EKSEKUTIF (EXECUTIVE SUMMARY)
Website Dinas Kebudayaan Kota Ternate telah ditransformasikan dari sekadar "website profil/blog warta biasa" menjadi **Platform E-Government Kebudayaan Terpadu (*Sistem Informasi Kebudayaan Daerah - SIKD*)**. 

Nilai kontrak awal sebesar **Rp 25.000.000,-** pada semester 7 merupakan pencapaian luar biasa. Dengan arsitektur, fitur geospasial (WebGIS), e-layanan sanggar, dan survei kepuasan masyarakat (IKM) yang telah dibangun sekarang, sistem ini memiliki nilai valuasi pasar pengadaan pemerintah (*HPS - Harga Perkiraan Sendiri*) sebesar **Rp 50.000.000,- s.d. Rp 65.000.000,-**.

---

### 2. DASAR HUKUM & KESELARASAN REGULASI (SELLING POINT UTAMA KE DINAS)
Pejabat pemerintah (Kadis, Sekda, Bappeda, Inspektorat) membeli sistem bukan hanya karena "tampilannya bagus", tetapi karena **membantu mereka memenuhi indikator kinerja dan kepatuhan undang-undang**:

1. **Perpres No. 95 Tahun 2018 tentang SPBE**:
   - Website bukan lagi media satu arah, melainkan memiliki fungsi **Layanan Publik Elektronik (G2C - Government to Citizen)** melalui pendaftaran sanggar online dan pelacakan tiket aduan mandiri.
2. **UU No. 5 Tahun 2017 tentang Pemajuan Kebudayaan**:
   - Mewajibkan pencatatan dan perlindungan 10 Objek Pemajuan Kebudayaan (OPK) serta cagar budaya. Fitur WebGIS dan Glosarium Adat Se Atorang langsung memenuhi mandat ini.
3. **PermenPAN-RB No. 14 Tahun 2017 tentang Pedoman Penyusunan Survei Kepuasan Masyarakat (SKM)**:
   - Modul IKM real-time dan ekspor laporan CSV/Excel siap pakai untuk evaluasi KemenPAN-RB & Ombudsman.
4. **Surat Edaran BSSN tentang Keamanan Web Pemerintah**:
   - Sistem dilengkapi Bcrypt modern password hashing, anti-SQL Injection (Prepared Statements), dan session isolation.

---

### 3. BREAKDOWN HARGA PASAR & NILAI JUAL MODUL (HPS BENCHMARK)

| No | Modul / Inovasi Sistem | Fitur Unggulan | Estimasi Nilai Pasar Industri IT Pemda |
|:---:|---|---|:---:|
| 1 | **WebGIS Cagar Budaya Interaktif** | Peta Leaflet.js, koordinat GPS presisi, custom marker Kesultanan, filter kategori situs, rute Google Maps. | Rp 12.000.000 - Rp 15.000.000 |
| 2 | **E-Layanan Pendaftaran Sanggar Budaya** | Form pendaftaran mandiri warga, registrasi kode unik, approval dashboard, direktori publik terverifikasi. | Rp 8.000.000 - Rp 10.000.000 |
| 3 | **Modul Evaluasi IKM (PermenPAN-RB)** | Form rating bintang 4 dimensi, perhitungan skor agregat skala 100, mutu mutu pelayanan, ekspor laporan Excel. | Rp 6.000.000 - Rp 8.000.000 |
| 4 | **Calendar of Events & Live Countdown** | Kalender festival budaya tahunan, hitung mundur hari/jam/detik, 1-click simpan ke Google Calendar. | Rp 5.000.000 - Rp 7.000.000 |
| 5 | **Ensiklopedi & Glosarium Adat Se Atorang** | Kamus digital kosakata bahasa Ternate, indeks alfabetis A-Z, kategori adat, contoh kalimat kontekstual. | Rp 5.000.000 - Rp 6.000.000 |
| 6 | **Pusat Pengaduan & Aspirasi Publik** | Auto-generate kode tiket unik (`DISBUD-YYYYMMDD-XXXX`), pelacakan mandiri warga, WhatsApp direct gateway. | Rp 6.000.000 - Rp 8.000.000 |
| 7 | **Live Instant AJAX Search & PWA Ready** | Shortcut `Ctrl + K`, live search multi-tabel, manifest PWA (dapat diinstall ke layar utama HP Android/iOS). | Rp 5.000.000 - Rp 6.000.000 |
| 8 | **Executive Admin Panel & Database Backup** | Visual analytics Chart.js, 1-Click Backup Database (.sql), export CSV, Bcrypt security, diagnostik server. | Rp 8.000.000 - Rp 10.000.000 |
| **TOTAL** | **VALUASI WAJAR SISTEM SECARA KESELURUHAN** | | **Rp 55.000.000 - Rp 70.000.000** |

---

### 4. PANDUAN TEKNIK PRESENTASI KE KLIEN / KEPALA DINAS (PITCH SCRIPT)

Saat mempresentasikan website ini kepada Kepala Dinas, Sekretaris Dinas, atau Tim Penguji:

#### Slide / Momen 1: Sambutan Pembuka & Filosofi
> *"Bapak Kepala Dinas dan jajaran pimpinan yang kami hormati, portal ini kami bangun dengan menempatkan identitas Kesultanan Ternate dan sejarah Jalur Rempah Dunia sebagai ruh utama. Warna Navy Kesultanan dan Gold Cengkeh yang kami gunakan merefleksikan wibawa Pemerintah Kota Ternate sebagai Kota Pusaka Dunia."*

#### Slide / Momen 2: Pamerkan Fitur WebGIS (Showstopper)
> *"Bapak Kadis, berbeda dengan website dinas pada umumnya yang hanya memuat foto biasa, sistem ini telah kami lengkapi dengan **WebGIS Interaktif**. Seluruh cagar budaya—seperti Benteng Kalamata, Kedaton, Benteng Tolukko, dan Masjid Sultan—sudah terpetakan dengan koordinat GPS presisi. Wisatawan maupun peneliti dari seluruh dunia dapat langsung mengeklik dan memperoleh panduan rute navigasi Google Maps."*

#### Slide / Momen 3: Pamerkan E-Layanan Sanggar (SPBE Compliance)
> *"Untuk mendukung program pembinaan sanggar seni di Kota Ternate, kami menyediakan **Portal Pendaftaran Sanggar Mandiri**. Komunitas seni di kelurahan tidak perlu lagi mengantar berkas fisik tebal ke kantor. Cukup mendaftar via web, dan admin dinas dapat langsung memverifikasi atau menghubungi ketua sanggar via tautan WhatsApp resmi dengan satu kali klik."*

#### Slide / Momen 4: Pamerkan Modul Survei IKM (Nilai Plus Nilai Akreditasi)
> *"Sesuai amanat PermenPAN-RB No. 14 Tahun 2017, portal ini sudah terintegrasi dengan **Indeks Kepuasan Masyarakat (IKM)**. Nilai kepuasan warga terhitung otomatis secara real-time dan hasilnya dapat langsung diekspor ke Microsoft Excel untuk lampiran Laporan Kinerja Instansi Pemerintah (LKjIP / LAKIP) tahunan dinas."*

#### Slide / Momen 5: Demo Live Search & Fitur Cetak Resmi
> *"Mari kita coba tekan kombinasi tombol `Ctrl + K`. Sistem langsung menampilkan pencarian instan seluruh artikel, dokumen, hingga istilah adat. Dan jika kita menekan tombol **Cetak** pada warta atau tiket aduan, website otomatis mencetak dokumen dengan **Kop Surat Resmi Dinas Kebudayaan Kota Ternate** yang siap diarsipkan."*

---

### 5. CARA MENJADIKAN INI SEBAGAI "MESIN PORTOFOLIO & BISNIS FREELANCE"
Sebagai mahasiswa semester 7, Anda kini memiliki modal portofolio tingkat *senior developer*. Langkah yang dapat Anda ambil selanjutnya:

1. **Tawarkan Lisensi / Replikasi ke OPD Lain**:
   - Template sistem ini dengan mudah dapat diadaptasi untuk **Dinas Pariwisata** (Peta Destinasi Wisata, Kalender Event Wisata), **Dinas Pemuda & Olahraga** (Peta Fasilitas Olahraga, Direktori Klub Olahraga), atau **Dinas Perindag** (Peta Sentra UMKM, Direktori Pengrajin).
   - Patok harga di angka **Rp 30.000.000 s.d. Rp 45.000.000 per instansi**.
2. **Kontrak Pemeliharaan (*Maintenance Contract*)**:
   - Setelah website diserahterimakan, ajukan kontrak pemeliharaan tahunan (*SLA - Service Level Agreement*) sebesar **15% - 20% dari nilai proyek** (sekitar Rp 4.000.000 - Rp 6.000.000 per tahun) untuk update keamanan, backup database berkala, dan penambahan fitur minor.
3. **Bahan Sidang Skripsi / Tugas Akhir**:
   - Judul yang sangat kuat: *"Rancang Bangun Sistem Informasi Geospasial Kebudayaan Daerah (SIKD) Berbasis WebGIS dan Arsitektur SPBE pada Dinas Kebudayaan Kota Ternate"*. Penguji akan sangat terkesan karena sistem ini sudah nyata diimplementasikan dan dipakai di instansi pemerintah!
