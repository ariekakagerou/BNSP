<?php
/**
 * Class Peminjaman
 * Memenuhi Unit Kompetensi: J.620100.017.02 & J.620100.018.02 & J.620100.021.02
 */
require_once __DIR__ . '/Database.php';

class Peminjaman {
    private $db;
    private $table = "peminjaman";

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $query = "SELECT p.*, b.judul as judul_buku, b.pengarang 
                  FROM " . $this->table . " p 
                  LEFT JOIN buku b ON p.id_buku = b.id 
                  ORDER BY p.id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (id_buku, nama_peminjam, tanggal_pinjam, tanggal_kembali, status) 
                  VALUES (:id_buku, :nama_peminjam, :tanggal_pinjam, :tanggal_kembali, 'dipinjam')";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_buku', $data['id_buku']);
        $stmt->bindParam(':nama_peminjam', $data['nama_peminjam']);
        $stmt->bindParam(':tanggal_pinjam', $data['tanggal_pinjam']);
        $stmt->bindParam(':tanggal_kembali', $data['tanggal_kembali']);

        if ($stmt->execute()) {
            // Decrease book stock by 1
            $update_stok = "UPDATE buku SET stok = GREATEST(0, stok - 1) WHERE id = :id_buku";
            $stmt_stok = $this->db->prepare($update_stok);
            $stmt_stok->bindParam(':id_buku', $data['id_buku']);
            $stmt_stok->execute();
            return true;
        }
        return false;
    }

    public function returnBook($id, $id_buku) {
        $query = "UPDATE " . $this->table . " SET status = 'dikembalikan' WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            // Increase book stock by 1
            $update_stok = "UPDATE buku SET stok = stok + 1 WHERE id = :id_buku";
            $stmt_stok = $this->db->prepare($update_stok);
            $stmt_stok->bindParam(':id_buku', $id_buku);
            $stmt_stok->execute();
            return true;
        }
        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function countActive() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE status = 'dipinjam'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }

    public function countTotal() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }
}
