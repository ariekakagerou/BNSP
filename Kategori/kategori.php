<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Kategori.php';

$kategoriModel = new Kategori();

$message = "";
$messageType = "success";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $nama_kategori = trim($_POST['nama_kategori']);
        $deskripsi = trim($_POST['deskripsi']);
        if ($kategoriModel->create($nama_kategori, $deskripsi)) {
            $message = "Kategori berhasil ditambahkan!";
        } else {
            $message = "Gagal menambahkan kategori.";
            $messageType = "danger";
        }
    } elseif ($action === 'update') {
        $id = (int)$_POST['id'];
        $nama_kategori = trim($_POST['nama_kategori']);
        $deskripsi = trim($_POST['deskripsi']);
        if ($kategoriModel->update($id, $nama_kategori, $deskripsi)) {
            $message = "Kategori berhasil diperbarui!";
        } else {
            $message = "Gagal memperbarui kategori.";
            $messageType = "danger";
        }
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id'];
        if ($kategoriModel->delete($id)) {
            $message = "Kategori berhasil dihapus!";
        } else {
            $message = "Gagal menghapus kategori.";
            $messageType = "danger";
        }
    }
}

$listKategori = $kategoriModel->getAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
    <!-- Header Title & Action -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-emerald-50 text-emerald-700 fw-bold small mb-2 border border-emerald-200" style="background:#d1fae5; color:#065f46;">
                <i class="fa-solid fa-layer-group"></i> Kelompok Pekerjaan 1: CRUD Kategori Buku
            </div>
            <h3 class="fw-extrabold text-dark mb-0" style="font-weight: 800;"><i class="fa-solid fa-tags text-primary me-2"></i>Kelola Kategori Buku</h3>
            <p class="text-muted small mb-0">Pengelompokan jenis dan genre koleksi perpustakaan</p>
        </div>
        <button class="btn btn-primary btn-primary-custom d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
            <i class="fa-solid fa-plus-circle fs-5"></i> <span>Tambah Kategori Baru</span>
        </button>
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
                        <th style="width: 60px;">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi Kategori</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($listKategori)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="fa-solid fa-tags fs-1 text-secondary opacity-50 mb-3 d-block"></i>
                                Belum ada kategori buku terdaftar. Klik tombol Tambah Kategori di atas.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($listKategori as $index => $kat): ?>
                            <tr>
                                <td class="fw-bold text-muted"><?= $index + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-2.5 rounded-3 bg-emerald-50 text-emerald-600" style="background:#d1fae5; color:#059669;">
                                            <i class="fa-solid fa-tag fs-5"></i>
                                        </div>
                                        <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($kat['nama_kategori']) ?></div>
                                    </div>
                                </td>
                                <td class="text-secondary"><?= htmlspecialchars($kat['deskripsi'] ?? 'Tidak ada deskripsi.') ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary rounded-start-3" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditKategori<?= $kat['id'] ?>">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger rounded-end-3" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalHapusKategori<?= $kat['id'] ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit Kategori -->
                            <div class="modal fade" id="modalEditKategori<?= $kat['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                                        <form method="POST" action="">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="id" value="<?= $kat['id'] ?>">
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Kategori</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-secondary">Nama Kategori</label>
                                                    <input type="text" name="nama_kategori" class="form-control" value="<?= htmlspecialchars($kat['nama_kategori']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-secondary">Deskripsi Singkat</label>
                                                    <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($kat['deskripsi']) ?></textarea>
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

                            <!-- Modal Hapus Kategori -->
                            <div class="modal fade" id="modalHapusKategori<?= $kat['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                                        <form method="POST" action="">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $kat['id'] ?>">
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body py-4 text-center">
                                                <div class="p-3 rounded-circle bg-danger-subtle text-danger d-inline-block mb-3">
                                                    <i class="fa-solid fa-trash fs-2"></i>
                                                </div>
                                                <h5>Hapus Kategori Ini?</h5>
                                                <p class="text-muted small mb-0">Kategori <strong><?= htmlspecialchars($kat['nama_kategori']) ?></strong> akan dihapus permanen.</p>
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

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="modalTambahKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
            <form method="POST" action="">
                <input type="hidden" name="action" value="create">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-plus-circle text-primary me-2"></i>Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Pemrograman & AI" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Deskripsi Singkat</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Penjelasan mengenai cakupan topik dalam kategori ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-primary-custom">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
