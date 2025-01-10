<?php
session_start();
include "config.php";

// Initialize variables
$error = "";
$username = "";

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Check for failed login attempts
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $check_attempts = mysqli_query($conn, "SELECT COUNT(*) as attempts FROM login_attempts 
        WHERE username = '$username' 
        AND ip_address = '$ip_address' 
        AND attempt_time > DATE_SUB(NOW(), INTERVAL 15 MINUTE)");
    $attempts = mysqli_fetch_assoc($check_attempts)['attempts'];
    
    if ($attempts >= 5) {
        $error = "Too many failed login attempts. Please try again in 15 minutes.";
    } else {
        // Check if username exists
        $sql = "SELECT * FROM users WHERE username = '$username' AND status = 'active'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Update last login time
                mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE user_id = " . $user['user_id']);
                
                // Clear login attempts
                mysqli_query($conn, "DELETE FROM login_attempts WHERE username = '$username'");
                
                header("Location: index.php");
                exit();
            } else {
                // Failed login attempt
                mysqli_query($conn, "INSERT INTO login_attempts (username, ip_address) VALUES ('$username', '$ip_address')");
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Donation Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="auth-container">
        <h2>Login</h2>
        <?php if($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>