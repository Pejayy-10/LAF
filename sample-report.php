<?php
session_start();
require_once 'classes/Item.class.php';

$item = new Item();

// Fetch Lost and Found Items
$lostItems = $item->fetchLostItems();
$foundItems = $item->fetchFoundItems();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Lost & Found</title>
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="main.php">Lost & Found</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="main.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="account/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="account/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="account/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <header class="bg-light text-center py-5">
        <div class="container">
            <h1 class="text-danger display-4">Campus Lost & Found</h1>
            <p class="lead">Welcome to the Campus Lost & Found platform. Report or find lost items easily!</p>
        </div>
    </header>

    <div class="container mt-4">
        <!-- Action Buttons -->
        <div class="text-center mb-5">
            <button id="report-lost-btn" class="btn btn-danger btn-lg shadow-sm">Report Lost Item</button>
            <button id="report-found-btn" class="btn btn-primary btn-lg shadow-sm">Report Found Item</button>
        </div>

        <!-- Lost Items Section -->
    <section>
        <h2 class="text-danger mb-3">Lost Items</h2>
        <div class="row">
            <?php if (!empty($lostItems)) : ?>
                <?php foreach ($lostItems as $item) : ?>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <img src="<?= str_replace('../', '', htmlspecialchars($item['image'])) ?>" class="card-img-top" alt="Lost Item Image">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($item['description']) ?></h5>
                                <p class="card-text">
                                    <strong>Location:</strong> <?= htmlspecialchars($item['location']) ?><br>
                                    <strong>Date Lost:</strong> <?= htmlspecialchars($item['date_lost']) ?>
                                </p>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-success btn-sm claim-btn" data-id="<?= $item['item_id'] ?>">Claim</button>
                                    <button class="btn btn-danger btn-sm details-btn" data-id="<?= $item['item_id'] ?>">View Details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No lost items available at the moment.</p>
            <?php endif; ?>
        </div>
    </section>


    <!-- Found Items Section -->
    <section class="mt-5">
        <h2 class="text-primary mb-3">Found Items</h2>
        <div class="row">
            <?php if (!empty($foundItems)) : ?>
                <?php foreach ($foundItems as $item) : ?>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <img src="<?= str_replace('../', '', htmlspecialchars($item['image'])) ?>" class="card-img-top" alt="Found Item Image">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($item['description']) ?></h5>
                                <p class="card-text">
                                    <strong>Location:</strong> <?= htmlspecialchars($item['location']) ?><br>
                                    <strong>Date Found:</strong> <?= htmlspecialchars($item['date_found']) ?>
                                </p>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-success btn-sm claim-btn" data-id="<?= $item['item_id'] ?>">Claim</button>
                                    <button class="btn btn-primary btn-sm details-btn" data-id="<?= $item['item_id'] ?>">View Details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No found items listed at the moment.</p>
            <?php endif; ?>
        </div>
    </section>


    </div>

    <!-- Dynamic Modal Container -->
    <div id="modal-container"></div>

    <?php require_once 'includes/_footer.php'; ?>

    <!-- Scripts -->
    <script src="js/main.js"></script>
</body>
</html>
