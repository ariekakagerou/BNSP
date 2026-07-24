<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Buku.php';
require_once __DIR__ . '/../classes/Kategori.php';
require_once __DIR__ . '/../classes/Peminjaman.php';

$currentUser = Auth::user();
$bukuModel = new Buku();
$kategoriModel = new Kategori();
$peminjamanModel = new Peminjaman();

$listBuku = $bukuModel->getAll();
$listPeminjaman = $peminjamanModel->getAll();
$totalBuku = $bukuModel->count();
$peminjamanAktif = $peminjamanModel->countActive();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perpustakaan Digital - BNSP FR.IA.02</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- jsPDF & html2pdf Library (Third Party Library) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            padding: 20px;
        }

        .header-kop {
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .table-pdf {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .table-pdf th, .table-pdf td {
            border: 1px solid #000;
            padding: 6px 10px;
        }

        .table-pdf th {
            background-color: #f2f2f2;
            text-align: center;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="container no-print mb-4">
    <div class="alert alert-info d-flex justify-content-between align-items-center shadow-sm">
        <div>
            <i class="fa-solid fa-file-pdf me-2"></i> Dokumen Siap Diunduh / Dicetak sebagai PDF.
        </div>
        <div>
            <button onclick="downloadPDF()" class="btn btn-danger fw-semibold btn-sm me-2">
                <i class="fa-solid fa-download me-1"></i> Unduh File PDF
            </button>
            <button onclick="window.print()" class="btn btn-secondary fw-semibold btn-sm">
                <i class="fa-solid fa-print me-1"></i> Cetak Langsung
            </button>
        </div>
    </div>
</div>

<div id="reportContent" class="bg-white p-4">
    <!-- KOP DOKUMEN -->
    <div class="header-kop text-center">
        <h4 class="fw-bold mb-1">LAPORAN MANAJEMEN PERPUSTAKAAN DIGITAL</h4>
        <h6 class="mb-1">SKEMA SERTIFIKASI: PEMROGRAM MUDA (ASSOCIATE PROGRAMMER)</h6>
        <p class="small mb-0">Dokumen Tugas Praktik Demonstrasi (FR.IA.02) | Tanggal Cetak: <?= date('d F Y H:i') ?></p>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="mb-4">
        <h6 class="fw-bold text-uppercase border-bottom pb-1">I. Ringkasan Statistik</h6>
        <table class="table-pdf mb-3">
            <tr>
                <td width="30%"><strong>Total Judul Buku</strong></td>
                <td><?= $totalBuku ?> Judul</td>
                <td width="30%"><strong>Status Sistem</strong></td>
                <td><span class="badge bg-success">Aktif / Berjalan</span></td>
            </tr>
            <tr>
                <td><strong>Peminjaman Aktif</strong></td>
                <td><?= $peminjamanAktif ?> Transaksi</td>
                <td><strong>Web Server & Database</strong></td>
                <td>XAMPP (Apache) / MySQL PDO</td>
            </tr>
        </table>
    </div>

    <!-- Data Koleksi Buku -->
    <div class="mb-4">
        <h6 class="fw-bold text-uppercase border-bottom pb-1">II. Daftar Koleksi Buku</h6>
        <table class="table-pdf">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th width="8%">Tahun</th>
                    <th>ISBN</th>
                    <th width="8%">Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listBuku as $i => $b): ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td><strong><?= htmlspecialchars($b['judul']) ?></strong></td>
                        <td><?= htmlspecialchars($b['nama_kategori'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($b['pengarang']) ?></td>
                        <td><?= htmlspecialchars($b['penerbit']) ?></td>
                        <td class="text-center"><?= $b['tahun_terbit'] ?></td>
                        <td><?= htmlspecialchars($b['isbn'] ?? '-') ?></td>
                        <td class="text-center"><?= $b['stok'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Data Peminjaman -->
    <div class="mb-4">
        <h6 class="fw-bold text-uppercase border-bottom pb-1">III. Rekapitulasi Peminjaman Buku</h6>
        <table class="table-pdf">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Peminjam</th>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listPeminjaman as $i => $p): ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($p['nama_peminjam']) ?></td>
                        <td><?= htmlspecialchars($p['judul_buku'] ?? '-') ?></td>
                        <td class="text-center"><?= date('d/m/Y', strtotime($p['tanggal_pinjam'])) ?></td>
                        <td class="text-center"><?= date('d/m/Y', strtotime($p['tanggal_kembali'])) ?></td>
                        <td class="text-center"><?= ucfirst($p['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Lembar Pengesahan -->
    <div class="mt-5 pt-3">
        <table width="100%">
            <tr>
                <td width="50%" class="text-center">
                    <p class="mb-1">Mengetahui,</p>
                    <p class="fw-bold mb-5">Asesor Competency</p>
                    <p class="mb-0">( ________________________ )</p>
                    <small>No. Reg: __________________</small>
                </td>
                <td width="50%" class="text-center">
                    <p class="mb-1">Dibuat Oleh,</p>
                    <p class="fw-bold mb-5">Asesi / Pemrogram Muda</p>
                    <p class="mb-0">( <strong><?= htmlspecialchars($currentUser['nama_lengkap'] ?? 'Asesi') ?></strong> )</p>
                    <small>Tanggal: <?= date('d/m/Y') ?></small>
                </td>
            </tr>
        </table>
    </div>
</div>

<script>
function downloadPDF() {
    const element = document.getElementById('reportContent');
    const opt = {
        margin:       10,
        filename:     'Laporan_Perpustakaan_BNSP.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save();
}
</script>

</body>
</html>
