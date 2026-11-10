<?php
include 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get complaint statistics for this user
$stmt = $conn->prepare("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as inprogress,
    SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved
    FROM complaints WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Recent complaints
$stmt = $conn->prepare("SELECT complaint_id, category, location, status, created_at FROM complaints WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_complaints = $stmt->get_result();
$stmt->close();
?>

<div class="container" style="padding: 3rem 0;">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    <p>Manage your village problem reports from this dashboard.</p>
    
    <div style="margin-top: 2rem; margin-bottom: 2rem;">
        <a href="report_problem.php" class="btn btn-primary">Report New Problem</a>
        <a href="my_complaints.php" class="btn btn-outline" style="margin-left: 1rem;">View My Complaints</a>
    </div>

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
    </div>

    <h3 style="margin-top: 3rem;">Recent Complaints</h3>
    <?php if($recent_complaints->num_rows > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Complaint ID</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $recent_complaints->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo $row['complaint_id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                            <td>
                                <?php
                                $badgeClass = 'badge-pending';
                                if($row['status'] == 'In Progress') $badgeClass = 'badge-inprogress';
                                if($row['status'] == 'Resolved') $badgeClass = 'badge-resolved';
                                if($row['status'] == 'Rejected') $badgeClass = 'badge-rejected';
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>"><?php echo $row['status']; ?></span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="color: var(--text-muted); padding: 1rem; background: var(--white); border-radius: 8px; text-align: center;">You have not submitted any complaints yet.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
