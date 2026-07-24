<?php
/**
 * Class Kategori
 * Memenuhi Unit Kompetensi: J.620100.017.02 & J.620100.018.02 & J.620100.021.02
 */
require_once __DIR__ . '/Database.php';

class Kategori {
    private $db;
    private $table = "kategori";

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($nama_kategori, $deskripsi) {
        $query = "INSERT INTO " . $this->table . " (nama_kategori, deskripsi) VALUES (:nama_kategori, :deskripsi)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nama_kategori', $nama_kategori);
        $stmt->bindParam(':deskripsi', $deskripsi);
        return $stmt->execute();
    }

    public function update($id, $nama_kategori, $deskripsi) {
        $query = "UPDATE " . $this->table . " SET nama_kategori = :nama_kategori, deskripsi = :deskripsi WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama_kategori', $nama_kategori);
        $stmt->bindParam(':deskripsi', $deskripsi);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }
}
