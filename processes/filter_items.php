<?php
require_once '../classes/Item.class.php';
require_once '../classes/Claim.class.php';

$item = new Item();
$claimInstance = new Claim();

// Get filter parameters
$filterType = isset($_POST['filter']) ? $_POST['filter'] : 'all';
$selectedCategories = isset($_POST['categories']) ? $_POST['categories'] : [];
$searchTerm = isset($_POST['search']) ? trim($_POST['search']) : '';
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;

// Initialize arrays
$allItems = [];

// Fetch and filter items
if ($filterType === 'all' || $filterType === 'lost') {
    $lostItems = $item->fetchLostItems($selectedCategories);
    if (!empty($searchTerm)) {
        $lostItems = array_filter($lostItems, function($item) use ($searchTerm) {
            return stripos($item['description'], $searchTerm) !== false;
        });
    }
    $allItems = array_merge($allItems, $lostItems);
}

if ($filterType === 'all' || $filterType === 'found') {
    $foundItems = $item->fetchFoundItems($selectedCategories);
    if (!empty($searchTerm)) {
        $foundItems = array_filter($foundItems, function($item) use ($searchTerm) {
            return stripos($item['description'], $searchTerm) !== false;
        });
    }
    $allItems = array_merge($allItems, $foundItems);
}

// Pagination
$itemsPerPage = 8;
$totalItems = count($allItems);
$totalPages = ceil($totalItems / $itemsPerPage);
$page = max(1, min($page, $totalPages));
$startIndex = ($page - 1) * $itemsPerPage;
$currentItems = array_slice($allItems, $startIndex, $itemsPerPage);

// Start output buffering for items HTML
ob_start();
require '../templates/items_template.php';
$itemsHtml = ob_get_clean();

// Start output buffering for pagination HTML
ob_start();
require '../templates/pagination_template.php';
$paginationHtml = ob_get_clean();

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'items' => $itemsHtml,
    'pagination' => $paginationHtml
]);
?>