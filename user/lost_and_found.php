<?php
// Get filter and search parameters
$filterType = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$selectedCategories = isset($_GET['categories']) ? $_GET['categories'] : [];
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

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

// Calculate pagination
$itemsPerPage = 8;
$totalItems = count($allItems);
$totalPages = ceil($totalItems / $itemsPerPage);

// Get current page
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages));

// Get items for current page
$startIndex = ($currentPage - 1) * $itemsPerPage;
$currentItems = array_slice($allItems, $startIndex, $itemsPerPage);
?>
<?php require_once 'includes_user_side/sidebar.php';?>

<!-- Search Bar -->
<div class="search-container mb-4">
    <form method="GET" action="">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by item description..." 
                   value="<?php echo htmlspecialchars($searchTerm); ?>" aria-label="Search items">
            <!-- Preserve filter and category selections in hidden inputs -->
            <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filterType); ?>">
            <?php foreach($selectedCategories as $category): ?>
                <input type="hidden" name="categories[]" value="<?php echo htmlspecialchars($category); ?>">
            <?php endforeach; ?>
            <button class="btn btn-danger" type="submit">
                <i class="fas fa-search"></i> Search
            </button>
            <?php if(!empty($searchTerm)): ?>
                <a href="?filter=<?php echo htmlspecialchars($filterType); ?><?php 
                    echo !empty($selectedCategories) ? '&categories[]=' . implode('&categories[]=', array_map('htmlspecialchars', $selectedCategories)) : ''; 
                ?>" class="btn btn-secondary">
                    Clear Search
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>
<!-- Card View -->
<div class="row card-view">
    <?php 
    if (!empty($currentItems)) :
        foreach ($currentItems as $item) :
            $isLost = isset($item['date_lost']);
            $claimStatus = $claimInstance->fetchClaimStatus($item['item_id'], $isLost ? 'lost' : 'found');
    ?>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 style="color: <?= $isLost ? '#C7253E' : '#0d6efd' ?>"><?= $isLost ? 'Lost Item' : 'Found Item' ?></h4>
                    <h5 class="card-title"><?= htmlspecialchars($item['description']) ?></h5>
                    <p class="card-text">
                        <strong>Category:</strong> <?= htmlspecialchars($item['category_name']) ?><br>
                        <strong>Reported By:</strong> <?= htmlspecialchars($item['reporter_name']) ?><br>
                        <strong>Location:</strong> <?= htmlspecialchars($item['location']) ?><br>
                        <strong>Date <?= $isLost ? 'Lost' : 'Found' ?>:</strong> 
                        <?= htmlspecialchars($isLost ? $item['date_lost'] : $item['date_found']) ?>
                    </p>

                    <?php if ($item['image']): ?>
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="Item Image" class="img-fluid mb-2">
                    <?php else: ?>
                        <p>Image not available</p>
                    <?php endif; ?>

                    <?php if ($claimStatus): ?>
                        <p class="text-warning"><strong>Claim Status:</strong> <?= htmlspecialchars($claimStatus['status']) ?></p>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between">
                        <button class="btn btn-success btn-sm claim-btn" 
                            data-item-id="<?= htmlspecialchars($item['item_id']) ?>" 
                            data-item-type="<?= $isLost ? 'lost' : 'found' ?>">
                            Claim
                        </button>
                        <button class="btn <?= $isLost ? 'btn-danger' : 'btn-primary' ?> btn-sm details-btn" 
                            data-id="<?= $item['item_id'] ?>">
                            View Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php 
        endforeach;
    else: 
    ?>
        <p>No items available at the moment.</p>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<div class="d-flex justify-content-center mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <!-- Previous button -->
            <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $currentPage - 1 ?>&filter=<?= $filterType ?><?= !empty($selectedCategories) ? '&categories[]=' . implode('&categories[]=', $selectedCategories) : '' ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            
            <!-- Page numbers -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($currentPage == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&filter=<?= $filterType ?><?= !empty($selectedCategories) ? '&categories[]=' . implode('&categories[]=', $selectedCategories) : '' ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            
            <!-- Next button -->
            <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $currentPage + 1 ?>&filter=<?= $filterType ?><?= !empty($selectedCategories) ? '&categories[]=' . implode('&categories[]=', $selectedCategories) : '' ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>

<script>
// Handle radio button and checkbox changes
function updateURL() {
    const filterValue = $('input[name="filter"]:checked').val();
    const selectedCategories = $('input[name="categories[]"]:checked').map(function() {
        return this.value;
    }).get();
    const searchValue = $('input[name="search"]').val();
    
    let url = '?filter=' + filterValue;
    
    if (selectedCategories.length > 0) {
        url += '&' + selectedCategories.map(cat => 'categories[]=' + cat).join('&');
    }
    
    if (searchValue) {
        url += '&search=' + encodeURIComponent(searchValue);
    }
    
    window.location.href = url;
}

// Attach event handlers
$('input[name="filter"]').change(updateURL);
$('input[name="categories[]"]').change(updateURL);
</script>
