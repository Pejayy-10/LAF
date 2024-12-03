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

// Fetch all items and filter for current user
$lostItems = $item->fetchLostItems();
$foundItems = $item->fetchFoundItems();

// Filter items for current user
$userLostItems = array_filter($lostItems, function($item) use ($userId) {
    return $item['user_id'] == $userId;
});

$userFoundItems = array_filter($foundItems, function($item) use ($userId) {
    return $item['user_id'] == $userId;
});

// After the user filtering code
$allItems = array_merge($userLostItems, $userFoundItems);

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

    .btn {
        transition: all 0.3s ease;
        border: none;
        padding: 8px 16px;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
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
            <h2 class="text-center mb-4" style="color: #C7253E; font-weight: bold;">My Reported Items</h2>
            
            <section class="mb-5">
                <div class="row card-view">
                    <?php if (!empty($userLostItems) || !empty($userFoundItems)) : ?>
                        <?php 
                        // Combine and process both lost and found items
                        $allItems = [];
                        foreach ($userLostItems as $item) {
                            $item['type'] = 'lost';
                            $allItems[] = $item;
                        }
                        foreach ($userFoundItems as $item) {
                            $item['type'] = 'found';
                            $allItems[] = $item;
                        }
                        
                        foreach ($currentItems as $report) : 
                            $isLost = $report['type'] === 'lost';
                        ?>
                            <div class="col-md-4 mb-3">
                                <div class="card shadow-sm hover-effect">
                                    <?php if ($report['image']): ?>
                                        <img src="<?= htmlspecialchars($report['image']) ?>" 
                                             class="card-img-top" 
                                             alt="<?= $isLost ? 'Lost' : 'Found' ?> Item Image" 
                                             style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="text-center p-3 bg-light" style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                            <p class="mt-2 text-muted">No Image Available</p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body">
                                        <span class="badge mb-2" 
                                              style="background-color: <?= $isLost ? '#C7253E' : '#0d6efd' ?>;">
                                            <?= $isLost ? 'Lost Item' : 'Found Item' ?>
                                        </span>
                                        <h5 class="card-title mt-2"><?= htmlspecialchars($report['description']) ?></h5>
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
                                                <strong>Status:</strong> 
                                                <span class="badge bg-secondary">Pending</span>
                                            </p>
                                        </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <button class="btn btn-sm complete-report" 
                                                    data-id="<?= $report['item_id'] ?>" 
                                                    data-type="lost"
                                                    style="background-color: #198754; color: white;">
                                                Complete
                                            </button>
                                            <button class="btn btn-sm cancel-report" 
                                                    data-id="<?= $report['item_id'] ?>" 
                                                    data-type="lost"
                                                    style="background-color: #C7253E; color: white;">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-center">No lost item reports.</p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Found Items Reports -->
            <section>
                <h3 class="text-primary mb-3">Found Item Reports</h3>
                <div class="row">
                    <?php if (!empty($userFoundItems)) : ?>
                        <?php foreach ($userFoundItems as $report) : ?>
                            <div class="col-md-4 mb-3">
                                <div class="card shadow-sm">
                                    <?php if ($report['image']): ?>
                                        <img src="<?= htmlspecialchars($report['image']) ?>" class="card-img-top" alt="Found Item Image" style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="text-center p-3 bg-light" style="height: 200px;">No Image Available</div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body">
                                        <span class="badge bg-primary mb-2">Found Item</span>
                                        <h5><?= $report['item_id'] ?></h5>
                                        <div class="card-text">
                                            <p class="mb-1"><strong>Category:</strong> <?= htmlspecialchars($report['category_name']) ?></p>
                                            <p class="mb-1"><strong>Location:</strong> <?= htmlspecialchars($report['location']) ?></p>
                                            <p class="mb-1"><strong>Date Found:</strong> <?= htmlspecialchars($report['date_found']) ?></p>
                                            <p class="mb-1">
                                                <strong>Status:</strong> 
                                                <span class="badge bg-secondary">Pending</span>
                                            </p>
                                        </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <button class="btn btn-success btn-sm complete-report" 
                                                    data-id="<?= $report['item_id'] ?>" 
                                                    data-type="found">
                                                Complete
                                            </button>
                                            <button class="btn btn-danger btn-sm cancel-report" 
                                                    data-id="<?= $report['item_id'] ?>" 
                                                    data-type="found">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-center">No found item reports.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle Complete Report
    $('.complete-report').click(function() {
        const itemId = $(this).data('id');
        const itemType = $(this).data('type');
        
        if (confirm('Are you sure you want to mark this report as complete?')) {
            $.ajax({
                url: 'processes/update_report_status.php',
                type: 'POST',
                data: {
                    item_id: itemId,
                    item_type: itemType,
                    status: 'completed'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Report marked as complete!');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while updating the report.');
                }
            });
        }
    });

    // Handle Cancel Report
    $('.cancel-report').click(function() {
        const itemId = $(this).data('id');
        const itemType = $(this).data('type');
        
        if (confirm('Are you sure you want to cancel this report? This action cannot be undone.')) {
            $.ajax({
                url: 'processes/update_report_status.php',
                type: 'POST',
                data: {
                    item_id: itemId,
                    item_type: itemType,
                    status: 'cancelled'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Report cancelled successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while cancelling the report.');
                }
            });
        }
    });
});
</script>