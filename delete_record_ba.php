<?php
// Database connection
$host = 'localhost'; 
$dbname = 'municipal_docs'; // change to your database name
$username = 'root'; // default XAMPP user
$password = ''; // default XAMPP password is empty

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // prevent SQL injection

    // Prepare delete query
    $stmt = $conn->prepare("DELETE FROM building_applications WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record.";
    }
} else {
    echo "No record ID provided.";
}
?>
