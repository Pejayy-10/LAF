<style>
    .dropdown-item:hover {
        background-color: #C7253E !important;
        color: white !important;
    }
</style>
<nav class="navbar navbar-expand-lg shadow-sm sticky-top" style="background-color: white;">
    <div class="container-fluid d-flex justify-content-between align-items-center" style="padding-left: 25px;">
        <a class="navbar-brand" href="main.php" style="color: #C7253E; font-weight: bold;">Lost & Found</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <span class="nav-link" style="color: #C7253E; font-weight: bold;">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user/hero.html" id="Home" role="button" style="color: #C7253E; font-weight: bold;">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="Home" role="button" style="color: #C7253E; font-weight: bold;">
                            Lost and Found
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="report-btn" role="button" style="color: #C7253E; font-weight: bold;">
                            Report Item
                        </a>
                    </li>
                    <li class="nav-item">
                        <!-- sample link -->
                        <a class="nav-link" href="#" id="Home" role="button" style="color: #C7253E; font-weight: bold;">
                            About Us
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #C7253E;">
                            <img src="uploads/profile/profile-pic.jpg" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="#" id="openProfileModal" style="color: #C7253E; transition: all 0.3s;">Profile View</a></li>
                            <li><a class="dropdown-item" href="report_history.php" style="color: #C7253E; transition: all 0.3s;">Report History</a></li>
                            <li><a class="dropdown-item" href="pending_reports.php" style="color: #C7253E; transition: all 0.3s;">Pending Reports</a></li>
                            <li><a class="dropdown-item" href="account/logout.php" style="color: #C7253E; transition: all 0.3s;">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="account/auth.php" style="color: #C7253E; font-weight: bold;">Login/Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
