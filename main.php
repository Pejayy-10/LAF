<?php
session_start();
require_once 'classes/Item.class.php';
require_once 'classes/Claim.class.php';

$item = new Item(); // Initialize Item class
$claimInstance = new Claim(); // Create an instance of the Claim class
?>

<?php require_once 'includes_user_side/head.php'; ?>
<body>
    <!-- Navbar -->
    <?php require_once 'includes_user_side/topnav.php';?>

    <!-- Content -->
    <div class="content" id="content-area">
        <!-- Home page content will be loaded here -->
    </div>
    
    <!-- Dynamic Modal Container -->
    <div id="modal-container"></div>

    <!-- Footer -->
    <?php require_once 'includes_user_side/footer.php';?>

</body>
</html>