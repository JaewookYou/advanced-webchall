<?php
if(isset($_GET['file'])) {
    $file = $_GET['file'];
    
    $file = str_replace('../', '', $file);
    
    $filepath = "images/" . $file;
    
    header('Content-Type: image/jpeg');
    
    if(file_exists($filepath)) {
        readfile($filepath);
    }
}
?> 