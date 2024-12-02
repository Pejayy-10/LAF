<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo 'Unauthorized access!';
    exit;
}

require_once '../classes/Item.class.php';

$itemId = $_GET['item_id'];
$itemType = $_GET['item_type']; // 'lost' or 'found'
?>
<div class="modal fade" id="claimItemModal" tabindex="-1" aria-labelledby="claimItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="claim-item-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="claimItemModalLabel">Claim <?= htmlspecialchars($itemType) ?> Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="item_id" value="<?= htmlspecialchars($itemId) ?>">
                    <input type="hidden" name="item_type" value="<?= htmlspecialchars($itemType) ?>">
                    <div class="mb-3">
                        <label for="text_proof" class="form-label">Proof of Ownership (Text)</label>
                        <textarea id="text_proof" name="text_proof" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image_proof" class="form-label">Proof of Ownership (Image)</label>
                        <input type="file" id="image_proof" name="image_proof" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn 
                        <?= $itemType === 'lost' ? 'btn-danger' : 'btn-primary' ?>
                    ">Submit Claim</button>
                </div>
            </form>
        </div>
    </div>
</div>
