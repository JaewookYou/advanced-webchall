<?php
include_once 'includes/header.php';
?>
<div class="container">
    <h1>Welcome to Company Portal</h1>
    <p>This is the public facing website of our company.</p>
    
    <?php if(isAdmin()): ?>
        <p><a href="/admin/">Go to Admin Panel</a></p>
    <?php endif; ?>
    
    <div class="search-box">
        <h3>Search Our Products</h3>
        <form action="search.php" method="GET">
            <input type="text" name="q" placeholder="Enter search term...">
            <input type="submit" value="Search">
        </form>
    </div>
</div>
</body>
</html> 