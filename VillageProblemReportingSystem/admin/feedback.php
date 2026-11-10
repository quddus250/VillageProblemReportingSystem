<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

$query = "SELECT f.*, u.name as user_name FROM feedback f JOIN users u ON f.user_id = u.id ORDER BY f.created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Feedback - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include 'includes/admin_navbar.php'; ?>
    
    <div class="container" style="padding: 3rem 0;">
        <h2>User Feedback</h2>
        <p>Feedback provided by citizens on resolved complaints.</p>

        <?php if($result->num_rows > 0): ?>
            <div class="grid-container">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="card" style="text-align: left; padding: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                            <strong><?php echo $row['complaint_id']; ?></strong>
                            <span style="color: var(--accent); font-weight: bold;">Rating: <?php echo $row['rating']; ?>/5</span>
                        </div>
                        <p style="font-weight: 500; margin-bottom: 0.5rem;">By: <?php echo htmlspecialchars($row['user_name']); ?></p>
                        <p style="color: var(--text-muted); font-style: italic;">"<?php echo htmlspecialchars($row['feedback']); ?>"</p>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 1rem; text-align: right;"><?php echo date('d M Y', strtotime($row['created_at'])); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert" style="background-color: var(--white); text-align: center;">No feedback received yet.</div>
        <?php endif; ?>
    </div>
    <script src="../assets/js/script.js"></script>
</body>
</html>
