<?php
$title = "Beats";
include 'header.php'; // Include header for navigation and layout
include 'db.php'; // Include database connection

// Handle search and filter
$search = $_GET['search'] ?? '';
$filter = $_GET['type'] ?? '';

// Base query
$query = "SELECT * FROM beats WHERE 1=1";

// Add search condition
if (!empty($search)) {
    $query .= " AND beat_name LIKE :search";
}

// Add filter condition
if (!empty($filter)) {
    $query .= " AND type = :type";
}

// Prepare and execute the query
$stmt = $pdo->prepare($query);

if (!empty($search)) {
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
if (!empty($filter)) {
    $stmt->bindValue(':type', $filter, PDO::PARAM_STR);
}

$stmt->execute();
$beats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #495057;
        }

        /* Search and Filter */
        form {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            gap: 10px;
        }

        input[type="text"], select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            width: 250px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Beats Section */
        .beats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .beat-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .beat-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .beat-img {
            width: 100%;
            max-width: 200px;
            height: auto;
            margin: 0 auto 15px;
            border-radius: 10px;
        }

        .beat-title {
            font-size: 1.4rem;
            font-weight: bold;
            margin: 10px 0;
            color: #343a40;
        }

        .beat-type, .beat-rate {
            font-size: 1.2rem;
            color: #555;
            margin: 5px 0;
        }

        .beat-rate {
            color: #e63946;
            font-weight: bold;
        }

        .beat-audio {
            margin: 15px 0;
            width: 100%;
            height: 40px;
            border: none;
        }

        .buy-btn {
            background: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        .buy-btn:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .beats-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }

            input[type="text"], select {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Beats Store</h1>

        <!-- Search and Filter Form -->
        <form method="GET">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                   placeholder="Search by Beat Name">
            <select name="type">
                <option value="">All Types</option>
                <option value="Hip Hop" <?php if ($filter == 'Hip Hop') echo 'selected'; ?>>Hip Hop</option>
                <option value="Romantic" <?php if ($filter == 'Romantic') echo 'selected'; ?>>Romantic</option>
                <option value="Sad" <?php if ($filter == 'Sad') echo 'selected'; ?>>Sad</option>
            </select>
            <button type="submit">Search</button>
        </form>

        <!-- Beats Grid -->
        <div class="beats-grid">
            <?php if (!empty($beats)): ?>
                <?php foreach ($beats as $beat): ?>
                    <div class="beat-card">
                        <img src="<?php echo htmlspecialchars($beat['image_path']); ?>" alt="Beat Image" class="beat-img">
                        <h3 class="beat-title"><?php echo htmlspecialchars($beat['beat_name']); ?></h3>
                        <p class="beat-type">Type: <?php echo htmlspecialchars($beat['type']); ?></p>
                        <p class="beat-rate">Price: $<?php echo number_format($beat['rate'], 2); ?></p>
                        <audio controls class="beat-audio">
                            <source src="<?php echo htmlspecialchars($beat['audio_path']); ?>" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                        <button class="buy-btn" onclick="window.location.href='purchase.php?beat_id=<?php echo $beat['beat_id']; ?>'">
                            Buy Now
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No beats found. Try adjusting your search or filter.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
