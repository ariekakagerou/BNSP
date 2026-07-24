<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Peminjaman.php';
require_once __DIR__ . '/../classes/Buku.php';

$peminjamanModel = new Peminjaman();
$bukuModel = new Buku();

$message = "";
$messageType = "success";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $data = [
            'id_buku' => (int)$_POST['id_buku'],
            'nama_peminjam' => trim($_POST['nama_peminjam']),
            'tanggal_pinjam' => $_POST['tanggal_pinjam'],
            'tanggal_kembali' => $_POST['tanggal_kembali']
        ];
        if ($peminjamanModel->create($data)) {
            $message = "Transaksi peminjaman berhasil dicatat & stok buku telah diperbarui!";
        } else {
            $message = "Gagal mencatat peminjaman.";
            $messageType = "danger";
        }
    } elseif ($action === 'return') {
        $id = (int)$_POST['id'];
        $id_buku = (int)$_POST['id_buku'];
        if ($peminjamanModel->returnBook($id, $id_buku)) {
            $message = "Buku berhasil dikembalikan & stok buku telah ditambah kembali!";
        } else {
            $message = "Gagal memperbarui status pengembalian.";
            $messageType = "danger";
        }
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id'];
        if ($peminjamanModel->delete($id)) {
            $message = "Catatan transaksi berhasil dihapus!";
        } else {
            $message = "Gagal menghapus catatan.";
            $messageType = "danger";
        }
    }
}

$listPeminjaman = $peminjamanModel->getAll();
$listBukuAvailable = $bukuModel->getAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <!-- Header Title & Action -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-amber-50 text-amber-700 fw-bold small mb-2 border border-amber-200" style="background:#fef3c7; color:#92400e;">
                <i class="fa-solid fa-layer-group"></i> Kelompok Pekerjaan 1: Transaksi Peminjaman Buku
            </div>
            <h3 class="fw-extrabold text-dark mb-0" style="font-weight: 800;"><i class="fa-solid fa-right-left text-primary me-2"></i>Sirkulasi Peminjaman Buku</h3>
            <p class="text-muted small mb-0">Pencatatan peminjaman, batas pengembalian, dan otomatisasi penyesuaian stok</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= getBaseUrl() ?>/Laporan/cetak_pdf.php" target="_blank" class="btn btn-outline-danger fw-bold d-flex align-items-center gap-2">
                <i class="fa-solid fa-file-pdf fs-5"></i> <span>Cetak Laporan PDF</span>
            </a>
            <button class="btn btn-primary btn-primary-custom d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahPeminjaman">
                <i class="fa-solid fa-plus-circle fs-5"></i> <span>Pinjam Buku Baru</span>
            </button>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2 fs-5 align-middle"></i> <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Table Card -->
    <div class="card card-custom p-4">
        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Peminjam</th>
                        <th>Buku Yang Dipinjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Pengembalian</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($listPeminjaman)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fa-solid fa-hand-holding-hand fs-1 text-secondary opacity-50 mb-3 d-block"></i>
                                Belum ada data transaksi peminjaman. Klik tombol Pinjam Buku Baru di atas.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($listPeminjaman as $index => $p): ?>
                            <tr>
                                <td class="fw-bold text-muted"><?= $index + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="p-2 rounded-circle bg-primary-subtle text-primary fw-bold" style="width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($p['nama_peminjam']) ?></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-indigo-900"><?= htmlspecialchars($p['judul_buku'] ?? '-') ?></div>
                                    <small class="text-muted"><i class="fa-solid fa-user-pen me-1"></i> <?= htmlspecialchars($p['pengarang'] ?? '') ?></small>
                                </td>
                                <td><small class="fw-semibold text-secondary"><i class="fa-solid fa-calendar-day me-1"></i> <?= date('d M Y', strtotime($p['tanggal_pinjam'])) ?></small></td>
                                <td><small class="fw-semibold text-secondary"><i class="fa-solid fa-calendar-check me-1"></i> <?= date('d M Y', strtotime($p['tanggal_kembali'])) ?></small></td>
                                <td class="text-center">
                                    <?php if ($p['status'] == 'dipinjam'): ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-3 py-1.5 fw-bold">
                                            <i class="fa-solid fa-hourglass-half me-1"></i> Sedang Dipinjam
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success-subtle text-success-emphasis border border-success px-3 py-1.5 fw-bold">
                                            <i class="fa-solid fa-circle-check me-1"></i> Sudah Dikembalikan
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <?php if ($p['status'] == 'dipinjam'): ?>
                                            <form method="POST" action="" class="d-inline">
                                                <input type="hidden" name="action" value="return">
                                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                <input type="hidden" name="id_buku" value="<?= $p['id_buku'] ?>">
                                                <button type="submit" class="btn btn-sm btn-success fw-bold px-2.5" title="Kembalikan Buku Ini">
                                                    <i class="fa-solid fa-check me-1"></i> Kembalikan
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <button class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalHapusPeminjaman<?= $p['id'] ?>"
                                                title="Hapus Transaksi">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Hapus Peminjaman -->
                            <div class="modal fade" id="modalHapusPeminjaman<?= $p['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                                        <form method="POST" action="">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Konfirmasi Hapus Transaksi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body py-4 text-center">
                                                <div class="p-3 rounded-circle bg-danger-subtle text-danger d-inline-block mb-3">
                                                    <i class="fa-solid fa-trash fs-2"></i>
                                                </div>
                                                <h5>Hapus Catatan Ini?</h5>
                                                <p class="text-muted small mb-0">Catatan peminjaman untuk <strong><?= htmlspecialchars($p['nama_peminjam']) ?></strong> akan dihapus.</p>
                                            </div>
                                            <div class="modal-footer border-top-0 pt-0 justify-content-center">
                                                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger rounded-3 px-4">Hapus Catatan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Peminjaman -->
<div class="modal fade" id="modalTambahPeminjaman" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
            <form method="POST" action="">
                <input type="hidden" name="action" value="create">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-plus-circle text-primary me-2"></i>Catat Peminjaman Buku Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Nama Peminjam / Anggota</label>
                        <input type="text" name="nama_peminjam" class="form-control" placeholder="Masukkan nama peminjam" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Pilih Buku Yang Tersedia</label>
                        <select name="id_buku" class="form-select" required>
                            <option value="">-- Pilih Judul Buku --</option>
                            <?php foreach ($listBukuAvailable as $b): ?>
                                <option value="<?= $b['id'] ?>" <?= ($b['stok'] <= 0) ? 'disabled' : '' ?>>
                                    <?= htmlspecialchars($b['judul']) ?> (Sisa Stok: <?= $b['stok'] ?> Unit)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Tanggal Pinjam</label>
                            <input type="date" name="tanggal_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Batas Pengembalian</label>
                            <input type="date" name="tanggal_kembali" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-primary-custom">Simpan Peminjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
