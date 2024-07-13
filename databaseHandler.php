<?php
class DatabaseHandler {
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $pdo;

    public function __construct($host, $dbname, $username, $password) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;

        $this->connect();
    }

    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function executeQuery($query, $params = []) {
        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute($params);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    public function executeNonQuery($query, $params = []) {
        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute($params);
            return $statement->rowCount();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    public function executeSingleRow($query, $params = []) {
        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute($params);
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }
    public function executeInsert($query, $params = []) {
        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute($params);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    public function executeUpdate($query, $params = []) {
        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute($params);
            return $statement->rowCount();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    public function executeDelete($query, $params = []) {
        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute($params);
            return $statement->rowCount();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }
    public function executeSingleValue($query, $params = []) {
        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute($params);
            return $statement->fetchColumn();
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    public function executeSingleRowById($table, $id) {
        $query = "SELECT * FROM $table WHERE id = ?";
        $params = [$id];
        return $this->executeSingleRow($query, $params);
    }
    public function startTransaction() {
        $this->pdo->beginTransaction();
    }

    public function commitTransaction() {
        $this->pdo->commit();
    }

    public function rollbackTransaction() {
        $this->pdo->rollBack();
    }
    public function executeCreateTable($query) {
        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute();
            return true;
        } catch (PDOException $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function executeAlterTable($query) {
        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute();
            return true;
        } catch (PDOException $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function executeDropTable($query) {
        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute();
            return true;
        } catch (PDOException $e) {
            $this->handleError($e);
            return false;
        }
    }
    private function handleError($exception) {
        // Handle errors here, such as logging or displaying an error message
        die("Query failed: " . $exception->getMessage());
    }
}
