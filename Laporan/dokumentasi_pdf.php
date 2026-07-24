<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Auth.php';

$currentUser = Auth::user();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokumentasi Program - BNSP FR.IA.02</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- html2pdf Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #1e293b;
            background: #f8fafc;
        }

        .doc-card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        pre {
            background: #0f172a;
            color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .table-doc th, .table-doc td {
            font-size: 0.85rem;
            padding: 8px 12px;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .doc-card { shadow: none; padding: 0; }
        }
    </style>
</head>
<body>

<div class="container no-print py-4">
    <div class="alert alert-primary d-flex justify-content-between align-items-center shadow-sm">
        <div>
            <i class="fa-solid fa-file-signature me-2"></i> Dokumen Kelompok Pekerjaan 2: Debugging & Dokumentasi Program.
        </div>
        <div>
            <a href="<?= getBaseUrl() ?>/Dashboard/dashboard.php" class="btn btn-outline-secondary btn-sm me-2">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
            <button onclick="downloadDocPDF()" class="btn btn-danger fw-semibold btn-sm">
                <i class="fa-solid fa-file-pdf me-1"></i> Unduh PDF Dokumentasi
            </button>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div id="docContent" class="doc-card">
        <div class="text-center border-bottom pb-3 mb-4">
            <h3 class="fw-bold">DOKUMEN KODE PROGRAM & PEMROGRAMAN</h3>
            <h5 class="text-primary fw-semibold">SKEMA: PEMROGRAM MUDA (ASSOCIATE PROGRAMMER)</h5>
            <p class="text-muted small mb-0">Kode Dokumen: FR.IA.02 - Tugas Praktik Demonstrasi | LSP EDI</p>
        </div>

        <h5 class="fw-bold text-dark mt-4 mb-3">A. Spesifikasi Lingkungan Pengembangan</h5>
        <table class="table table-bordered table-doc mb-4">
            <tr class="table-light"><th width="30%">Tools & Software</th><td>Visual Studio Code, Apache (XAMPP), MySQL Server 8.3, Chrome Browser</td></tr>
            <tr><th>Bahasa Pemrograman</th><td>PHP 8.2 (OOP & PDO), HTML5, CSS3, JavaScript (ES6)</td></tr>
            <tr class="table-light"><th>Library & Framework</th><td>Bootstrap 5.3, FontAwesome 6.4, html2pdf.js (Export PDF), Google Fonts</td></tr>
        </table>

        <h5 class="fw-bold text-dark mt-4 mb-3">B. Arsitektur Class & OOP (Pemrograman Berorientasi Objek)</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <h6 class="fw-bold text-indigo"><i class="fa-solid fa-database me-2"></i>Class Database</h6>
                    <small class="text-muted d-block">Metode PDO Singleton Pattern untuk manajemen koneksi MySQL tunggal yang terenkapsulasi.</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <h6 class="fw-bold text-indigo"><i class="fa-solid fa-shield-halved me-2"></i>Class Auth</h6>
                    <small class="text-muted d-block">Metode autentikasi login, password hashing, dan sesi pengguna.</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 bg-light">
                    <h6 class="fw-bold text-indigo"><i class="fa-solid fa-book me-2"></i>Class Buku</h6>
                    <small class="text-muted d-block">Fungsi CRUD data buku, pencarian kata kunci, dan filter kategori.</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 bg-light">
                    <h6 class="fw-bold text-indigo"><i class="fa-solid fa-tags me-2"></i>Class Kategori</h6>
                    <small class="text-muted d-block">Fungsi CRUD data kategori dan genre buku.</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 bg-light">
                    <h6 class="fw-bold text-indigo"><i class="fa-solid fa-right-left me-2"></i>Class Peminjaman</h6>
                    <small class="text-muted d-block">Fungsi sirkulasi peminjaman, pengembalian, dan otomatisasi stok.</small>
                </div>
            </div>
        </div>

        <h5 class="fw-bold text-dark mt-4 mb-3">C. Hasil Pengujian & Debugging Program</h5>
        <table class="table table-striped table-bordered table-doc mb-4">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Fitur Unit</th>
                    <th>Pengujian Skenario</th>
                    <th>Hasil Diharapkan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>1</td><td>Autentikasi</td><td>Login username & password valid</td><td>Masuk ke dashboard</td><td><span class="badge bg-success">PASS</span></td></tr>
                <tr><td>2</td><td>CRUD Buku</td><td>Tambah, Edit, Hapus Buku</td><td>Perubahan tersimpan di DB</td><td><span class="badge bg-success">PASS</span></td></tr>
                <tr><td>3</td><td>Filter & Search</td><td>Cari judul buku & filter kategori</td><td>Data terfilter sesuai pencarian</td><td><span class="badge bg-success">PASS</span></td></tr>
                <tr><td>4</td><td>Transaksi Peminjaman</td><td>Pinjam buku baru</td><td>Stok buku berkurang otomatis</td><td><span class="badge bg-success">PASS</span></td></tr>
                <tr><td>5</td><td>Transaksi Pengembalian</td><td>Klik kembalikan buku</td><td>Stok buku bertambah otomatis</td><td><span class="badge bg-success">PASS</span></td></tr>
                <tr><td>6</td><td>Cetak PDF</td><td>Klik Cetak Laporan PDF</td><td>File PDF ter-generate sempurna</td><td><span class="badge bg-success">PASS</span></td></tr>
            </tbody>
        </table>

        <div class="mt-5 pt-4 border-top">
            <div class="row text-center">
                <div class="col-6">
                    <p class="mb-5 fw-bold">Asesor Competency,</p>
                    <p class="mb-0">( ________________________ )</p>
                </div>
                <div class="col-6">
                    <p class="mb-5 fw-bold">Asesi / Pemrogram Muda,</p>
                    <p class="mb-0">( <strong><?= htmlspecialchars($currentUser['nama_lengkap'] ?? 'Asesi') ?></strong> )</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadDocPDF() {
    const element = document.getElementById('docContent');
    const opt = {
        margin:       10,
        filename:     'Dokumentasi_Program_BNSP_Pemrogram_Muda.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save();
}
</script>
</body>
</html>
