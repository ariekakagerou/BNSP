<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Auth.php';

$currentPage = basename($_SERVER['PHP_SELF']);
if (!Auth::check() && $currentPage != 'login.php') {
    header("Location: " . getBaseUrl() . "/Auth/login.php");
    exit();
}

$currentUser = Auth::user();
$baseUrl = getBaseUrl();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perpustakaan Digital - BNSP</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts Inter & Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            --accent-gradient: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            --card-border-radius: 18px;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Glassmorphism Navbar */
        .navbar-custom {
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.5px;
            color: #ffffff !important;
        }

        .brand-icon {
            background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .nav-link {
            color: #94a3b8 !important;
            font-weight: 600;
            font-size: 0.92rem;
            padding: 0.55rem 1.1rem !important;
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-1px);
        }

        .nav-link.active {
            color: #ffffff !important;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.3) 0%, rgba(79, 70, 229, 0.5) 100%);
            border: 1px solid rgba(129, 140, 248, 0.3);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        /* Modern UI Cards */
        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: var(--card-border-radius);
            box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.04), 0 4px 6px -2px rgba(15, 23, 42, 0.02);
            background: #ffffff;
            transition: all 0.25s ease;
        }

        .card-custom:hover {
            box-shadow: 0 20px 35px -10px rgba(15, 23, 42, 0.08);
        }

        /* Metric Cards */
        .stat-card {
            border-radius: var(--card-border-radius);
            padding: 1.6rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-card .icon-bg {
            position: absolute;
            right: 15px;
            bottom: 10px;
            font-size: 4.5rem;
            opacity: 0.18;
            transition: transform 0.3s ease;
        }

        .stat-card:hover .icon-bg {
            transform: scale(1.15) rotate(-5deg);
        }

        .bg-gradient-indigo { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); }
        .bg-gradient-emerald { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
        .bg-gradient-amber { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); }
        .bg-gradient-rose { background: linear-gradient(135deg, #e11d48 0%, #be123c 100%); }

        /* Tables & Typography */
        .table-custom {
            margin-bottom: 0;
        }

        .table-custom th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 1rem 1.25rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-custom td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
        }

        .btn-primary-custom {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            padding: 0.65rem 1.35rem;
            color: #fff;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
            transition: all 0.25s ease;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #4338ca 0%, #312e81 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
            color: #fff;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            color: #fff;
            font-weight: 700;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
        }

        .badge-status {
            padding: 0.4rem 0.85rem;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.78rem;
        }

        footer {
            margin-top: auto;
            background: #0f172a;
            color: #94a3b8;
            padding: 1.75rem 0;
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body>

<?php if (Auth::check()): ?>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top py-2">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= $baseUrl ?>/Dashboard/dashboard.php">
            <span class="brand-icon">
                <i class="fa-solid fa-book-bookmark text-white"></i>
            </span>
            <span>Library<span class="text-indigo-400" style="color: #818cf8;">Pro</span></span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-1">
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>" href="<?= $baseUrl ?>/Dashboard/dashboard.php">
                        <i class="fa-solid fa-chart-pie me-1.5 opacity-75"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'buku.php') ? 'active' : '' ?>" href="<?= $baseUrl ?>/Buku/buku.php">
                        <i class="fa-solid fa-book me-1.5 opacity-75"></i> Data Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'kategori.php') ? 'active' : '' ?>" href="<?= $baseUrl ?>/Kategori/kategori.php">
                        <i class="fa-solid fa-tags me-1.5 opacity-75"></i> Kategori Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'peminjaman.php') ? 'active' : '' ?>" href="<?= $baseUrl ?>/Peminjaman/peminjaman.php">
                        <i class="fa-solid fa-right-left me-1.5 opacity-75"></i> Peminjaman
                    </a>
                </li>
                <li class="nav-item d-lg-none">
                    <a class="nav-link" href="<?= $baseUrl ?>/Laporan/dokumentasi_pdf.php" target="_blank">
                        <i class="fa-solid fa-file-signature me-1.5"></i> Dokumentasi BNSP
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3 pt-2 pt-lg-0">
                <a href="<?= $baseUrl ?>/Laporan/dokumentasi_pdf.php" target="_blank" class="btn btn-outline-light btn-sm rounded-3 px-3 d-none d-lg-inline-flex align-items-center gap-1.5" style="border-color: rgba(255,255,255,0.2);">
                    <i class="fa-solid fa-file-signature text-amber-400" style="color: #fbbf24;"></i>
                    <span>Dokumentasi BNSP</span>
                </a>
                <div class="d-flex align-items-center gap-2 text-light ps-lg-2 border-lg-start border-secondary">
                    <div class="user-avatar">
                        <?= strtoupper(substr($currentUser['nama_lengkap'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="text-start d-none d-md-block">
                        <div class="fw-bold small leading-tight" style="line-height: 1.2;"><?= htmlspecialchars($currentUser['nama_lengkap'] ?? 'User') ?></div>
                        <span class="badge bg-indigo-500 text-uppercase" style="font-size: 0.6rem; background: #6366f1; letter-spacing: 0.5px;"><?= htmlspecialchars($currentUser['role'] ?? 'admin') ?></span>
                    </div>
                </div>
                <a href="<?= $baseUrl ?>/Auth/logout.php" class="btn btn-outline-danger btn-sm rounded-3 px-3 ms-2" title="Keluar dari Sistem">
                    <i class="fa-solid fa-power-off"></i>
                </a>
            </div>
        </div>
    </div>
</nav>
<?php endif; ?>
