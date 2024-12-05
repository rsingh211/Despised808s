<?php
session_start();
include 'header.php';
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ensure a purchase_id is provided
if (!isset($_GET['purchase_id'])) {
    header("Location: beats.php");
    exit();
}

$purchase_id = $_GET['purchase_id'];

// Fetch purchase details
$stmt = $pdo->prepare("
    SELECT pr.*, b.beat_name, b.rate, u.username
    FROM purchase_record pr
    JOIN beats b ON pr.beat_id = b.beat_id
    JOIN users u ON pr.user_id = u.user_id
    WHERE pr.purchase_id = :purchase_id
");
$stmt->execute(['purchase_id' => $purchase_id]);
$purchase = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$purchase) {
    header("Location: beats.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        td {
            background-color: #f9f9f9;
        }

        .total-row td {
            font-weight: bold;
        }

        .btn {
            display: block;
            margin: 20px auto;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            width: 150px;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Invoice</h1>
        <table>
            <tr>
                <th>Field</th>
                <th>Details</th>
            </tr>
            <tr>
                <td>Username</td>
                <td><?php echo htmlspecialchars($purchase['username']); ?></td>
            </tr>
            <tr>
                <td>Beat Name</td>
                <td><?php echo htmlspecialchars($purchase['beat_name']); ?></td>
            </tr>
            <tr>
                <td>Quantity</td>
                <td><?php echo htmlspecialchars($purchase['quantity']); ?></td>
            </tr>
            <tr>
                <td>Price per Beat</td>
                <td>$<?php echo number_format($purchase['rate'], 2); ?></td>
            </tr>
            <tr class="total-row">
                <td>Total Price</td>
                <td>$<?php echo number_format($purchase['total_price'], 2); ?></td>
            </tr>
            <tr>
                <td>Purchase Date</td>
                <td><?php echo htmlspecialchars($purchase['purchased_at']); ?></td>
            </tr>
        </table>
        <a href="beats.php" class="btn">Back to Beats</a>
    </div>
</body>
</html>
