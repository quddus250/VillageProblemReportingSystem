<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

// Handle deletion
if(isset($_GET['delete'])){
    $del_id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM complaints WHERE id = ?");
    $stmt->bind_param("i", $del_id);
    $stmt->execute();
    header("Location: manage_complaints.php?msg=deleted");
    exit();
}

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT c.*, u.name as user_name FROM complaints c JOIN users u ON c.user_id = u.id";
if($status_filter){
    $query .= " WHERE c.status = ?";
}
$query .= " ORDER BY c.created_at DESC";

$stmt = $conn->prepare($query);
if($status_filter){
    $stmt->bind_param("s", $status_filter);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Complaints - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include 'includes/admin_navbar.php'; ?>
    
    <div class="container" style="padding: 3rem 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2>Manage Complaints</h2>
            
            <form method="GET" action="" style="display: flex; gap: 0.5rem;">
                <select name="status" style="padding: 0.5rem; border-radius: 4px; border: 1px solid var(--border);">
                    <option value="">All Statuses</option>
                    <option value="Pending" <?php echo $status_filter == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="In Progress" <?php echo $status_filter == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Resolved" <?php echo $status_filter == 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                    <option value="Rejected" <?php echo $status_filter == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
                <button type="submit" class="btn btn-outline" style="padding: 0.5rem 1rem;">Filter</button>
            </form>
        </div>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Complaint deleted successfully.</div>
        <?php endif; ?>

        <?php if($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Complaint ID</th>
                            <th>User Name</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo $row['complaint_id']; ?></strong></td>
                                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo $row['priority']; ?></td>
                                <td>
                                    <?php
                                    $badgeClass = 'badge-pending';
                                    if($row['status'] == 'In Progress') $badgeClass = 'badge-inprogress';
                                    if($row['status'] == 'Resolved') $badgeClass = 'badge-resolved';
                                    if($row['status'] == 'Rejected') $badgeClass = 'badge-rejected';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $row['status']; ?></span>
                                </td>
                                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="update_complaint.php?id=<?php echo $row['id']; ?>" class="btn btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.85rem;">Update</a>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn" style="background-color: var(--danger); color: white; padding: 0.3rem 0.6rem; font-size: 0.85rem;" onclick="return confirm('Are you sure you want to delete this complaint?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert" style="background-color: var(--white); text-align: center;">No complaints found.</div>
        <?php endif; ?>
    </div>
    <script src="../assets/js/script.js"></script>
</body>
</html>
