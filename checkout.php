<?php
session_start();
include 'header.php'; // Include header for navigation
include 'db.php'; // Include database connection

// Check if a beat ID is passed
if (!isset($_GET['beat_id'])) {
    echo "<p>No beat selected for purchase.</p>";
    include 'footer.php';
    exit;
}

$beat_id = intval($_GET['beat_id']); // Get the beat ID securely

// Fetch beat details
$query = $pdo->prepare("SELECT * FROM beats WHERE id = :beat_id");
$query->execute([':beat_id' => $beat_id]);
$beat = $query->fetch(PDO::FETCH_ASSOC);

if (!$beat) {
    echo "<p>Beat not found.</p>";
    include 'footer.php';
    exit;
}

// Simulate purchase (Replace this with actual logic for payment or order processing)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert a new purchase record
    $user_id = $_SESSION['user_id'] ?? 1; // Replace with logged-in user's ID
    $insert = $pdo->prepare("INSERT INTO purchases (user_id, beat_id, created_at) VALUES (:user_id, :beat_id, NOW())");
    $insert->execute([':user_id' => $user_id, ':beat_id' => $beat_id]);

    // Get the last inserted purchase ID
    $purchase_id = $pdo->lastInsertId();

    // Redirect to the invoice page
    header("Location: invoice.php?purchase_id=$purchase_id");
    exit;
}
?>

<section class="checkout-section">
    <h2>Checkout</h2>
    <div class="checkout-details">
        <h3><?php echo htmlspecialchars($beat['title']); ?></h3>
        <p>By: <?php echo htmlspecialchars($beat['artist_name']); ?></p>
        <p>Price: $<?php echo htmlspecialchars($beat['price']); ?></p>
        <form method="post">
            <button type="submit" class="buy-btn">Confirm Purchase</button>
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>
