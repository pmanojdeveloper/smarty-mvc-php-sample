<?php
// Replace these values with your database connection details
$host = "localhost";
$dbname = "test";
$username = "root";
$password = "";

// Create an instance of the DatabaseHandler class
$database = new DatabaseHandler($host, $dbname, $username, $password);

// Example 1: Execute a SELECT query
$query = "SELECT * FROM your_table WHERE id = ?";
$params = [1];
$result = $database->executeQuery($query, $params);
print_r($result);

// Example 2: Execute an INSERT query
$insertQuery = "INSERT INTO your_table (column1, column2) VALUES (?, ?)";
$insertParams = ['value1', 'value2'];
$affectedRows = $database->executeNonQuery($insertQuery, $insertParams);
echo "Inserted $affectedRows rows.";

// Example 3: Execute a SELECT query for a single row
$singleRowQuery = "SELECT * FROM your_table WHERE id = ?";
$singleRowParams = [1];
$row = $database->executeSingleRow($singleRowQuery, $singleRowParams);
print_r($row);

// Example 4: Start, commit, and rollback a transaction
try {
    $database->startTransaction();

    // Perform multiple queries here

    $database->commitTransaction();
} catch (Exception $e) {
    $database->rollbackTransaction();
    echo "Transaction failed: " . $e->getMessage();
}
// Example 5: Execute an INSERT query
$insertQuery = "INSERT INTO your_table (column1, column2) VALUES (?, ?)";
$insertParams = ['value1', 'value2'];
$insertedId = $database->executeInsert($insertQuery, $insertParams);
echo "Inserted with ID: $insertedId";

// Example 6: Execute an UPDATE query
$updateQuery = "UPDATE your_table SET column1 = ? WHERE id = ?";
$updateParams = ['new_value', 1];
$affectedRows = $database->executeUpdate($updateQuery, $updateParams);
echo "Updated $affectedRows rows.";

// Example 7: Execute a DELETE query
$deleteQuery = "DELETE FROM your_table WHERE id = ?";
$deleteParams = [1];
$deletedRows = $database->executeDelete($deleteQuery, $deleteParams);
echo "Deleted $deletedRows rows.";


// Example 8: Execute a CREATE TABLE query
$createTableQuery = "
    CREATE TABLE your_new_table (
        id INT PRIMARY KEY,
        name VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
";
$successCreateTable = $database->executeCreateTable($createTableQuery);
if ($successCreateTable) {
    echo "Table created successfully";
} else {
    echo "Failed to create table";
}

// Example 9: Execute an ALTER TABLE query
$alterTableQuery = "ALTER TABLE your_table ADD COLUMN new_column INT";
$successAlterTable = $database->executeAlterTable($alterTableQuery);
if ($successAlterTable) {
    echo "Table altered successfully";
} else {
    echo "Failed to alter table";
}

// Example 10: Execute a DROP TABLE query
$dropTableQuery = "DROP TABLE your_table";
$successDropTable = $database->executeDropTable($dropTableQuery);
if ($successDropTable) {
    echo "Table dropped successfully";
} else {
    echo "Failed to drop table";
}

// Example 11: Execute a SELECT query for a single value
$singleValueQuery = "SELECT name FROM your_table WHERE id = ?";
$singleValueParams = [1];
$name = $database->executeSingleValue($singleValueQuery, $singleValueParams);
echo "Name: $name";

// Example 12: Execute a SELECT query for a single row by ID
$userId = 1;
$userRow = $database->executeSingleRowById('users', $userId);
print_r($userRow);
