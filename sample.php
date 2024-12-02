<?php
// Database connection
$host = 'localhost'; // Your database host
$dbname = 'lost_and_found'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Check if the search term is set
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    
    // Prepare the SQL query using LIKE to search in descriptions
    $stmt = $pdo->prepare("SELECT * FROM reports WHERE description LIKE :searchTerm");
    $stmt->execute(['searchTerm' => '%' . $searchTerm . '%']);
    
    // Fetch the results
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if any results were found
    if (count($reports) > 0) {
        // Loop through the results and display them
        foreach ($reports as $report) {
            echo '<div class="result-item">';
            echo '<h4>Report ID: ' . $report['id'] . '</h4>';
            echo '<p>Description: ' . htmlspecialchars($report['description']) . '</p>';
            echo '</div>';
        }
    } else {
        // If no results were found, display a message
        echo '<p>No reports found matching your search.</p>';
    }
}
?>
