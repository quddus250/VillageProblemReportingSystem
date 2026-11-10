<?php
include 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $landmark = $_POST['landmark'];
    $priority = $_POST['priority'];
    $contact = $_POST['contact'];
    
    // Generate unique complaint ID (e.g., VPR2026001)
    $year = date("Y");
    $stmt = $conn->query("SELECT COUNT(*) as count FROM complaints WHERE YEAR(created_at) = $year");
    $row = $stmt->fetch_assoc();
    $next_num = $row['count'] + 1;
    $complaint_id = "VPR" . $year . str_pad($next_num, 3, "0", STR_PAD_LEFT);

    // Handle image upload
    $image_path = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $target_dir = "uploads/";
        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $file_name = $complaint_id . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $file_name;
        
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        if(in_array(strtolower($file_extension), $allowed_types)){
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
                $image_path = $target_file;
            } else {
                $error = "Error uploading image.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    if(empty($error)){
        $stmt = $conn->prepare("INSERT INTO complaints (complaint_id, user_id, title, category, description, location, landmark, priority, image, contact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssssss", $complaint_id, $user_id, $title, $category, $description, $location, $landmark, $priority, $image_path, $contact);
        
        if($stmt->execute()){
            $success = "Complaint registered successfully! Your Complaint ID is: <strong>$complaint_id</strong>";
        } else {
            $error = "Failed to register complaint. Please try again.";
        }
        $stmt->close();
    }
}
?>

<div class="container" style="padding: 3rem 0;">
    <div class="form-container" style="max-width: 700px;">
        <h2 style="text-align: center; margin-bottom: 2rem;">Report a Problem</h2>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Problem Title *</label>
                <input type="text" id="title" name="title" required placeholder="e.g. Huge pothole on Main Street">
            </div>
            
            <div class="form-group">
                <label for="category">Category *</label>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="Road Damage">Road Damage</option>
                    <option value="Drainage Issue">Drainage Issue</option>
                    <option value="Garbage Problem">Garbage Problem</option>
                    <option value="Street Light Issue">Street Light Issue</option>
                    <option value="Water Supply Issue">Water Supply Issue</option>
                    <option value="Electricity Issue">Electricity Issue</option>
                    <option value="Health & Sanitation">Health & Sanitation</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">Detailed Description *</label>
                <textarea id="description" name="description" rows="4" required placeholder="Describe the problem in detail..."></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="location">Location/Area Name *</label>
                    <input type="text" id="location" name="location" required>
                </div>
                <div class="form-group">
                    <label for="landmark">Nearest Landmark</label>
                    <input type="text" id="landmark" name="landmark">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="priority">Priority Level *</label>
                    <select id="priority" name="priority" required>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Emergency">Emergency</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contact">Contact Number *</label>
                    <input type="text" id="contact" name="contact" pattern="[0-9]{10}" required placeholder="10-digit number">
                </div>
            </div>

            <div class="form-group">
                <label for="image">Upload Image (Optional but recommended)</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Submit Complaint</button>
                <button type="reset" class="btn btn-outline" style="flex: 1;">Clear Form</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
