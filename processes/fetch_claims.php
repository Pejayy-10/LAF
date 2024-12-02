<?php
require_once '../classes/Claim.class.php';

header('Content-Type: application/json');

try {
    $claim = new Claim();
    $claims = $claim->fetchClaimsExcludingReporter($_SESSION['user_id']); // Pass the current user ID
    echo json_encode($claims);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
