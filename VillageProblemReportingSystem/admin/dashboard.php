<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

// Get statistics
$stats = [];
$result = $conn->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as inprogress,
    SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved,
    SUM(CASE WHEN priority = 'Emergency' THEN 1 ELSE 0 END) as emergency
    FROM complaints");
$stats = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Village Problem Reporting</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include 'includes/admin_navbar.php'; ?>
    
    <div class="container" style="padding: 3rem 0;">
        <h2>Admin Dashboard</h2>
        <p>Overview of village complaints.</p>

        <div class="grid-container">
            <div class="card">
                <h1 style="color: var(--primary); font-size: 2.5rem;"><?php echo $stats['total']; ?></h1>
                <p style="font-weight: 600;">Total Complaints</p>
            </div>
            <div class="card">
                <h1 style="color: var(--warning); font-size: 2.5rem;"><?php echo $stats['pending'] ?? 0; ?></h1>
                <p style="font-weight: 600;">Pending</p>
            </div>
            <div class="card">
                <h1 style="color: var(--info); font-size: 2.5rem;"><?php echo $stats['inprogress'] ?? 0; ?></h1>
                <p style="font-weight: 600;">In Progress</p>
            </div>
            <div class="card">
                <h1 style="color: var(--success); font-size: 2.5rem;"><?php echo $stats['resolved'] ?? 0; ?></h1>
                <p style="font-weight: 600;">Resolved</p>
            </div>
            <div class="card">
                <h1 style="color: var(--danger); font-size: 2.5rem;"><?php echo $stats['emergency'] ?? 0; ?></h1>
                <p style="font-weight: 600;">Emergency</p>
            </div>
        </div>

        <div style="margin-top: 3rem;">
            <h3>Quick Actions</h3>
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <a href="manage_complaints.php" class="btn btn-primary">Manage Complaints</a>
                <a href="manage_users.php" class="btn btn-outline">Manage Users</a>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/script.js"></script>
</body>
</html>
