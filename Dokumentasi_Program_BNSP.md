# DOKUMEN KODE PROGRAM & PEMROGRAMAN
**Skema Sertifikasi:** Pemrogram Muda (Associate Programmer)  
**Nomor Skema:** 005/SSK/LSP-EDI/I/2026  
**Kode Dokumen:** FR.IA.02 - Tugas Praktik Demonstrasi  
**Judul Aplikasi:** Sistem Informasi Perpustakaan Digital (Manajemen Buku)  

---

## KELOMPOK PEKERJAAN 2: DEBUGGING & DOKUMENTASI PROGRAM

### A. Spesifikasi Lingkungan Pengembangan (Tools & Environment)

1. **Tools / Software Pemrograman yang digunakan:**
   - **Text Editor / IDE:** Visual Studio Code / Antigravity IDE
   - **Web Server:** Apache (via XAMPP v8.2)
   - **Database Management System:** MySQL Server 8.3 (MariaDB Compatible)
   - **Web Browser:** Google Chrome / Microsoft Edge

2. **Bahasa Pemrograman:**
   - **PHP v8.2:** Digunakan untuk *Backend Logic*, pengolahan *OOP*, dan koneksi basis data menggunakan *PDO (PHP Data Objects)*.
   - **HTML5 & CSS3:** Digunakan untuk struktur dan styling tampilan interface (*User Interface*).
   - **JavaScript (ES6):** Digunakan untuk interaktivitas modal dialog dan pemicu cetak/export PDF.

3. **Library, Komponen Pre-existing, & Framework:**
   - **Bootstrap v5.3 (CDN):** Frontend CSS Framework untuk tata letak responsif dan komponen UI modern.
   - **FontAwesome v6.4 (CDN):** Icon set library untuk ikon navigasi dan aksi.
   - **html2pdf.js / jsPDF (CDN):** Library pihak ketiga (*3rd Party Library*) untuk generasi dan ekspor dokumen laporan ke format PDF.
   - **Google Fonts (Inter):** Typography modern web.

---

### B. Dokumentasi Kode Program & Arsitektur Sistem

Aplikasi dibangun menggunakan prinsip **Pemrograman Berorientasi Objek (OOP)** dengan pola struktur modul terpisah:

1. **Class `Database` (`classes/Database.php`)**
   - **Fungsi:** Mengelola koneksi ke MySQL menggunakan driver PDO.
   - **Penerapan OOP:** *Singleton Pattern* & *Encapsulation* untuk efisiensi koneksi database tunggal.
   - **Metode Kunci:** `getInstance()`, `getConnection()`.

2. **Class `Auth` (`classes/Auth.php`)**
   - **Fungsi:** Mengelola proses login, verifikasi kata sandi (*password hashing*), dan manajemen *Session* pengguna.
   - **Metode Kunci:** `login($username, $password)`, `check()`, `user()`, `logout()`.

3. **Class `Buku` (`classes/Buku.php`)**
   - **Fungsi:** Mengelola data master buku (Create, Read, Update, Delete), pencarian berdasarkan kata kunci, dan filter kategori.
   - **Metode Kunci:** `getAll($search, $kategori_id)`, `getById($id)`, `create($data)`, `update($id, $data)`, `delete($id)`, `count()`.

4. **Class `Kategori` (`classes/Kategori.php`)**
   - **Fungsi:** Mengelola data kategori/genre buku.
   - **Metode Kunci:** `getAll()`, `getById($id)`, `create($nama, $deskripsi)`, `update($id, $nama, $deskripsi)`, `delete($id)`, `count()`.

5. **Class `Peminjaman` (`classes/Peminjaman.php`)**
   - **Fungsi:** Mengelola transaksi peminjaman dan pengembalian buku serta pembaruan otomatis stok buku.
   - **Metode Kunci:** `getAll()`, `create($data)`, `returnBook($id, $id_buku)`, `delete($id)`, `countActive()`.

---

### C. Daftar Halaman & Modul Tampilan (Minimal 5 Tampilan)

1. **`login.php` (Halaman 1):** Tampilan autentikasi pengguna/admin.
2. **`dashboard.php` (Halaman 2):** Ringkasan statistik koleksi, peminjaman aktif, dan shortcut menu.
3. **`buku.php` (Halaman 3):** Tabel data buku lengkap dengan fitur Tambah, Edit, Hapus, dan Pencarian/Filter.
4. **`kategori.php` (Halaman 4):** Pengelolaan kategori buku (CRUD).
5. **`peminjaman.php` (Halaman 5):** Transaksi pencatatan peminjaman, pengembalian, serta integrasi tombol cetak PDF.
6. **`cetak_pdf.php`:** Halaman ekspor laporan rekapitulasi berformat PDF menggunakan library `html2pdf.js`.

---

### D. Hasil Pengujian & Debugging (Kelompok Pekerjaan 2)

| No | Modul / Fitur | Skenario Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 1 | Autentikasi | Login dengan username/password benar | Berhasil masuk ke dashboard.php | PASS |
| 2 | Autentikasi | Login dengan password salah | Menampilkan pesan error validasi | PASS |
| 3 | Data Buku (CRUD) | Tambah buku baru | Data tersimpan di DB & muncul di tabel | PASS |
| 4 | Data Buku (CRUD) | Edit data buku & ubah stok | Data di DB ter-update secara akurat | PASS |
| 5 | Data Buku (CRUD) | Cari judul / filter kategori | Tabel menampilkan hasil filter spesifik | PASS |
| 6 | Transaksi Peminjaman | Catat peminjaman buku | Stok buku berkurang 1 otomatis | PASS |
| 7 | Transaksi Peminjaman | Klik Pengembalian Buku | Status berubah & stok bertambah 1 | PASS |
| 8 | Cetak Laporan PDF | Klik tombol "Cetak Laporan PDF" | File PDF ter-generate & dapat diunduh | PASS |

---

### E. Lampiran Source Code Utama

#### 1. `classes/Database.php`
```php
<?php
class Database {
    private static $instance = null;
    private $host = "localhost";
    private $db_name = "db_perpustakaan";
    private $username = "root";
    private $password = "";
    private $conn;

    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";charset=utf8mb4", $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $this->conn->exec("CREATE DATABASE IF NOT EXISTS `" . $this->db_name . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->conn->exec("USE `" . $this->db_name . "`");
        } catch (PDOException $e) {
            die("Koneksi Database Gagal: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}
```

#### 2. `classes/Buku.php`
```php
<?php
require_once __DIR__ . '/Database.php';

class Buku {
    private $db;
    private $table = "buku";

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($search = '', $kategori_id = '') {
        $query = "SELECT b.*, k.nama_kategori FROM " . $this->table . " b LEFT JOIN kategori k ON b.id_kategori = k.id WHERE 1=1";
        if (!empty($search)) {
            $query .= " AND (b.judul LIKE :search OR b.pengarang LIKE :search OR b.isbn LIKE :search)";
        }
        if (!empty($kategori_id)) {
            $query .= " AND b.id_kategori = :kategori_id";
        }
        $query .= " ORDER BY b.id DESC";
        $stmt = $this->db->prepare($query);
        if (!empty($search)) {
            $search_param = "%" . $search . "%";
            $stmt->bindParam(':search', $search_param);
        }
        if (!empty($kategori_id)) {
            $stmt->bindParam(':kategori_id', $kategori_id);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
```
