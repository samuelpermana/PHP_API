<?php

// Connect to your database
$host = 'localhost';
$dbName = 'contact_list';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Create a contact
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $numberPhone = $_POST['numberPhone'];
    $address = $_POST['address'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO contacts (first_Name, last_Name, phone_number, address) VALUES (:firstName, :lastName, :numberPhone, :address)");
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':numberPhone', $numberPhone);
        $stmt->bindParam(':address', $address);
        $stmt->execute();
        echo "Contact created successfully!";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Retrieve all contacts
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM contacts");
        $stmt->execute();
        $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($contacts);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Update a contact
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $putData);
    $contactId = $putData['contactId'];
    $firstName = $putData['firstName'];
    $lastName = $putData['lastName'];
    $numberPhone = $putData['numberPhone'];
    $address = $putData['address'];
    
    try {
        $stmt = $conn->prepare("UPDATE contacts SET first_Name = :firstName, last_Name = :lastName, phone_Number = :numberPhone, address = :address WHERE id = :contactId");
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':numberPhone', $numberPhone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':contactId', $contactId);
        $stmt->execute();
        echo "Contact updated successfully!";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Delete a contact
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $deleteData = $_GET; 
    $contactId = $deleteData['contactId'];
    
    try {
        $stmt = $conn->prepare("DELETE FROM contacts WHERE id = :contactId");
        $stmt->bindParam(':contactId', $contactId);
        $stmt->execute();
        echo "Contact deleted successfully!";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


?>
