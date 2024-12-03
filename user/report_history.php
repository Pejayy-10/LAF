<?php
require_once 'classes/Item.class.php';
require_once 'classes/Claim.class.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: account/login.php');
    exit;
}

$item = new Item();
$claim = new Claim();
$userId = $_SESSION['user_id'];

// Sample data for demonstration
$completedItems = [
    [
        'item_id' => 1,
        'description' => 'sample',
        'category_name' => 'sample',
        'location' => 'sample',
        'date_found' => '2024-03-15',
        'completed_date' => '2024-03-20',
        'image' => 'uploads/sample-bag.jpg',
        'type' => 'found',
        'reporter_name' => 'sample'
    ],
    [
        'item_id' => 2,
        'description' => 'sample',
        'category_name' => 'sample',
        'location' => 'sample',
        'date_lost' => '2024-03-10',
        'completed_date' => '2024-03-18',
        'image' => 'uploads/sample-watch.jpg',
        'type' => 'lost',
        'reporter_name' => 'sample'
    ]
];

$cancelledItems = [
    [
        'item_id' => 3,
        'description' => 'sample',
        'category_name' => 'sample',
        'location' => 'sample',
        'date_lost' => '2024-03-12',
        'cancelled_date' => '2024-03-14',
        'image' => 'uploads/sample-wallet.jpg',
        'type' => 'lost',
        'reporter_name' => 'sample'
    ],
    [
        'item_id' => 4,
        'description' => 'sample',
        'category_name' => 'sample',
        'location' => 'sample',
        'date_found' => '2024-03-08',
        'cancelled_date' => '2024-03-09',
        'image' => null,
        'type' => 'found',
        'reporter_name' => 'sample'
    ]
];

// Calculate pagination
$itemsPerPage = 8;
$totalItems = count($completedItems) + count($cancelledItems);
$totalPages = ceil($totalItems / $itemsPerPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages));
?>

<style>
    .hover-effect {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }

    .card {
        border: none;
        border-radius: 8px;
        overflow: hidden;
    }

    .badge {
        padding: 8px 12px;
        font-weight: 500;
    }

    .card-text {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .card-text strong {
        color: #212529;
    }
</style>

<div class="container-fluid mt-4 px-4">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-4" style="color: #C7253E; font-weight: bold;">Report History</h2>
            
            <!-- Completed Items Section -->
            <section class="mb-5">
                <h3 class="text-success mb-3">Completed Reports</h3>
                <div class="row">
                    <?php if (!empty($completedItems)) : ?>
                        <?php foreach ($completedItems as $report) : 
                            $isLost = $report['type'] === 'lost';
                        ?>
                            <div class="col-md-4 mb-3">
                                <div class="card shadow-sm hover-effect">
                                    <?php if ($report['image']): ?>
                                        <img src="<?= htmlspecialchars($report['image']) ?>" 
                                             class="card-img-top" 
                                             alt="Item Image" 
                                             style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="text-center p-3 bg-light" style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                            <p class="mt-2 text-muted">No Image Available</p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge" 
                                                  style="background-color: <?= $isLost ? '#C7253E' : '#0d6efd' ?>;">
                                                <?= $isLost ? 'Lost Item' : 'Found Item' ?>
                                            </span>
                                            <span class="badge bg-success">Completed</span>
                                        </div>
                                        <h5 class="card-title"><?= htmlspecialchars($report['description']) ?></h5>
                                        <div class="card-text">
                                            <p class="mb-1">
                                                <strong>Category:</strong> 
                                                <?= htmlspecialchars($report['category_name']) ?>
                                            </p>
                                            <p class="mb-1">
                                                <strong>Location:</strong> 
                                                <?= htmlspecialchars($report['location']) ?>
                                            </p>
                                            <p class="mb-1">
                                                <strong>Date <?= $isLost ? 'Lost' : 'Found' ?>:</strong> 
                                                <?= htmlspecialchars($isLost ? $report['date_lost'] : $report['date_found']) ?>
                                            </p>
                                            <p class="mb-1">
                                                <strong>Completed Date:</strong> 
                                                <?= htmlspecialchars($report['completed_date']) ?>
                                            </p>
                                            <p class="mb-1">
                                                <strong>Reported By:</strong> 
                                                <?= htmlspecialchars($report['reporter_name']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-center">No completed reports found.</p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Cancelled Items Section -->
            <section class="mb-5">
                <h3 class="text-danger mb-3">Cancelled Reports</h3>
                <div class="row">
                    <?php if (!empty($cancelledItems)) : ?>
                        <?php foreach ($cancelledItems as $report) : 
                            $isLost = $report['type'] === 'lost';
                        ?>
                            <div class="col-md-4 mb-3">
                                <div class="card shadow-sm hover-effect">
                                    <?php if ($report['image']): ?>
                                        <img src="<?= htmlspecialchars($report['image']) ?>" 
                                             class="card-img-top" 
                                             alt="Item Image" 
                                             style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="text-center p-3 bg-light" style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                            <p class="mt-2 text-muted">No Image Available</p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge" 
                                                  style="background-color: <?= $isLost ? '#C7253E' : '#0d6efd' ?>;">
                                                <?= $isLost ? 'Lost Item' : 'Found Item' ?>
                                            </span>
                                            <span class="badge bg-danger">Cancelled</span>
                                        </div>
                                        <h5 class="card-title"><?= htmlspecialchars($report['description']) ?></h5>
                                        <div class="card-text">
                                            <p class="mb-1">
                                                <strong>Category:</strong> 
                                                <?= htmlspecialchars($report['category_name']) ?>
                                            </p>
                                            <p class="mb-1">
                                                <strong>Location:</strong> 
                                                <?= htmlspecialchars($report['location']) ?>
                                            </p>
                                            <p class="mb-1">
                                                <strong>Date <?= $isLost ? 'Lost' : 'Found' ?>:</strong> 
                                                <?= htmlspecialchars($isLost ? $report['date_lost'] : $report['date_found']) ?>
                                            </p>
                                            <p class="mb-1">
                                                <strong>Cancelled Date:</strong> 
                                                <?= htmlspecialchars($report['cancelled_date']) ?>
                                            </p>
                                            <p class="mb-1">
                                                <strong>Reported By:</strong> 
                                                <?= htmlspecialchars($report['reporter_name']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-center">No cancelled reports found.</p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($currentPage == $i) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $currentPage + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div> 