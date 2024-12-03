<?php
session_start();
require_once '../classes/Item.class.php';

if (!isset($_GET['item_id']) || !isset($_GET['type'])) {
    http_response_code(400);
    exit('Item ID and type are required');
}

$item = new Item();
$itemDetails = null;

if ($_GET['type'] === 'lost') {
    $itemDetails = $item->fetchLostItemDetails($_GET['item_id']);
} else {
    $itemDetails = $item->fetchFoundItemDetails($_GET['item_id']);
}

if (!$itemDetails) {
    http_response_code(404);
    exit('Item not found');
}

$isLost = $_GET['type'] === 'lost';
?>

<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header <?= $isLost ? 'bg-danger' : 'bg-primary' ?> text-white">
                <h5 class="modal-title" id="viewDetailsModalLabel">
                    <i class="fas fa-<?= $isLost ? 'search' : 'box' ?> me-2"></i>
                    <?= $isLost ? 'Lost' : 'Found' ?> Item Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($itemDetails['image'])): ?>
                    <div class="text-center mb-4">
                        <img src="<?= htmlspecialchars($itemDetails['image']) ?>" 
                             class="img-fluid rounded shadow-sm" 
                             alt="Item Image" 
                             style="max-height: 300px; object-fit: contain;">
                    </div>
                <?php endif; ?>
                
                <div class="details-container p-3 bg-light rounded">
                    <div class="detail-item mb-3">
                        <label class="text-muted mb-1">Description</label>
                        <p class="fw-bold mb-2"><?= htmlspecialchars($itemDetails['description']) ?></p>
                    </div>
                    
                    <div class="detail-item mb-3">
                        <label class="text-muted mb-1">Category</label>
                        <p class="fw-bold mb-2"><?= htmlspecialchars($itemDetails['category_name']) ?></p>
                    </div>
                    
                    <div class="detail-item mb-3">
                        <label class="text-muted mb-1">Location</label>
                        <p class="fw-bold mb-2"><?= htmlspecialchars($itemDetails['location']) ?></p>
                    </div>
                    
                    <div class="detail-item mb-3">
                        <label class="text-muted mb-1">Date <?= $isLost ? 'Lost' : 'Found' ?></label>
                        <p class="fw-bold mb-2">
                            <?= htmlspecialchars($isLost ? $itemDetails['date_lost'] : $itemDetails['date_found']) ?>
                        </p>
                    </div>
                    
                    <div class="detail-item">
                        <label class="text-muted mb-1">Reported by</label>
                        <p class="fw-bold mb-0"><?= htmlspecialchars($itemDetails['reporter_name']) ?></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn <?= $isLost ? 'btn-danger' : 'btn-primary' ?> claim-btn" 
                        data-item-id="<?= htmlspecialchars($itemDetails['item_id']) ?>"
                        data-item-type="<?= $isLost ? 'lost' : 'found' ?>">
                    Claim Item
                </button>
            </div>
        </div>
    </div>
</div> 
<style>
    /* Modal Styling */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.modal-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    padding: 1rem 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.details-container {
    border: 1px solid rgba(0,0,0,0.1);
}

.detail-item label {
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-item p {
    color: #2c3e50;
}

.modal-footer {
    border-top: 1px solid rgba(0,0,0,0.1);
    padding: 1rem 1.5rem;
}

.btn-close-white {
    filter: brightness(0) invert(1);
}
</style>