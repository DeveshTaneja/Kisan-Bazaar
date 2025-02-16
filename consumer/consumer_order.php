<?php
session_start();
include "../config/db.php"; // Database Connection

// Redirect user if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch ongoing orders (pending, processing, shipped)
$queryOngoing = "SELECT orders.id, orders.item_name, orders.quantity, orders.price, orders.created_at, orders.status, users.name AS farmer_name
                 FROM orders 
                 JOIN users ON orders.farmer_id = users.id
                 WHERE orders.consumer_id = ? AND orders.status IN ('pending', 'processing', 'shipped')
                 ORDER BY orders.created_at DESC";

$stmtOngoing = $conn->prepare($queryOngoing);
$stmtOngoing->bind_param("i", $user_id);
$stmtOngoing->execute();
$resultOngoing = $stmtOngoing->get_result();

// Fetch completed orders (status: done)
$queryCompleted = "SELECT orders.id, orders.item_name, orders.quantity, orders.price, orders.created_at, orders.status, users.name AS farmer_name
                   FROM orders 
                   JOIN users ON orders.farmer_id = users.id
                   WHERE orders.consumer_id = ? AND orders.status = 'done'
                   ORDER BY orders.created_at DESC";

$stmtCompleted = $conn->prepare($queryCompleted);
$stmtCompleted->bind_param("i", $user_id);
$stmtCompleted->execute();
$resultCompleted = $stmtCompleted->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 900px; margin-top: 20px; }
        .section-title {
            font-size: 22px; font-weight: bold; margin-bottom: 15px; color: #007bff;
        }
        .order-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 12px;
            transition: transform 0.3s;
        }
        .order-card:hover { transform: translateY(-3px); }
        .status-badge {
            font-size: 14px;
            padding: 5px 10px;
            font-weight: bold;
            border-radius: 12px;
        }
        .status-pending { background-color: #f0ad4e; color: white; }
        .status-processing { background-color: #5bc0de; color: white; }
        .status-shipped { background-color: #0275d8; color: white; }
        .status-done { background-color: #5cb85c; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center text-primary">My Orders</h2>

    <!-- Ongoing Orders -->
    <div class="mt-4">
        <h3 class="section-title"><i class="fas fa-truck-moving"></i> Ongoing Orders</h3>
        <?php if ($resultOngoing->num_rows > 0): ?>
            <?php while ($row = $resultOngoing->fetch_assoc()): ?>
                <div class="order-card">
                    <div class="d-flex justify-content-between">
                        <h5><?= htmlspecialchars($row['item_name']); ?></h5>
                        <span class="status-badge <?= 'status-' . strtolower($row['status']); ?>">
                            <?= ucfirst($row['status']); ?>
                        </span>
                    </div>
                    <p><strong>Price:</strong> ₹<?= number_format($row['price'], 2); ?></p>
                    <p><strong>Quantity:</strong> <?= htmlspecialchars($row['quantity']); ?> kg</p>
                    <p><strong>Order Date:</strong> <?= date('d M, Y', strtotime($row['created_at'])); ?></p>
                    <p><strong>Farmer:</strong> <?= htmlspecialchars($row['farmer_name']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No ongoing orders.</p>
        <?php endif; ?>
    </div>

    <!-- Completed Orders -->
    <div class="mt-4">
        <h3 class="section-title"><i class="fas fa-check-circle"></i> Completed Orders</h3>
        <?php if ($resultCompleted->num_rows > 0): ?>
            <?php while ($row = $resultCompleted->fetch_assoc()): ?>
                <div class="order-card">
                    <div class="d-flex justify-content-between">
                        <h5><?= htmlspecialchars($row['item_name']); ?></h5>
                        <span class="status-badge status-done">Completed</span>
                    </div>
                    <p><strong>Price:</strong> ₹<?= number_format($row['price'], 2); ?></p>
                    <p><strong>Quantity:</strong> <?= htmlspecialchars($row['quantity']); ?> kg</p>
                    <p><strong>Order Date:</strong> <?= date('d M, Y', strtotime($row['created_at'])); ?></p>
                    <p><strong>Farmer:</strong> <?= htmlspecialchars($row['farmer_name']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No completed orders.</p>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<div class="translate-container">
        <div id="google_translate_element"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'hi,en,gu,kn,ml,mr,pa,ta,te,bn,ur', // Hindi, Gujarati, Kannada, Malayalam, Marathi, Punjabi, Tamil, Telugu, Bengali, Urdu
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>