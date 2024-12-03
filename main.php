<?php
session_start();
require_once 'classes/Item.class.php';
require_once 'classes/Claim.class.php';

$item = new Item(); // Initialize Item class
$claimInstance = new Claim(); // Create an instance of the Claim class
?>

<?php require_once 'includes_user_side/head.php';?>
<body>
    <!-- Navbar -->
    <?php require_once 'includes_user_side/topnav.php';?>
    <!-- kung gusto ka adto sa hero press lng ang home, pero not pa siya ajax -->
    <!-- Content -->
    <div id="content" class="content">   
        <!-- Dynamic content will be loaded here -->
    </div>
    
    <!-- Dynamic Modal Container -->
    <div id="modal-container"></div>



    <!-- Footer -->
    <?php require_once 'includes_user_side/footer.php';?>

</body>
</html>
