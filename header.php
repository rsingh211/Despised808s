<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Despised - <?php echo $title ?? 'Home'; ?></title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
<nav class="navbar">
    <div class="logo">
        <a href="index.php">My Beats</a>
    </div>
    <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
      <li><a href="beats.php">Beats</a></li>
      <li><a href="about.php">About</a></li>
      <li><a href="contact.php">Contact</a></li>
      <li><a href="review.php">Review</a></li>
      <li><a href="register.php">Register</a></li>
      <li><a href="admin_beats.php">Admin</a></li>
        <!-- Show Login or Logout based on session -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
</header>
