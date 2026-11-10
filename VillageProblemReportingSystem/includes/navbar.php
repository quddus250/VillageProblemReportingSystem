<nav class="navbar">
    <div class="container nav-container">
        <a href="index.php" class="logo">Village Services</a>
        <ul class="nav-links">
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="report_problem.php">Report Problem</a></li>
                <li><a href="my_complaints.php">My Complaints</a></li>
                <li><a href="logout.php" class="btn btn-outline" style="padding: 0.3rem 0.8rem;">Logout</a></li>
            <?php else: ?>
                <li><a href="index.php">Home</a></li>
                <li><a href="track_complaint.php">Track Complaint</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php" class="btn btn-primary" style="padding: 0.3rem 0.8rem;">Register</a></li>
                <li><a href="admin/login.php" class="btn btn-outline" style="padding: 0.3rem 0.8rem;">Admin Login</a></li>
            <?php endif; ?>
        </ul>
        <div class="menu-toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </div>
</nav>
