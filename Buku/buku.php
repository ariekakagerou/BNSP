<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Buku.php';
require_once __DIR__ . '/../classes/Kategori.php';

$bukuModel = new Buku();
$kategoriModel = new Kategori();

$message = "";
$messageType = "success";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $data = [
            'id_kategori' => $_POST['id_kategori'],
            'judul' => trim($_POST['judul']),
            'pengarang' => trim($_POST['pengarang']),
            'penerbit' => trim($_POST['penerbit']),
            'tahun_terbit' => (int)$_POST['tahun_terbit'],
            'isbn' => trim($_POST['isbn']),
            'stok' => (int)$_POST['stok']
        ];
        if ($bukuModel->create($data)) {
            $message = "Buku berhasil ditambahkan!";
        } else {
            $message = "Gagal menambahkan buku.";
            $messageType = "danger";
        }
    } elseif ($action === 'update') {
        $id = (int)$_POST['id'];
        $data = [
            'id_kategori' => $_POST['id_kategori'],
            'judul' => trim($_POST['judul']),
            'pengarang' => trim($_POST['pengarang']),
            'penerbit' => trim($_POST['penerbit']),
            'tahun_terbit' => (int)$_POST['tahun_terbit'],
            'isbn' => trim($_POST['isbn']),
            'stok' => (int)$_POST['stok']
        ];
        if ($bukuModel->update($id, $data)) {
            $message = "Data buku berhasil diperbarui!";
        } else {
            $message = "Gagal memperbarui data buku.";
            $messageType = "danger";
        }
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id'];
        if ($bukuModel->delete($id)) {
            $message = "Buku berhasil dihapus!";
        } else {
            $message = "Gagal menghapus buku.";
            $messageType = "danger";
        }
    }
}

$search = trim($_GET['search'] ?? '');
$kategori_filter = trim($_GET['kategori'] ?? '');

$listBuku = $bukuModel->getAll($search, $kategori_filter);
$listKategori = $kategoriModel->getAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <!-- Header Title & Action -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-indigo-50 text-indigo-700 fw-bold small mb-2 border border-indigo-200" style="background:#e0e7ff; color:#3730a3;">
                <i class="fa-solid fa-layer-group"></i> Kelompok Pekerjaan 1: CRUD Data Buku
            </div>
            <h3 class="fw-extrabold text-dark mb-0" style="font-weight: 800;"><i class="fa-solid fa-book text-primary me-2"></i>Manajemen Data Buku</h3>
            <p class="text-muted small mb-0">Kelola informasi koleksi buku, ketersediaan stok, dan pencarian cepat</p>
        </div>
        <button class="btn btn-primary btn-primary-custom d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahBuku">
            <i class="fa-solid fa-plus-circle fs-5"></i> <span>Tambah Buku Baru</span>
        </button>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2 fs-5 align-middle"></i> <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filter & Search Toolbar -->
    <div class="card card-custom p-3.5 mb-4">
        <form method="GET" action="" class="row g-2.5 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Judul Buku, Pengarang, atau ISBN..." value="<?= htmlspecialchars($search) ?>">
                </div>
            </div>
            <div class="col-md-4">
                <select name="kategori" class="form-select">
                    <option value="">-- Semua Kategori Buku --</option>
                    <?php foreach ($listKategori as $kat): ?>
                        <option value="<?= $kat['id'] ?>" <?= ($kategori_filter == $kat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-indigo text-white w-100 fw-bold" style="background:#4f46e5;"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                <a href="buku.php" class="btn btn-outline-secondary" title="Reset Filter"><i class="fa-solid fa-rotate-left"></i></a>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="card card-custom p-4">
        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul Buku & ISBN</th>
                        <th>Kategori</th>
                        <th>Pengarang</th>
                        <th>Penerbit / Tahun</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($listBuku)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fa-solid fa-book-open fs-1 text-secondary opacity-50 mb-3 d-block"></i>
                                Tidak ada data buku ditemukan. Silakan tambahkan buku baru atau sesuaikan kata kunci pencarian.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($listBuku as $index => $b): ?>
                            <tr>
                                <td class="fw-bold text-muted"><?= $index + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-2.5 rounded-3 bg-indigo-50 text-indigo-600 d-none d-sm-block" style="background:#e0e7ff; color:#4f46e5;">
                                            <i class="fa-solid fa-book fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-6 mb-0"><?= htmlspecialchars($b['judul']) ?></div>
                                            <small class="text-muted"><i class="fa-solid fa-barcode me-1"></i> <?= htmlspecialchars($b['isbn'] ?? '-') ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-indigo-50 text-indigo-700 border border-indigo-200 px-3 py-1.5 fw-semibold" style="background:#e0e7ff; color:#3730a3; border-color:#c7d2fe;">
                                        <?= htmlspecialchars($b['nama_kategori'] ?? 'Umum') ?>
                                    </span>
                                </td>
                                <td class="fw-medium text-secondary"><?= htmlspecialchars($b['pengarang']) ?></td>
                                <td class="small text-muted">
                                    <i class="fa-solid fa-building me-1"></i> <?= htmlspecialchars($b['penerbit']) ?> 
                                    <span class="badge bg-light text-dark border ms-1"><?= $b['tahun_terbit'] ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($b['stok'] > 0): ?>
                                        <span class="badge bg-success-subtle text-success border border-success px-3 py-1.5 fw-bold"><?= $b['stok'] ?> Unit Tersedia</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-1.5 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Stok Habis</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary rounded-start-3" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditBuku<?= $b['id'] ?>"
                                                title="Edit Buku">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger rounded-end-3" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalHapusBuku<?= $b['id'] ?>"
                                                title="Hapus Buku">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit Buku -->
                            <div class="modal fade" id="modalEditBuku<?= $b['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                                        <form method="POST" action="">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Data Buku</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-secondary">Judul Buku</label>
                                                    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($b['judul']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-secondary">Kategori Buku</label>
                                                    <select name="id_kategori" class="form-select" required>
                                                        <?php foreach ($listKategori as $kat): ?>
                                                            <option value="<?= $kat['id'] ?>" <?= ($b['id_kategori'] == $kat['id']) ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($kat['nama_kategori']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold text-secondary">Pengarang</label>
                                                        <input type="text" name="pengarang" class="form-control" value="<?= htmlspecialchars($b['pengarang']) ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold text-secondary">Penerbit</label>
                                                        <input type="text" name="penerbit" class="form-control" value="<?= htmlspecialchars($b['penerbit']) ?>" required>
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-bold text-secondary">Tahun Terbit</label>
                                                        <input type="number" name="tahun_terbit" class="form-control" value="<?= $b['tahun_terbit'] ?>" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-bold text-secondary">Kode ISBN</label>
                                                        <input type="text" name="isbn" class="form-control" value="<?= htmlspecialchars($b['isbn']) ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-bold text-secondary">Jumlah Stok</label>
                                                        <input type="number" name="stok" class="form-control" value="<?= $b['stok'] ?>" min="0" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary btn-primary-custom">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Hapus Buku -->
                            <div class="modal fade" id="modalHapusBuku<?= $b['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                                        <form method="POST" action="">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Konfirmasi Hapus Buku</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body py-4 text-center">
                                                <div class="p-3 rounded-circle bg-danger-subtle text-danger d-inline-block mb-3">
                                                    <i class="fa-solid fa-trash fs-2"></i>
                                                </div>
                                                <h5>Apakah Anda yakin?</h5>
                                                <p class="text-muted small mb-0">Buku <strong><?= htmlspecialchars($b['judul']) ?></strong> akan dihapus permanen dari basis data.</p>
                                            </div>
                                            <div class="modal-footer border-top-0 pt-0 justify-content-center">
                                                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger rounded-3 px-4">Hapus Permanen</button>
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

<!-- Modal Tambah Buku -->
<div class="modal fade" id="modalTambahBuku" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
            <form method="POST" action="">
                <input type="hidden" name="action" value="create">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-plus-circle text-primary me-2"></i>Tambah Buku Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Pemrograman Web dengan PHP & MySQL" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Kategori Buku</label>
                        <select name="id_kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($listKategori as $kat): ?>
                                <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Pengarang</label>
                            <input type="text" name="pengarang" class="form-control" placeholder="Nama Pengarang" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control" placeholder="Nama Penerbit" required>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" class="form-control" value="<?= date('Y') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Kode ISBN</label>
                            <input type="text" name="isbn" class="form-control" placeholder="978-...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Jumlah Stok</label>
                            <input type="number" name="stok" class="form-control" value="5" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-primary-custom">Simpan Buku</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
