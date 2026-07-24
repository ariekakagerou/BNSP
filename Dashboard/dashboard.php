<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Buku.php';
require_once __DIR__ . '/../classes/Kategori.php';
require_once __DIR__ . '/../classes/Peminjaman.php';

$bukuModel = new Buku();
$kategoriModel = new Kategori();
$peminjamanModel = new Peminjaman();

$totalBuku = $bukuModel->count();
$totalKategori = $kategoriModel->count();
$peminjamanAktif = $peminjamanModel->countActive();
$totalPeminjaman = $peminjamanModel->countTotal();

$bukuTerbaru = $bukuModel->getAll();
$bukuTerbaru = array_slice($bukuTerbaru, 0, 5);

$peminjamanTerbaru = $peminjamanModel->getAll();
$peminjamanTerbaru = array_slice($peminjamanTerbaru, 0, 5);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <!-- Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 text-white p-4 p-md-5 rounded-4 shadow-lg overflow-hidden position-relative" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);">
                <!-- Background Graphic -->
                <div class="position-absolute end-0 bottom-0 opacity-10 pe-4 pb-2 d-none d-md-block" style="font-size: 14rem; line-height: 0;">
                    <i class="fa-solid fa-book-open"></i>
                </div>

                <div class="row align-items-center position-relative z-1">
                    <div class="col-lg-8">
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 text-indigo-200 small mb-3 border border-white border-opacity-10" style="backdrop-filter: blur(8px);">
                            <i class="fa-solid fa-sparkles text-amber-400" style="color: #fbbf24;"></i> Sistem Informasi Manajemen Perpustakaan
                        </div>
                        <h2 class="fw-extrabold display-6 mb-2" style="font-weight: 800;">Selamat Datang, <?= htmlspecialchars($currentUser['nama_lengkap']) ?>! 👋</h2>
                        <p class="text-white-50 leading-relaxed mb-4 mb-lg-0" style="font-size: 1.05rem;">
                            Kelola koleksi buku, kategori, dan transaksi peminjaman secara efisien dalam satu tempat terpadu.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                            <a href="<?= getBaseUrl() ?>/Laporan/cetak_pdf.php" target="_blank" class="btn btn-light fw-bold text-indigo-900 rounded-3 shadow-sm px-3.5 py-2.5">
                                <i class="fa-solid fa-file-pdf text-danger me-1.5 fs-5 align-middle"></i> Cetak Laporan PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Metric Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card bg-gradient-indigo shadow-sm">
                <div class="small fw-bold text-uppercase tracking-wider opacity-75">Total Koleksi Buku</div>
                <div class="display-5 fw-extrabold my-1" style="font-weight: 800;"><?= $totalBuku ?></div>
                <div class="small d-flex align-items-center gap-1"><i class="fa-solid fa-book-bookmark"></i> Judul Terdaftar</div>
                <i class="fa-solid fa-book-open icon-bg"></i>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card bg-gradient-emerald shadow-sm">
                <div class="small fw-bold text-uppercase tracking-wider opacity-75">Kategori Buku</div>
                <div class="display-5 fw-extrabold my-1" style="font-weight: 800;"><?= $totalKategori ?></div>
                <div class="small d-flex align-items-center gap-1"><i class="fa-solid fa-tags"></i> Kategori Aktif</div>
                <i class="fa-solid fa-layer-group icon-bg"></i>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card bg-gradient-amber shadow-sm">
                <div class="small fw-bold text-uppercase tracking-wider opacity-75">Peminjaman Aktif</div>
                <div class="display-5 fw-extrabold my-1" style="font-weight: 800;"><?= $peminjamanAktif ?></div>
                <div class="small d-flex align-items-center gap-1"><i class="fa-solid fa-clock"></i> Sedang Dipinjam</div>
                <i class="fa-solid fa-hand-holding-hand icon-bg"></i>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card bg-gradient-rose shadow-sm">
                <div class="small fw-bold text-uppercase tracking-wider opacity-75">Total Transaksi</div>
                <div class="display-5 fw-extrabold my-1" style="font-weight: 800;"><?= $totalPeminjaman ?></div>
                <div class="small d-flex align-items-center gap-1"><i class="fa-solid fa-receipt"></i> Riwayat Transaksi</div>
                <i class="fa-solid fa-file-invoice icon-bg"></i>
            </div>
        </div>
    </div>

    <!-- Quick Action Navigation Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="fw-bold text-secondary small text-uppercase tracking-wider"><i class="fa-solid fa-bolt text-warning me-1.5"></i>Aksi Cepat Manajemen:</div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= getBaseUrl() ?>/Buku/buku.php" class="btn btn-sm btn-outline-primary rounded-3 font-semibold">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Buku Baru
                        </a>
                        <a href="<?= getBaseUrl() ?>/Kategori/kategori.php" class="btn btn-sm btn-outline-success rounded-3 font-semibold">
                            <i class="fa-solid fa-tag me-1"></i> Tambah Kategori
                        </a>
                        <a href="<?= getBaseUrl() ?>/Peminjaman/peminjaman.php" class="btn btn-sm btn-outline-warning rounded-3 font-semibold">
                            <i class="fa-solid fa-hand-holding me-1"></i> Catat Peminjaman
                        </a>
                        <a href="<?= getBaseUrl() ?>/Laporan/dokumentasi_pdf.php" target="_blank" class="btn btn-sm btn-outline-dark rounded-3 font-semibold">
                            <i class="fa-solid fa-file-signature me-1"></i> Laporan Pengujian BNSP
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tables Row -->
    <div class="row g-4">
        <!-- Recent Books -->
        <div class="col-lg-6">
            <div class="card card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-book text-primary me-2"></i>Buku Terbaru</h5>
                        <small class="text-muted">Koleksi buku yang baru ditambahkan</small>
                    </div>
                    <a href="<?= getBaseUrl() ?>/Buku/buku.php" class="btn btn-sm btn-light border rounded-3 fw-semibold">Kelola Semua <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-custom align-middle">
                        <thead>
                            <tr>
                                <th>Judul Buku</th>
                                <th>Kategori</th>
                                <th class="text-center">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bukuTerbaru)): ?>
                                <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data buku terdaftar.</td></tr>
                            <?php else: ?>
                                <?php foreach ($bukuTerbaru as $b): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($b['judul']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($b['pengarang']) ?></small>
                                        </td>
                                        <td><span class="badge bg-indigo-50 text-indigo-700 border" style="background:#e0e7ff; color:#3730a3;"><?= htmlspecialchars($b['nama_kategori'] ?? 'Umum') ?></span></td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1 fw-bold"><?= $b['stok'] ?> Unit</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-lg-6">
            <div class="card card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-clock-rotate-left text-warning me-2"></i>Peminjaman Terbaru</h5>
                        <small class="text-muted">Aktivitas peminjaman anggota terkini</small>
                    </div>
                    <a href="<?= getBaseUrl() ?>/Peminjaman/peminjaman.php" class="btn btn-sm btn-light border rounded-3 fw-semibold">Kelola Semua <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-custom align-middle">
                        <thead>
                            <tr>
                                <th>Peminjam</th>
                                <th>Judul Buku</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($peminjamanTerbaru)): ?>
                                <tr><td colspan="3" class="text-center text-muted py-4">Belum ada transaksi peminjaman.</td></tr>
                            <?php else: ?>
                                <?php foreach ($peminjamanTerbaru as $p): ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?= htmlspecialchars($p['nama_peminjam']) ?></td>
                                        <td class="small text-muted"><?= htmlspecialchars($p['judul_buku'] ?? '-') ?></td>
                                        <td class="text-center">
                                            <?php if ($p['status'] == 'dipinjam'): ?>
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-2.5 py-1"><i class="fa-solid fa-hourglass-half me-1"></i> Dipinjam</span>
                                            <?php else: ?>
                                                <span class="badge bg-success-subtle text-success-emphasis border border-success px-2.5 py-1"><i class="fa-solid fa-circle-check me-1"></i> Kembali</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
