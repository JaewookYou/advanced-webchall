<?php
include_once 'includes/header.php';
include_once 'includes/db.php';

function waf($input){
    $t = strtolower($input);
    if(preg_match("/union|like|admin|\||\&|-|\\\\|\x09|\x0b|\x0c|\x0d|\x20/",$t)){
        die('no hack..');
    } else {
        return $input;
    }
}

$searchType = isset($_GET['type']) ? $_GET['type'] : 'public';
$query = isset($_GET['q']) ? waf($_GET['q']) : '';

echo "<div class='container'>";
echo "<h2>Search Results</h2>";

if($query) {
    try {
        $sql = "SELECT id, name, description, price FROM products WHERE 1=1 ";
        if($searchType === 'public') {
            $sql .= "AND price < 100 ";
        }
        $sql .= "AND (name LIKE '%" . $query . "%' OR description LIKE '%" . $query . "%') LIMIT 1";
        
        $result = $pdo->query($sql);
        if($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='product'>";
            echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
            echo "<p>" . htmlspecialchars($row['description']) . "</p>";
            echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";
            echo "</div>";
        } else {
            echo "<p>No products found.</p>";
        }
    } catch(PDOException $e) {
        echo "An error occurred while searching.";
    }
}

echo "<p><a href='index.php'>Back to Home</a></p>";
echo "</div>";
?> 