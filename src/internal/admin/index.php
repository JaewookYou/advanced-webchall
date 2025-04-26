<?php
// Make sure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/header.php';
include_once '../includes/db.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'GET' && isAdmin()) {
    echo "<script>window.location.href = 'upload.php';</script>";
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ? AND is_admin = 1");
    $stmt->execute([$username, sha1($password)]);
    
    if($row = $stmt->fetch()) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['is_admin'] = true;
        
        // Create a unique session directory name for this user
        $_SESSION['upload_dir'] = session_id();
        
        // Create the user's upload directory if it doesn't exist
        $user_dir = '../uploads/' . $_SESSION['upload_dir'] . '/';
        $thumbnail_dir = $user_dir . 'thumbnails/';
        
        if (!file_exists($user_dir)) {
            mkdir($user_dir, 0777, true);
        }
        if (!file_exists($thumbnail_dir)) {
            mkdir($thumbnail_dir, 0777, true);
        }
        
        echo "<script>window.location.href = 'upload.php';</script>";
        exit();
    } else {
        $error = 'Invalid credentials';
    }
}
?>

<div class="container">
    <h2>Admin Login</h2>
    <?php if($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <div>
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
    </form>
</div> 