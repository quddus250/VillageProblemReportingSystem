<?php 
include 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php'; 

if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email_mobile = $_POST['email_mobile'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ? OR mobile = ?");
    $stmt->bind_param("ss", $email_mobile, $email_mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found with this email/mobile.";
    }
    $stmt->close();
}
?>

<div class="container" style="padding: 5rem 0;">
    <div class="form-container">
        <h2 style="text-align: center; margin-bottom: 2rem;">User Login</h2>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email_mobile">Email or Mobile Number *</label>
                <input type="text" id="email_mobile" name="email_mobile" required>
            </div>
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            <p style="text-align: center; margin-top: 1rem;">Don't have an account? <a href="register.php" style="color: var(--primary); font-weight: 600;">Register</a></p>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
