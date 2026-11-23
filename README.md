# Portal Resmi Dinas Kebudayaan Kota Ternate

Portal Web Resmi dan Sistem Informasi Manajemen Terpadu Dinas Kebudayaan Pemerintah Kota Ternate.

Dirancang untuk pelestarian cagar budaya, sejarah jalur rempah, direktori sanggar seni, kalender tradisi Moloku Kie Raha, survei kepuasan masyarakat (IKM SPBE), serta pelayanan publik interaktif dengan tema visual **Cream Mocha**.

---

## 🚀 Fitur Unggulan

### 1. Portal Publik
- **Beranda Interaktif**: Hero banner kesultanan, statistik kebudayaan, profil pimpinan dinas, running text warta resmi.
- **Peta Cagar Budaya (WebGIS)**: Pemetaan titik koordinat cagar budaya dan situs bersejarah berbasis Leaflet.js dengan rute navigasi Google Maps.
- **Kalender Festival & Tradisi**: Agenda kebudayaan tahunan lengkap dengan hitung mundur (*countdown timer*) waktu pelaksanaan.
- **Fasilitasi Sanggar Seni**: Direktori sanggar budaya binaan dan formulir pendaftaran komunitas seni baru secara daring.
- **Glosarium Bahasa Ternate**: Kamus istilah adat *Se Atorang* dan kosakata bahasa daerah Ternate.
- **Survei Kepuasan (IKM SPBE)**: Pengukuran kepuasan masyarakat terhadap layanan dinas dengan kalkulasi otomatis nilai mutu.
- **Warta & Agenda**: Publikasi berita resmi dengan dukungan lampiran dokumen PDF (*in-browser PDF viewer*).
- **Galeri Multimedia**: Dokumentasi foto kegiatan dengan tampilan modal lightbox resolusi tinggi.
- **Pencarian Cepat (Ctrl + K)**: Modal pencarian instan untuk menemukan artikel dan konten situs secara langsung.

### 2. Panel Administrasi Terpadu (`/admin`)
- **Dashboard Analytics**: Statistik data warta, dokumen PDF, sanggar budaya, dan grafik survei IKM berbasis Chart.js.
- **Manajemen Warta & Berita**: CRUD berita dan pengumuman dengan upload gambar & berkas PDF.
- **Manajemen Galeri**: Upload foto dokumentasi kebudayaan.
- **Kotak Masuk Aduan**: Pemantauan aspirasi dan pengaduan warga dengan ekspor data ke format CSV.
- **Verifikasi Sanggar**: Tinjau dan setujui pendaftaran sanggar seni masyarakat.
- **Cadangan Basis Data**: Fitur *one-click* backup database ke file `.sql`.
- **Pengaturan Akun**: Perubahan kata sandi administrator dengan enkripsi aman `bcrypt`.

---

## 🛠️ Persyaratan Sistem (Tech Stack)

- **Bahasa Pemrograman**: PHP 8.1 / 8.2 / 8.3
- **Basis Data**: MySQL 5.7+ / MariaDB 10.4+
- **Styling**: Tailwind CSS Modern, Bootstrap Icons, Plus Jakarta Sans & Playfair Display Google Fonts
- **Library Frontend**: Leaflet.js (GIS), Chart.js (Visualisasi Statistik)

---

## 📦 Panduan Instalasi Lokal (XAMPP / Laragon)

1. **Clone Repositori**:
   ```bash
   git clone https://github.com/owensavala/web_dinas.git
   ```
2. **Pindahkan Folder**:
   Pindahkan folder ke direktori web server (misalnya: `C:\xampp\htdocs\web_dinas`).
3. **Import Basis Data**:
   - Buka `phpMyAdmin` di browser (`http://localhost/phpmyadmin`).
   - Buat database baru bernama `diskeb`.
   - Import file `diskeb.sql` ke dalam database `diskeb`.
4. **Jalankan Aplikasi**:
   - Akses portal publik: `http://localhost/web_dinas/index.php`
   - Akses panel admin: `http://localhost/web_dinas/admin/login.php`
   - Kredensial default: Username `admin` / Password sesuai database (`admin` / `admin123`).

---

## 🐳 Panduan Deploy dengan Docker / Docker Compose (VPS / Instabox)

Proyek ini telah dilengkapi dengan `Dockerfile` dan `docker-compose.yml` siap pakai:

1. **Jalankan dengan Satu Perintah**:
   ```bash
   docker-compose up -d --build
   ```
2. **Akses Layanan**:
   - Web Aplikasi: `http://IP_SERVER:8080`
   - Database MySQL berjalan otomatis dan langsung memuat struktur tabel dari `diskeb.sql`.

---

## 🚂 Panduan Deploy ke Railway / Cloud Containers

1. Push repositori ini ke akun **GitHub**.
2. Masuk ke [Railway.app](https://railway.app), klik **New Project** -> **Deploy from GitHub repo**.
3. Pilih repositori `web_dinas`.
4. Tambahkan plugin **MySQL Database** di Railway.
5. Hubungkan variabel lingkungan: Railway secara otomatis menyediakan variabel `MYSQLHOST`, `MYSQLUSER`, `MYSQLPASSWORD`, `MYSQLDATABASE`, dan `MYSQLPORT` yang langsung dibaca oleh file `koneksi.php`.
6. Import file `diskeb.sql` ke database Railway melalui antarmuka Railway atau koneksi CLI.

---

## 📄 Lisensi & Hak Cipta
Dikelola oleh Dinas Kebudayaan Pemerintah Kota Ternate, Maluku Utara.
