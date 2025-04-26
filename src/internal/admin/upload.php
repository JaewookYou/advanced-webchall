<?php
// Start the session at the beginning
session_start();

class FileIncluder {
    public $filename = "includes/config.php";  // Default to config file
    
    function __construct($file = "includes/config.php") {
        $this->filename = $file;
    }
    
    function __destruct() {
        include $this->filename;
    }
}

function includeFiles() {
    $headerIncluder = new FileIncluder('../includes/header.php');
    $dbIncluder = new FileIncluder('../includes/db.php');
    $configIncluder = new FileIncluder('../includes/config.php');
    unset($headerIncluder);
    unset($dbIncluder); 
    unset($configIncluder);
}

includeFiles();

if(!isAdmin()) {
    header('Location: index.php');
    exit;
}

// Create a unique session directory name if not exists
if (!isset($_SESSION['upload_dir'])) {
    $_SESSION['upload_dir'] = session_id();
}

$message = '';
$base_upload_dir = '../uploads/'.$_SESSION['upload_dir'].'/';
$thumbnail_dir = '../uploads/'.$_SESSION['upload_dir'].'/thumbnails/';

// Simple processing of upload_dir parameter
$upload_dir = isset($_POST['upload_dir']) ? $_POST['upload_dir'] : $base_upload_dir;

// Create directories if they don't exist
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}
if (!file_exists($thumbnail_dir)) {
    mkdir($thumbnail_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $filename = basename($file['name']);
    $target_path = $upload_dir . '/' . $filename;
    
    // Check file extension
    $blocked_extensions = array('phar', 'php', 'php3', 'php4', 'php5', 'phtml');
    $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if(in_array($file_extension, $blocked_extensions)) {
        $message = '<p style="color: red;">This file type is not allowed!</p>';
    } else {
        echo "Testing file signature...<br>";
        // First check file signature
        $image_info = getimagesize($file['tmp_name']);
        
        if ($image_info) {
            echo '<p style="color: green;">Valid image signature detected!</p>';
            
            echo "Creating thumbnail...<br>";
            
            // Only move file if it's a valid image
            move_uploaded_file($file['tmp_name'], $target_path);
            $message = "File uploaded successfully to: " . htmlspecialchars($target_path);
            
            if (file_exists($target_path)) {
                // For Creating thumbnail
                $thumb_path = $thumbnail_dir . $filename;

                // Todo : implement thumbnail creation
                // echo '<p style="color: green;">Thumbnail created successfully!</p>';
                
            }
        } else {
            $message = '<p style="color: red;">Invalid image file!</p>';
            unlink($target_path);  // Remove invalid file
        }
    
    }
}
?>

<div class="container">
    <h2>Admin Image Upload</h2>
    
    <p>Your session upload directory: <?php echo htmlspecialchars($_SESSION['upload_dir']); ?></p>
    
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div>
            <label>Select Image:</label>
            <input type="file" name="image" required>
        </div>
        <div>
            <input type="submit" value="Upload">
        </div>
    </form>
    
    <h3>Your Uploaded Images:</h3>
    <div class="gallery">
        <?php
        // Only show files from this user's directory
        $files = glob($upload_dir . "*.*");
        foreach($files as $file) {
            if (is_file($file)) {
                $filename = basename($file);
                echo "<div class='image-item'>";
                echo "<img src='" . htmlspecialchars($upload_dir . $filename) . "' alt='Thumbnail'>";
                echo "<p>Path: " . htmlspecialchars($file) . "</p>";
                echo "</div>";
            }
        }
        ?>
    </div>
</div>

<style>
.gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    padding: 1rem;
}
.image-item {
    text-align: center;
}
.image-item img {
    max-width: 100px;
    height: auto;
}
</style> 