<?php
/**
 * Class Database
 * Memenuhi Unit Kompetensi: J.620100.018.02 (OOP) & J.620100.021.02 (Akses Basis Data)
 */
class Database {
    private static $instance = null;
    private $host = "localhost";
    private $db_name = "db_perpustakaan";
    private $username = "root";
    private $password = "";
    private $conn;

    private function __construct() {
        try {
            // First connect without DB selected to ensure DB exists
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

            // Create DB if not exists and select it
            $this->conn->exec("CREATE DATABASE IF NOT EXISTS `" . $this->db_name . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->conn->exec("USE `" . $this->db_name . "`");

            // Auto-initialize tables & seeder if not present
            $this->initTables();

        } catch (PDOException $e) {
            die("Koneksi Database Gagal: " . $e->getMessage());
        }
    }

    private function initTables() {
        try {
            $check = $this->conn->query("SHOW TABLES LIKE 'users'");
            if ($check && $check->rowCount() === 0) {
                $schemaPath = __DIR__ . '/../database/schema.sql';
                if (file_exists($schemaPath)) {
                    $sql = file_get_contents($schemaPath);
                    $this->conn->exec($sql);
                } else {
                    $this->createTablesDirectly();
                }
            }
        } catch (Exception $e) {
            $this->createTablesDirectly();
        }
    }

    private function createTablesDirectly() {
        $queries = [
            "CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `username` VARCHAR(50) NOT NULL UNIQUE,
                `password` VARCHAR(255) NOT NULL,
                `nama_lengkap` VARCHAR(100) NOT NULL,
                `role` ENUM('admin', 'petugas') DEFAULT 'admin',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `kategori` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `nama_kategori` VARCHAR(100) NOT NULL,
                `deskripsi` TEXT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `buku` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `id_kategori` INT NOT NULL,
                `judul` VARCHAR(200) NOT NULL,
                `pengarang` VARCHAR(150) NOT NULL,
                `penerbit` VARCHAR(150) NOT NULL,
                `tahun_terbit` INT(4) NOT NULL,
                `isbn` VARCHAR(30) NULL,
                `stok` INT DEFAULT 1,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`id_kategori`) REFERENCES `kategori`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `peminjaman` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `id_buku` INT NOT NULL,
                `nama_peminjam` VARCHAR(100) NOT NULL,
                `tanggal_pinjam` DATE NOT NULL,
                `tanggal_kembali` DATE NOT NULL,
                `status` ENUM('dipinjam', 'dikembalikan') DEFAULT 'dipinjam',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`id_buku`) REFERENCES `buku`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`) VALUES
            (1, 'admin', '$2y$10$4y9pB2z8n.VzN0M4A9aQde3.x4K0H6y9aW2l1Q3m5P4o5R6s7T8u9', 'Administrator Utama', 'admin')
            ON DUPLICATE KEY UPDATE `username`=`username`;",

            "INSERT INTO `kategori` (`id`, `nama_kategori`, `deskripsi`) VALUES
            (1, 'Teknologi & Informasi', 'Buku-buku tentang komputer, jaringan, dan pemrograman'),
            (2, 'Sains & Matematika', 'Buku ilmu pengetahuan alam dan matematika'),
            (3, 'Fiksi & Novel', 'Koleksi cerita fiksi, novel, dan sastra'),
            (4, 'Ekonomi & Bisnis', 'Buku pengelolaan finansial dan kewirausahaan')
            ON DUPLICATE KEY UPDATE `nama_kategori`=`nama_kategori`;",

            "INSERT INTO `buku` (`id`, `id_kategori`, `judul`, `pengarang`, `penerbit`, `tahun_terbit`, `isbn`, `stok`) VALUES
            (1, 1, 'Clean Code: A Handbook of Agile Software Craftsmanship', 'Robert C. Martin', 'Prentice Hall', 2008, '978-0132350884', 5),
            (2, 1, 'Pemrograman Web dengan PHP & MySQL', 'Budi Raharjo', 'Informatika', 2021, '978-6026232231', 8),
            (3, 2, 'Fisika Dasar untuk Universitas', 'Paul A. Tipler', 'Erlangga', 2018, '978-9797812234', 3),
            (4, 3, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, '978-9793062792', 10)
            ON DUPLICATE KEY UPDATE `judul`=`judul`;",

            "INSERT INTO `peminjaman` (`id`, `id_buku`, `nama_peminjam`, `tanggal_pinjam`, `tanggal_kembali`, `status`) VALUES
            (1, 1, 'Ahmad Fauzi', '2026-07-20', '2026-07-27', 'dipinjam'),
            (2, 4, 'Siti Nurhaliza', '2026-07-15', '2026-07-22', 'dikembalikan')
            ON DUPLICATE KEY UPDATE `nama_peminjam`=`nama_peminjam`;"
        ];

        foreach ($queries as $q) {
            try {
                $this->conn->exec($q);
            } catch (Exception $ex) {
                // Continue if already exists
            }
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
