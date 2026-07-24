# 📚 Sistem Informasi Perpustakaan Digital (Manajemen Buku)

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-v5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![Database](https://img.shields.io/badge/MySQL-8.3-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/BNSP-Pemrogram%20Muda-orange?style=for-the-badge)](https://bnsp.go.id)

Aplikasi web **Sistem Informasi Perpustakaan Digital** berbasis **PHP Native (OOP)** dan **Bootstrap 5.3** yang dikembangkan untuk memenuhi skema sertifikasi kompetensi **Pemrogram Muda (Associate Programmer) - BNSP** (Kode Skema: `005/SSK/LSP-EDI/I/2026`, Kode Dokumen: `FR.IA.02`).

---

## 🌟 Fitur Utama

- 🔐 **Autentikasi Pengguna & Keamanan Session**
  - Halaman login aman dengan validasi kredensial pengguna.
  - Pengamanan session halaman terproteksi.
  - Mekanisme logout yang menghancurkan session secara aman.

- 📊 **Dashboard Statistik Real-Time**
  - Ringkasan statistik total buku, total kategori, dan transaksi peminjaman aktif.
  - Shortcut menu untuk navigasi cepat.

- 📖 **Manajemen Data Buku (CRUD & Filter)**
  - Operasi Tambah, Lihat, Edit, dan Hapus data buku.
  - Fitur pencarian cepat berdasarkan *Judul*, *Pengarang*, atau *ISBN*.
  - Filter pencarian buku berdasarkan *Kategori*.

- 🏷️ **Manajemen Kategori Buku (CRUD)**
  - Pengelolaan master kategori/genre buku.

- 🔄 **Transaksi Peminjaman & Pengembalian (Otomatisasi Stok)**
  - Pencatatan transaksi peminjaman buku oleh anggota perpustakaan.
  - **Logika Otomatisasi Stok**: Stok berkurang otomatis saat terjadi peminjaman dan bertambah kembali saat buku dikembalikan.

- 📄 **Ekspor Laporan PDF**
  - Generasi laporan rekapitulasi data peminjaman ke format dokumen PDF secara instan menggunakan library `html2pdf.js`.

---

## 🛠️ Teknologi & Lingkungan Pengembangan

| Komponen | Teknologi yang Digunakan |
|---|---|
| **Bahasa Pemrograman** | PHP v8.2 (Backend Logic & OOP), HTML5, CSS3, JavaScript (ES6) |
| **Database Driver** | PDO (*PHP Data Objects*) dengan MySQL / MariaDB |
| **CSS Framework** | Bootstrap v5.3 (CDN) |
| **Icon Library** | FontAwesome v6.4 (CDN) |
| **PDF Generator** | `html2pdf.js` / `jsPDF` (CDN) |
| **Web Server & DBMS** | Apache & MySQL (XAMPP Control Panel) |
| **IDE / Text Editor** | Visual Studio Code / Antigravity IDE |

---

## 🏗️ Arsitektur System & Class OOP (`classes/`)

Aplikasi menerapkan pola **Pemrograman Berorientasi Objek (OOP)** dengan pemisahan tanggung jawab (*Separation of Concerns*) melalui class-class terisolasi:

```
classes/
├── Database.php   # Mengelola koneksi PDO ke MySQL dengan Singleton Pattern
├── Auth.php       # Mengelola autentikasi login, password hashing, & session
├── Buku.php       # Mengelola query & logika bisnis data master buku
├── Kategori.php   # Mengelola query & logika bisnis data master kategori
└── Peminjaman.php # Mengelola transaksi peminjaman/pengembalian & otomatisasi stok
```

---

## 📂 Struktur Direktori Proyek

```bash
BNSP/
├── Auth/              # Modul Autentikasi Pengguna
│   ├── login.php      # Form & proses login
│   └── logout.php     # Skrip logout & penghancuran session
├── Buku/              # Modul Pengelolaan Data Buku
│   └── buku.php       # Antarmuka CRUD, pencarian, & filter buku
├── Dashboard/         # Modul Halaman Utama
│   └── dashboard.php  # Dashboard statistik & shortcut aplikasi
├── Kategori/          # Modul Pengelolaan Master Kategori
│   └── kategori.php   # Antarmuka CRUD kategori buku
├── Laporan/           # Modul Reporting & Ekspor PDF
│   ├── cetak_pdf.php  # Template cetak laporan PDF
│   └── dokumentasi_pdf.php
├── Peminjaman/        # Modul Transaksi Sirkulasi Buku
│   └── peminjaman.php # Pencatatan peminjaman & pengembalian buku
├── classes/           # Class Backend Berbasis OOP (Business Logic)
│   ├── Auth.php
│   ├── Buku.php
│   ├── Database.php
│   ├── Kategori.php
│   └── Peminjaman.php
├── database/          # Skrip Struktur Basis Data SQL
│   └── schema.sql     # Skrip DDL tabel & data dummy awal
├── includes/          # Komponen UI Template (Modular Layout)
│   ├── header.php     # Topbar, Navbar, Sidebar, & CDN Library
│   └── footer.php     # Footer & Scripts JS
├── config.php         # File konfigurasi global aplikasi
├── index.php          # Entry point utama (Redirect ke Auth/login.php)
└── README.md          # Dokumentasi utama proyek
```

---

## 🚀 Panduan Instalasi & Cara Menjalankan

### 1. Prasyarat Sistem
- **XAMPP** (dengan PHP v8.2+ dan MySQL/MariaDB) terinstal di komputer.
- Browser modern (Google Chrome, Microsoft Edge, atau Mozilla Firefox).

### 2. Langkah-Langkah Pemasangan
1. **Clone atau Letakkan Folder Proyek:**
   Salin folder proyek ini ke dalam direktori server local (`htdocs` XAMPP):
   ```bash
   C:\xampp\htdocs\BNSP
   ```

2. **Import Database MySQL:**
   - Buka **phpMyAdmin** di browser: `http://localhost/phpmyadmin`
   - Buat database baru bernama `db_perpustakaan` *(atau biarkan skrip membuat secara otomatis)*.
   - Import file SQL yang berada di lokasi: `database/schema.sql`.

3. **Konfigurasi Database (Jika Diperlukan):**
   Koneksi database dapat disesuaikan pada file `classes/Database.php` atau `config.php`:
   ```php
   private $host = "localhost";
   private $db_name = "db_perpustakaan";
   private $username = "root";
   private $password = "";
   ```

4. **Jalankan Aplikasi:**
   - Pastikan Module **Apache** dan **MySQL** pada XAMPP Control Panel dalam status **Running**.
   - Akses URL berikut di browser Anda:
     ```
     http://localhost/BNSP
     ```

---

## 🔑 Akun Pengujian (Default Credentials)

Gunakan akun berikut untuk melakukan pengujian login pada aplikasi:

| Role / Hak Akses | Username | Password |
|---|---|---|
| **Administrator** | `admin` | `admin123` |

---

## 🧪 Skenario Pengujian (Testing Matrix)

| No | Modul | Skenario Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 1 | Autentikasi | Login dengan kredensial valid | Berhasil masuk ke `dashboard.php` | ✅ PASS |
| 2 | Autentikasi | Login dengan password salah | Menampilkan notifikasi validasi error | ✅ PASS |
| 3 | Master Buku | Tambah data buku baru | Data tersimpan di DB & muncul pada tabel | ✅ PASS |
| 4 | Master Buku | Cari judul / filter kategori | Tabel hanya menampilkan data sesuai filter | ✅ PASS |
| 5 | Peminjaman | Catat peminjaman buku baru | Stok buku berkurang 1 otomatis | ✅ PASS |
| 6 | Pengembalian | Klik Pengembalian Buku | Status diperbarui & stok bertambah 1 | ✅ PASS |
| 7 | Cetak Laporan | Klik tombol "Cetak Laporan PDF" | File laporan PDF ter-generate & dapat diunduh | ✅ PASS |

---

## 📄 Lisensi & Hak Cipta

Dikembangkan untuk kebutuhan Uji Kompetensi Sertifikasi BNSP Skema **Pemrogram Muda (Associate Programmer)**.
