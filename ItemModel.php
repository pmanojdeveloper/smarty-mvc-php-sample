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

    public function createItem($name, $description) {
        $query = "INSERT INTO items (name, description) VALUES (:name, :description)";
        $params = [':name' => $name, ':description' => $description];
        return $this->db->executeInsert($query, $params);
    }
    
    public function updateItem($id, $name, $description) {
        $query = "UPDATE items SET name = ?, description = ? WHERE id = ?";
        return $this->db->executeNonQuery($query, [$name, $description, $id]);
    }

    public function deleteItem($id) {
        $query = "DELETE FROM items WHERE id = ?";
        return $this->db->executeNonQuery($query, [$id]);
    }
}
