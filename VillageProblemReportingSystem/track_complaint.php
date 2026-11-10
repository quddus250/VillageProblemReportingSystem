<?php
include 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

$complaint = null;
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $complaint_id = $_POST['complaint_id'];
    
    $stmt = $conn->prepare("SELECT complaint_id, category, location, status, admin_remarks, created_at, updated_at FROM complaints WHERE complaint_id = ?");
    $stmt->bind_param("s", $complaint_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0){
        $complaint = $result->fetch_assoc();
    } else {
        $error = "No complaint found with ID: " . htmlspecialchars($complaint_id);
    }
    $stmt->close();
}
?>

<div class="container" style="padding: 4rem 0; min-height: 70vh;">
    <div style="max-width: 600px; margin: 0 auto; text-align: center;">
        <h2 style="margin-bottom: 1rem;">Track Your Complaint</h2>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">Enter your unique Complaint ID to check the current status of your report.</p>
        
        <form method="POST" action="" style="display: flex; gap: 1rem; margin-bottom: 3rem;">
            <input type="text" name="complaint_id" placeholder="Enter Complaint ID (e.g. VPR2026001)" required style="flex: 1; padding: 0.8rem; border: 2px solid var(--primary); border-radius: 8px; font-size: 1rem;">
            <button type="submit" class="btn btn-primary">Track Status</button>
        </form>

        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($complaint): ?>
            <div style="background: var(--white); border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 2rem; text-align: left;">
                <h3 style="border-bottom: 2px solid var(--secondary); padding-bottom: 0.5rem; margin-bottom: 1.5rem; text-align: center;">Result for <?php echo $complaint['complaint_id']; ?></h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">Category</p>
                        <p style="font-weight: 600;"><?php echo htmlspecialchars($complaint['category']); ?></p>
                    </div>
                    <div>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">Location</p>
                        <p style="font-weight: 600;"><?php echo htmlspecialchars($complaint['location']); ?></p>
                    </div>
                    <div>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">Submitted On</p>
                        <p style="font-weight: 600;"><?php echo date('d M Y', strtotime($complaint['created_at'])); ?></p>
                    </div>
                    <div>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">Current Status</p>
                        <?php
                        $badgeClass = 'badge-pending';
                        if($complaint['status'] == 'In Progress') $badgeClass = 'badge-inprogress';
                        if($complaint['status'] == 'Resolved') $badgeClass = 'badge-resolved';
                        if($complaint['status'] == 'Rejected') $badgeClass = 'badge-rejected';
                        ?>
                        <span class="badge <?php echo $badgeClass; ?>"><?php echo $complaint['status']; ?></span>
                    </div>
                </div>

                <div style="background: var(--bg); padding: 1.5rem; border-radius: 8px; border-left: 4px solid var(--primary);">
                    <h4 style="margin-bottom: 0.5rem; font-size: 1rem;">Admin Remarks:</h4>
                    <p><?php echo $complaint['admin_remarks'] ? nl2br(htmlspecialchars($complaint['admin_remarks'])) : 'Your complaint is currently under review. No remarks provided yet.'; ?></p>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 1rem;">Last Updated: <?php echo date('d M Y, h:i A', strtotime($complaint['updated_at'])); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
