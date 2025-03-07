<?php
// navbar.php
session_start();

$base_url = '/skilldrop/skilldrop/'; 

?>
<nav class="nav">
    <ul class="nav-list">
        <li><a href="<?php echo $base_url; ?>index.php" class="nav-link">Home</a></li>
        <li><a href="<?php echo $base_url; ?>about-us.php" class="nav-link">About</a></li>
        <li><a href="<?php echo $base_url; ?>contact-us.php" class="nav-link">Contact</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="<?php echo $base_url; ?>dashboard.php" class="nav-link">Dashboard</a></li>
            <li><a href="<?php echo $base_url; ?>logout.php" class="nav-link">Log Out</a></li>
        <?php else: ?>
            <li><a href="<?php echo $base_url; ?>auth/login.php" class="nav-link">Log In</a></li>
            <li><a href="<?php echo $base_url; ?>auth/register.php" class="nav-link">Sign Up</a></li>
        <?php endif; ?>
    </ul>
</nav>
