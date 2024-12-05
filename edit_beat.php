<?php
session_start();
include 'header.php';
include 'db.php';

// Check if the user is an admin (Add verification logic if required)

// Fetch the beat to edit
if (!isset($_GET['id'])) {
    header("Location: admin_beats.php");
    exit();
}
$beat_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM beats WHERE beat_id = :beat_id");
$stmt->execute(['beat_id' => $beat_id]);
$beat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$beat) {
    header("Location: admin_beats.php");
    exit();
}

// Handle edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $beat_name = $_POST['beat_name'];
    $type = $_POST['type'];
    $rate = $_POST['rate'];

    // Update beat details
    $stmt = $pdo->prepare("UPDATE beats SET beat_name = :beat_name, type = :type, rate = :rate WHERE beat_id = :beat_id");
    $stmt->execute([
        'beat_name' => $beat_name,
        'type' => $type,
        'rate' => $rate,
        'beat_id' => $beat_id
    ]);

    header("Location: admin_beats.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Beat</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
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

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Beat</h1>
        <form method="POST">
            <input type="text" name="beat_name" value="<?php echo htmlspecialchars($beat['beat_name']); ?>" placeholder="Beat Name" required>
            <select name="type" required>
                <option value="Hip Hop" <?php if ($beat['type'] == 'Hip Hop') echo 'selected'; ?>>Hip Hop</option>
                <option value="Romantic" <?php if ($beat['type'] == 'Romantic') echo 'selected'; ?>>Romantic</option>
                <option value="Sad" <?php if ($beat['type'] == 'Sad') echo 'selected'; ?>>Sad</option>
            </select>
            <input type="number" name="rate" step="0.01" value="<?php echo htmlspecialchars($beat['rate']); ?>" placeholder="Rate" required>
            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
