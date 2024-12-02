<?php
require_once 'classes/Database.class.php';

$db = new Database();
$conn = $db->connect();

echo "<h3>Lost Items Raw Data:</h3>";
$sql = "SELECT l.*, c.category_name, c.category_id 
        FROM lost_items l 
        LEFT JOIN categories c ON l.category = c.category_id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$lostItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Lost Items Query: " . $sql . "<br><br>";
echo "Lost Items Data:<br>";
echo "<pre>";
print_r($lostItems);
echo "</pre>";

echo "<h3>Found Items Raw Data:</h3>";
$sql = "SELECT f.*, c.category_name, c.category_id 
        FROM found_items f 
        LEFT JOIN categories c ON f.category = c.category_id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$foundItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Found Items Query: " . $sql . "<br><br>";
echo "Found Items Data:<br>";
echo "<pre>";
print_r($foundItems);
echo "</pre>";

echo "<h3>Categories Table:</h3>";
$sql = "SELECT * FROM categories";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Categories Data:<br>";
echo "<pre>";
print_r($categories);
echo "</pre>";

// Show a sample lost item with its raw category value
echo "<h3>Sample Lost Item Category Value:</h3>";
$sql = "SELECT item_id, category FROM lost_items LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$sampleItem = $stmt->fetch(PDO::FETCH_ASSOC);
