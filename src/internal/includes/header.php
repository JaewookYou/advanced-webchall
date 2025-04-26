<?php
session_start();

// Basic authentication check
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Only output header HTML if not included by FileIncluder
if(!isset($GLOBALS['SKIP_HEADER'])) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Company Internal Portal</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="header">
        <img src="/download.php?file=logo.png" alt="Company Logo">
    </div>
<?php
}
?>
</html> 