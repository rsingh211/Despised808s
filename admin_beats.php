<?php
session_start();
include 'header.php';
include 'db.php';
include 'authenticate.php';
// Check if the user is an admin (Add admin verification logic if required)
// Example: if ($_SESSION['role'] !== 'admin') { header("Location: login.php"); exit(); }

// Handle adding a new beat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_beat'])) {
    $beat_name = $_POST['beat_name'];
    $type = $_POST['type'];
    $rate = $_POST['rate'];

    // Check if files are uploaded
    if (isset($_FILES['image']) && isset($_FILES['audio'])) {
        $image_path = 'images/' . basename($_FILES['image']['name']);
        $audio_path = 'beats/' . basename($_FILES['audio']['name']);

        // Move uploaded files to their directories
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path) &&
            move_uploaded_file($_FILES['audio']['tmp_name'], $audio_path)) {
            // Insert new beat into database
            $stmt = $pdo->prepare("INSERT INTO beats (beat_name, type, rate, image_path, audio_path)
                                   VALUES (:beat_name, :type, :rate, :image_path, :audio_path)");
            $stmt->execute([
                'beat_name' => $beat_name,
                'type' => $type,
                'rate' => $rate,
                'image_path' => $image_path,
                'audio_path' => $audio_path
            ]);
        } else {
            $error = "Error uploading files.";
        }
    } else {
        $error = "Both image and audio files are required.";
    }
}

// Handle editing a beat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_beat'])) {
    $beat_id = $_POST['beat_id'];
    $beat_name = $_POST['beat_name'];
    $type = $_POST['type'];
    $rate = $_POST['rate'];

    // Update beat information
    $stmt = $pdo->prepare("UPDATE beats SET beat_name = :beat_name, type = :type, rate = :rate WHERE beat_id = :beat_id");
    $stmt->execute([
        'beat_name' => $beat_name,
        'type' => $type,
        'rate' => $rate,
        'beat_id' => $beat_id
    ]);
}

// Handle deleting a beat
if (isset($_GET['delete_beat'])) {
    $beat_id = $_GET['delete_beat'];

    // Delete the beat
    $stmt = $pdo->prepare("DELETE FROM beats WHERE beat_id = :beat_id");
    $stmt->execute(['beat_id' => $beat_id]);
}

// Handle deleting a review
if (isset($_GET['delete_review'])) {
    $review_id = $_GET['delete_review'];

    // Delete the review
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE review_id = :review_id");
    $stmt->execute(['review_id' => $review_id]);
}

// Fetch all beats and reviews
$beats = $pdo->query("SELECT * FROM beats")->fetchAll(PDO::FETCH_ASSOC);
$reviews = $pdo->query("
    SELECT r.review_id, r.review_text, r.rating, b.beat_name, u.username
    FROM reviews r
    JOIN beats b ON r.beat_id = b.beat_id
    JOIN users u ON r.user_id = u.user_id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Beats</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1, h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-danger {
            background-color: #e63946;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input, select, button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Beats Management</h1>

        <!-- Add Beat Form -->
        <h2>Add a New Beat</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="beat_name" placeholder="Beat Name" required>
            <select name="type" required>
                <option value="" disabled selected>Select Type</option>
                <option value="Hip Hop">Hip Hop</option>
                <option value="Romantic">Romantic</option>
                <option value="Sad">Sad</option>
            </select>
            <input type="number" name="rate" step="0.01" placeholder="Rate" required>
            <input type="file" name="image" accept="image/*" required>
            <input type="file" name="audio" accept="audio/*" required>
            <button type="submit" name="add_beat" class="btn btn-primary">Add Beat</button>
        </form>

        <!-- Beats Table -->
        <h2>All Beats</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Rate</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($beats as $beat): ?>
                <tr>
                    <td><?php echo $beat['beat_id']; ?></td>
                    <td><?php echo htmlspecialchars($beat['beat_name']); ?></td>
                    <td><?php echo htmlspecialchars($beat['type']); ?></td>
                    <td>$<?php echo number_format($beat['rate'], 2); ?></td>
                    <td>
                        <a href="edit_beat.php?id=<?php echo $beat['beat_id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="admin_beats.php?delete_beat=<?php echo $beat['beat_id']; ?>" class="btn btn-danger"
                           onclick="return confirm('Are you sure you want to delete this beat?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Reviews Table -->
        <h2>All Reviews</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Beat Name</th>
                <th>Username</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?php echo $review['review_id']; ?></td>
                    <td><?php echo htmlspecialchars($review['beat_name']); ?></td>
                    <td><?php echo htmlspecialchars($review['username']); ?></td>
                    <td><?php echo htmlspecialchars($review['rating']); ?> / 5</td>
                    <td><?php echo htmlspecialchars($review['review_text']); ?></td>
                    <td>
                        <a href="admin_beats.php?delete_review=<?php echo $review['review_id']; ?>" class="btn btn-danger"
                           onclick="return confirm('Are you sure you want to delete this review?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
