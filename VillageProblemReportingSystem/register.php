<?php 
include 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php'; 

if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $village = $_POST['village'];
    $address = $_POST['address'];

    if($password !== $confirm_password){
        $error = "Passwords do not match.";
    } elseif(strlen($mobile) != 10) {
        $error = "Mobile number must be 10 digits.";
    } elseif(strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if email or mobile exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR mobile = ?");
        $stmt->bind_param("ss", $email, $mobile);
        $stmt->execute();
        $stmt->store_result();
        
        if($stmt->num_rows > 0){
            $error = "Email or Mobile number already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, mobile, email, password, village, address) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $mobile, $email, $hashed_password, $village, $address);
            
            if($stmt->execute()){
                $success = "Registration successful. You can now <a href='login.php'>Login here</a>.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<div class="container" style="padding: 3rem 0;">
    <div class="form-container">
        <h2 style="text-align: center;">Register Account</h2>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="registerForm">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile Number *</label>
                <input type="text" id="mobile" name="mobile" pattern="[0-9]{10}" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" minlength="6" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>
            </div>
            <div class="form-group">
                <label for="village">Village Name *</label>
                <input type="text" id="village" name="village" required>
            </div>
            <div class="form-group">
                <label for="address">Full Address *</label>
                <textarea id="address" name="address" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
            <p style="text-align: center; margin-top: 1rem;">Already have an account? <a href="login.php" style="color: var(--primary); font-weight: 600;">Login</a></p>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
