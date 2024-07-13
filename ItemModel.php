<?php
require_once 'databaseHandler.php';

class ItemModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllItems() {
        $query = "SELECT * FROM items";
        return $this->db->executeQuery($query);
    }

    public function getItemById($id) {
        $query = "SELECT * FROM items WHERE id = ?";
        return $this->db->executeSingleRow($query, [$id]);
    }

    public function createItem($name, $description, $file_path = null) {
        $query = "INSERT INTO items (name, description, file_path) VALUES (?, ?, ?)";
        $params = [$name, $description, $file_path];
        return $this->db->executeInsert($query, $params);
    }
    
    public function updateItem($id, $name, $description, $file_path = null) {
        $query = "UPDATE items SET name = ?, description = ?, file_path = ? WHERE id = ?";
        $params = [$name, $description, $file_path, $id];
        return $this->db->executeNonQuery($query, $params);
    }
    

    public function deleteItem($id) {
        $query = "DELETE FROM items WHERE id = ?";
        return $this->db->executeNonQuery($query, [$id]);
    }
}
?>
