<?php
include 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT * FROM complaints WHERE user_id = ?";
if($status_filter){
    $query .= " AND status = ?";
}
$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
if($status_filter){
    $stmt->bind_param("is", $user_id, $status_filter);
} else {
    $stmt->bind_param("i", $user_id);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container" style="padding: 3rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>My Complaints</h2>
        
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

    <?php if($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Location</th>
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
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
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
                                <a href="complaint_details.php?id=<?php echo $row['complaint_id']; ?>" class="btn btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.85rem;">View</a>
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

<?php include 'includes/footer.php'; ?>
