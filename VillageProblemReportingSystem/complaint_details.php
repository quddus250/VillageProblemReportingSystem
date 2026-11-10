<?php
include 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$complaint_id = $_GET['id'];
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Fetch complaint
$stmt = $conn->prepare("SELECT * FROM complaints WHERE complaint_id = ?");
$stmt->bind_param("s", $complaint_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "<div class='container' style='padding: 3rem 0;'><h2 style='text-align:center;'>Complaint not found.</h2></div>";
    include 'includes/footer.php';
    exit();
}

$complaint = $result->fetch_assoc();

// Check authorization: restrict to owner for privacy.
if($user_id && $complaint['user_id'] != $user_id) {
     echo "<div class='container' style='padding: 3rem 0;'><div class='alert alert-danger text-center'>Access Denied.</div></div>";
     include 'includes/footer.php';
     exit();
}

// Handle feedback submission
$feedback_msg = '';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_feedback'])){
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];
    
    // Check if already feedback given
    $f_check = $conn->query("SELECT id FROM feedback WHERE complaint_id = '$complaint_id'");
    if($f_check->num_rows > 0){
        $feedback_msg = "<div class='alert alert-danger'>Feedback already submitted for this complaint.</div>";
    } else {
        $f_stmt = $conn->prepare("INSERT INTO feedback (complaint_id, user_id, rating, feedback) VALUES (?, ?, ?, ?)");
        $f_stmt->bind_param("siis", $complaint_id, $user_id, $rating, $feedback);
        if($f_stmt->execute()){
            $feedback_msg = "<div class='alert alert-success'>Thank you for your feedback!</div>";
        }
    }
}

// Check if feedback exists to hide form
$feedback_exists = false;
$f_check = $conn->query("SELECT id FROM feedback WHERE complaint_id = '$complaint_id'");
if($f_check->num_rows > 0){
    $feedback_exists = true;
}
?>

<div class="container" style="padding: 3rem 0;">
    <div style="background: var(--white); padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--secondary); padding-bottom: 1rem; margin-bottom: 2rem;">
            <h2>Complaint Details: <?php echo $complaint['complaint_id']; ?></h2>
            <?php
            $badgeClass = 'badge-pending';
            if($complaint['status'] == 'In Progress') $badgeClass = 'badge-inprogress';
            if($complaint['status'] == 'Resolved') $badgeClass = 'badge-resolved';
            if($complaint['status'] == 'Rejected') $badgeClass = 'badge-rejected';
            ?>
            <span class="badge <?php echo $badgeClass; ?>" style="font-size: 1rem; padding: 0.5rem 1rem;"><?php echo $complaint['status']; ?></span>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <p><strong>Title:</strong> <?php echo htmlspecialchars($complaint['title']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($complaint['category']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($complaint['location']); ?></p>
                <p><strong>Landmark:</strong> <?php echo htmlspecialchars($complaint['landmark']); ?></p>
                <p><strong>Priority:</strong> <?php echo $complaint['priority']; ?></p>
                <p><strong>Date Submitted:</strong> <?php echo date('d M Y, h:i A', strtotime($complaint['created_at'])); ?></p>
                <p><strong>Last Updated:</strong> <?php echo date('d M Y, h:i A', strtotime($complaint['updated_at'])); ?></p>
                
                <h4 style="margin-top: 1.5rem;">Description:</h4>
                <p style="background: var(--bg); padding: 1rem; border-radius: 8px;"><?php echo nl2br(htmlspecialchars($complaint['description'])); ?></p>

                <h4 style="margin-top: 1.5rem;">Admin Remarks:</h4>
                <p style="background: var(--bg); padding: 1rem; border-radius: 8px; border-left: 4px solid var(--primary);">
                    <?php echo $complaint['admin_remarks'] ? nl2br(htmlspecialchars($complaint['admin_remarks'])) : 'No remarks yet.'; ?>
                </p>
            </div>
            
            <div>
                <h4>Uploaded Image:</h4>
                <?php if($complaint['image']): ?>
                    <img src="<?php echo $complaint['image']; ?>" alt="Problem Image" style="max-width: 100%; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <?php else: ?>
                    <div style="background: var(--bg); height: 200px; display: flex; align-items: center; justify-content: center; border-radius: 8px; color: var(--text-muted);">
                        No Image Uploaded
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($complaint['status'] == 'Resolved' && $user_id == $complaint['user_id'] && !$feedback_exists): ?>
            <div style="margin-top: 3rem; background: var(--secondary); padding: 2rem; border-radius: 12px;">
                <h3 style="margin-bottom: 1rem;">Provide Feedback</h3>
                <?php echo $feedback_msg; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Rate the Resolution (1 to 5):</label>
                        <select name="rating" required style="width: 200px;">
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Poor</option>
                            <option value="1">1 - Very Poor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Your Feedback / Comments:</label>
                        <textarea name="feedback" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="submit_feedback" class="btn btn-primary">Submit Feedback</button>
                </form>
            </div>
        <?php elseif($feedback_exists): ?>
            <div style="margin-top: 3rem; background: #d4edda; padding: 1.5rem; border-radius: 12px; color: #155724;">
                <strong>Thank you!</strong> You have already submitted feedback for this complaint.
            </div>
        <?php endif; ?>

        <div style="margin-top: 2rem;">
            <a href="my_complaints.php" class="btn btn-outline">&larr; Back to My Complaints</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
