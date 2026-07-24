<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth();
$error = "";

if (Auth::check()) {
    header("Location: " . getBaseUrl() . "/Dashboard/dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Username dan Password wajib diisi!";
    } else {
        if ($auth->login($username, $password)) {
            header("Location: " . getBaseUrl() . "/Dashboard/dashboard.php");
            exit();
        } else {
            $error = "Username atau Password salah! Gunakan akun demo di bawah.";
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container my-auto py-5">
    <div class="row justify-content-center align-items-center g-4">
        <!-- Left Side: Hero Info -->
        <div class="col-lg-5 text-lg-start text-center me-lg-4">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-indigo-50 text-indigo-700 fw-bold small mb-3 border border-indigo-200" style="background: #e0e7ff; color: #3730a3; border-color: #c7d2fe;">
                <i class="fa-solid fa-certificate text-primary"></i> Sertifikasi BNSP Associate Programmer
            </div>
            <h1 class="fw-extrabold display-5 text-dark mb-3" style="font-weight: 800; letter-spacing: -1px;">
                Sistem Perpustakaan <span style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Digital</span>
            </h1>
            <p class="text-secondary leading-relaxed mb-4" style="font-size: 1.05rem;">
                Aplikasi Manajemen Koleksi Buku & Transaksi Peminjaman yang dirancang sesuai standar kriteria Uji Kompetensi **FR.IA.02 Tugas Praktik Demonstrasi**.
            </p>

            <div class="d-none d-lg-block">
                <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 bg-white border shadow-sm">
                    <div class="p-2.5 rounded-3 bg-primary text-white"><i class="fa-solid fa-layer-group fs-5"></i></div>
                    <div>
                        <div class="fw-bold text-dark">5 Halaman Interaktif</div>
                        <div class="small text-muted">Dashboard, Data Buku, Kategori, Peminjaman, & PDF Report</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-white border shadow-sm">
                    <div class="p-2.5 rounded-3 bg-emerald-600 text-white" style="background: #10b981;"><i class="fa-solid fa-code fs-5"></i></div>
                    <div>
                        <div class="fw-bold text-dark">Arsitektur OOP & PDO MySQL</div>
                        <div class="small text-muted">Struktur kode terenkapsulasi dengan kelas modular yang bersih</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Card -->
        <div class="col-lg-4 col-md-8">
            <div class="card card-custom p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-indigo-600 text-white rounded-4 mb-3 shadow-lg" style="width: 58px; height: 58px; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);">
                        <i class="fa-solid fa-user-lock fs-4"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Masuk ke Sistem</h4>
                    <p class="text-muted small">Masukkan kredensial Anda untuk melanjutkan</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show small rounded-3" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-1.5"></i> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-user"></i></span>
                            <input type="text" id="inputUsername" name="username" class="form-control border-start-0 ps-0" placeholder="Masukkan username" value="admin" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" id="inputPassword" name="password" class="form-control border-start-0 border-end-0 ps-0" placeholder="Masukkan password" value="admin123" required>
                            <button type="button" class="input-group-text bg-light border-start-0 text-muted" onclick="togglePasswordVisibility()">
                                <i class="fa-solid fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-primary-custom w-100 py-2.5 fw-bold text-uppercase tracking-wider" style="letter-spacing: 0.5px;">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk Sekarang
                    </button>
                </form>

                <!-- Demo Auto Fill Box -->
                <div class="mt-4 pt-3 border-top text-center">
                    <div class="small text-muted mb-2">Akses Cepat Pengujian Asesor:</div>
                    <button type="button" onclick="fillAdminCredentials()" class="btn btn-sm btn-light border text-indigo-700 fw-semibold rounded-pill px-3 py-1">
                        <i class="fa-solid fa-bolt text-warning me-1"></i> Auto-Fill (admin / admin123)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const input = document.getElementById('inputPassword');
    const icon = document.getElementById('toggleIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function fillAdminCredentials() {
    document.getElementById('inputUsername').value = 'admin';
    document.getElementById('inputPassword').value = 'admin123';
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
