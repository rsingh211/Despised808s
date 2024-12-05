<?php
session_start();
include 'header.php';
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ensure a beat_id is provided
if (!isset($_GET['beat_id'])) {
    header("Location: beats.php");
    exit();
}

$beat_id = $_GET['beat_id'];

// Fetch beat details
$stmt = $pdo->prepare("SELECT * FROM beats WHERE beat_id = :beat_id");
$stmt->execute(['beat_id' => $beat_id]);
$beat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$beat) {
    header("Location: beats.php");
    exit();
}

// Handle purchase
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $quantity = $_POST['quantity'];
    $total_price = $beat['rate'] * $quantity;

    // Insert purchase record
    $stmt = $pdo->prepare("
        INSERT INTO purchase_record (user_id, beat_id, quantity, total_price, purchased_at)
        VALUES (:user_id, :beat_id, :quantity, :total_price, NOW())
    ");
    $stmt->execute([
        'user_id' => $user_id,
        'beat_id' => $beat_id,
        'quantity' => $quantity,
        'total_price' => $total_price
    ]);

    // Get the last inserted purchase ID
    $purchase_id = $pdo->lastInsertId();

    // Redirect to invoice
    header("Location: invoice.php?purchase_id=$purchase_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase</title>
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

        input, button {
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
        <h1>Purchase Beat</h1>
        <p><strong>Beat Name:</strong> <?php echo htmlspecialchars($beat['beat_name']); ?></p>
        <p><strong>Price per Beat:</strong> $<?php echo number_format($beat['rate'], 2); ?></p>
        <form method="POST">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" value="1" required>
            <button type="submit">Confirm Purchase</button>
        </form>
    </div>
</body>
</html>
