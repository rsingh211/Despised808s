<?php
session_start();
include 'header.php';
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the latest 5 reviews by the logged-in user
$user_id = $_SESSION['user_id'];
$query = $pdo->prepare("
    SELECT r.review_text, r.rating, b.beat_name, u.username
    FROM reviews r
    JOIN beats b ON r.beat_id = b.beat_id
    JOIN users u ON r.user_id = u.user_id
    WHERE r.user_id = :user_id
    ORDER BY r.created_at DESC LIMIT 5
");
$query->execute(['user_id' => $user_id]);
$reviews = $query->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for new review
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $beat_id = $_POST['beat_id'];
    $review_text = $_POST['review_text'];
    $rating = $_POST['rating'];

    // Insert the review into the database
    $stmt = $pdo->prepare("INSERT INTO reviews (user_id, beat_id, review_text, rating, created_at)
                           VALUES (:user_id, :beat_id, :review_text, :rating, NOW())");
    $stmt->execute([
        'user_id' => $user_id,
        'beat_id' => $beat_id,
        'review_text' => $review_text,
        'rating' => $rating
    ]);

    header("Location: review.php");
    exit();
}

// Fetch all beats for the dropdown
$beats = $pdo->query("SELECT beat_id, beat_name FROM beats")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .review-list {
            margin-bottom: 20px;
        }

        .review-item {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        select, textarea, input[type="submit"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        textarea {
            resize: vertical;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Reviews</h1>

        <div class="review-list">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <strong><?php echo htmlspecialchars($review['beat_name']); ?></strong><br>
                        <em>Reviewed by: <?php echo htmlspecialchars($review['username']); ?></em><br>
                        Rating: <?php echo htmlspecialchars($review['rating']); ?> / 5<br>
                        <?php echo htmlspecialchars($review['review_text']); ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reviews yet. Submit one below!</p>
            <?php endif; ?>
        </div>

        <h2>Submit a Review</h2>
        <form method="POST">
            <select name="beat_id" required>
                <option value="" disabled selected>Select a Beat</option>
                <?php foreach ($beats as $beat): ?>
                    <option value="<?php echo $beat['beat_id']; ?>"><?php echo htmlspecialchars($beat['beat_name']); ?></option>
                <?php endforeach; ?>
            </select>
            <textarea name="review_text" rows="4" placeholder="Write your review..." required></textarea>
            <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" required>
            <input type="submit" value="Submit Review">
        </form>
    </div>
</body>
</html>
