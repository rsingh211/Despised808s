<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Destroy the session and redirect to login page
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .logout-container {
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logout-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .logout-container form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .logout-container button {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .confirm {
            background-color: #e63946;
            color: white;
        }

        .cancel {
            background-color: #6c757d;
            color: white;
        }

        .confirm:hover {
            background-color: #c0273c;
        }

        .cancel:hover {
            background-color: #495057;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h1>Are you sure you want to log out?</h1>
        <form method="POST">
            <button type="submit" class="confirm">Yes, Log Out</button>
            <a href="index.php" class="cancel" style="text-decoration: none; padding: 10px 15px; display: inline-block;">Cancel</a>
        </form>
    </div>
</body>
</html>
