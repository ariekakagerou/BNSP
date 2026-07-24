<?php
/**
 * Class Buku
 * Memenuhi Unit Kompetensi: J.620100.017.02 & J.620100.018.02 & J.620100.021.02
 */
require_once __DIR__ . '/Database.php';

class Buku {
    private $db;
    private $table = "buku";

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($search = '', $kategori_id = '') {
        $query = "SELECT b.*, k.nama_kategori 
                  FROM " . $this->table . " b 
                  LEFT JOIN kategori k ON b.id_kategori = k.id 
                  WHERE 1=1";

        if (!empty($search)) {
            $query .= " AND (b.judul LIKE :search OR b.pengarang LIKE :search OR b.isbn LIKE :search)";
        }
        if (!empty($kategori_id)) {
            $query .= " AND b.id_kategori = :kategori_id";
        }

        $query .= " ORDER BY b.id DESC";

        $stmt = $this->db->prepare($query);

        if (!empty($search)) {
            $search_param = "%" . $search . "%";
            $stmt->bindParam(':search', $search_param);
        }
        if (!empty($kategori_id)) {
            $stmt->bindParam(':kategori_id', $kategori_id);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT b.*, k.nama_kategori 
                  FROM " . $this->table . " b 
                  LEFT JOIN kategori k ON b.id_kategori = k.id 
                  WHERE b.id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (id_kategori, judul, pengarang, penerbit, tahun_terbit, isbn, stok) 
                  VALUES (:id_kategori, :judul, :pengarang, :penerbit, :tahun_terbit, :isbn, :stok)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_kategori', $data['id_kategori']);
        $stmt->bindParam(':judul', $data['judul']);
        $stmt->bindParam(':pengarang', $data['pengarang']);
        $stmt->bindParam(':penerbit', $data['penerbit']);
        $stmt->bindParam(':tahun_terbit', $data['tahun_terbit']);
        $stmt->bindParam(':isbn', $data['isbn']);
        $stmt->bindParam(':stok', $data['stok']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET id_kategori = :id_kategori, judul = :judul, pengarang = :pengarang, 
                      penerbit = :penerbit, tahun_terbit = :tahun_terbit, isbn = :isbn, stok = :stok 
                  WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':id_kategori', $data['id_kategori']);
        $stmt->bindParam(':judul', $data['judul']);
        $stmt->bindParam(':pengarang', $data['pengarang']);
        $stmt->bindParam(':penerbit', $data['penerbit']);
        $stmt->bindParam(':tahun_terbit', $data['tahun_terbit']);
        $stmt->bindParam(':isbn', $data['isbn']);
        $stmt->bindParam(':stok', $data['stok']);
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
