<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../account/login.php");
    exit;
}

require_once '../includes/_head.php';
?>

<body>
    <div class="wrapper">
        <?php
        require_once '../includes/_topnav.php'; // Include the top navigation
        ?>

        <div class="d-flex">
            <?php
            require_once '../includes/_sidebar.php'; // Include the sidebar
            ?>
            
            <!-- Main Content Area -->
            <div class="content-page">
                <div class="container mt-4">
                    <h1 class="text-danger">Admin Dashboard</h1>
                    <div id="dashboard-content">
                        <!-- Dynamic content will be loaded here -->
                        <p>Welcome to the Admin Dashboard</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    require_once '../includes/_footer.php';
    ?>
</body>
</html>
