<?php
require_once('libs/Smarty.class.php');
require_once('databaseHandler.php');

$smarty = new Smarty;
$smarty->setTemplateDir('views/templates');
$smarty->setCompileDir('views/templates_c');
$smarty->setCacheDir('views/cache');

try {
    $host = 'localhost';
    $dbname = 'test';
    $username = 'root';
    $password = '';

    $db = new DatabaseHandler($host, $dbname, $username, $password);
    
    $db->executeNonQuery("
        CREATE TABLE IF NOT EXISTS items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT
        )
    ");
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}

return ['smarty' => $smarty, 'db' => $db];
