<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

if(!isset($_GET['id'])){
    header("Location: manage_complaints.php");
    exit();
}

$id = (int)$_GET['id'];
$success = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $status = $_POST['status'];
    $admin_remarks = $_POST['admin_remarks'];

    $stmt = $conn->prepare("UPDATE complaints SET status = ?, admin_remarks = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $admin_remarks, $id);
    
    if($stmt->execute()){
        $success = "Complaint updated successfully.";
    } else {
        $error = "Failed to update complaint.";
    }
}

// Fetch complaint details
$stmt = $conn->prepare("SELECT c.*, u.name as user_name, u.mobile FROM complaints c JOIN users u ON c.user_id = u.id WHERE c.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "<div class='container' style='padding: 3rem 0;'><h2 style='text-align:center;'>Complaint not found.</h2></div>";
    exit();
}
$complaint = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Complaint - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include 'includes/admin_navbar.php'; ?>
    
    <div class="container" style="padding: 3rem 0;">
        <div style="background: var(--white); padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--secondary); padding-bottom: 1rem; margin-bottom: 2rem;">
                <h2>Update Complaint: <?php echo $complaint['complaint_id']; ?></h2>
                <a href="manage_complaints.php" class="btn btn-outline">Back to List</a>
            </div>

            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h4 style="margin-bottom: 1rem;">Complaint Info</h4>
                    <p><strong>Title:</strong> <?php echo htmlspecialchars($complaint['title']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($complaint['category']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($complaint['location']); ?></p>
                    <p><strong>Priority:</strong> <?php echo $complaint['priority']; ?></p>
                    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($complaint['description'])); ?></p>
                    
                    <h4 style="margin-top: 1.5rem; margin-bottom: 1rem;">User Info</h4>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($complaint['user_name']); ?></p>
                    <p><strong>Mobile:</strong> <?php echo htmlspecialchars($complaint['mobile']); ?></p>
                    <p><strong>Alt Contact:</strong> <?php echo htmlspecialchars($complaint['contact']); ?></p>
                </div>
                
                <div>
                    <h4>Uploaded Image:</h4>
                    <?php if($complaint['image']): ?>
                        <img src="../<?php echo $complaint['image']; ?>" alt="Problem Image" style="max-width: 100%; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <?php else: ?>
                        <div style="background: var(--bg); height: 150px; display: flex; align-items: center; justify-content: center; border-radius: 8px; color: var(--text-muted);">
                            No Image Uploaded
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div style="background: var(--secondary); padding: 2rem; border-radius: 12px;">
                <h3 style="margin-bottom: 1rem;">Update Status & Remarks</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="Pending" <?php echo $complaint['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="In Progress" <?php echo $complaint['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Resolved" <?php echo $complaint['status'] == 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                            <option value="Rejected" <?php echo $complaint['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Admin Remarks (Will be visible to user)</label>
                        <textarea name="admin_remarks" rows="4"><?php echo htmlspecialchars($complaint['admin_remarks']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Complaint</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/js/script.js"></script>
</body>
</html>
