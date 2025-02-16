<?php
session_start();
include "../config/db.php"; // Database Connection

// Redirect user if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch Farmer Details
$queryUser = "SELECT name FROM users WHERE id = ?";
$stmtUser = $conn->prepare($queryUser);
$stmtUser->bind_param("i", $user_id);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$user = $resultUser->fetch_assoc();
$username = $user['name'] ?? "Farmer";

// Fetch Transaction History (Completed Orders)
$queryTransactions = "SELECT orders.item_name, orders.quantity, orders.price, orders.created_at, users.name AS retailer_name
                      FROM orders 
                      JOIN users ON orders.consumer_id = users.id 
                      WHERE orders.farmer_id = ? AND orders.status = 'done'
                      ORDER BY orders.created_at DESC";

$stmtTransactions = $conn->prepare($queryTransactions);
$stmtTransactions->bind_param("i", $user_id);
$stmtTransactions->execute();
$resultTransactions = $stmtTransactions->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .transaction-card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .transaction-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        .transaction-details { flex-grow: 1; padding-left: 15px; }
        .info-icon, .call-icon { font-size: 20px; cursor: pointer; }
        .transaction-title { color: #4A90E2; font-weight: bold; }
    </style>
</head>
<body>

    <div class="container">
        <aside class="sidebar">
            <h2><?= htmlspecialchars($username); ?></h2>
            <ul>
                <li onclick="window.location.href='../auth/logout.php'"><i class="fas fa-sign-out-alt"></i> Logout</li>
                <li onclick="window.location.href='change_password.php'"><i class="fas fa-key"></i> Change Password</li>
                <li class="active"><i class="fas fa-history"></i> Transaction History</li>
                <li onclick="loadPage('income')"><i class="fas fa-chart-line"></i> Monthly Income</li>
            </ul>
        </aside>

        <main class="content">
            <h2>Transaction History</h2>

            <?php if ($resultTransactions->num_rows > 0): ?>
                <?php while ($row = $resultTransactions->fetch_assoc()): ?>
                    <div class="transaction-card">
                        <i class="fas fa-info-circle info-icon"></i>
                        <div class="transaction-details">
                            <h3 class="transaction-title"><?= htmlspecialchars($row['item_name']); ?></h3>
                            <p><strong>Price:</strong> ₹<?= number_format($row['price'], 2); ?></p>
                            <p><strong>Quantity:</strong> <?= htmlspecialchars($row['quantity']); ?> kg</p>
                            <p><strong>Delivery Date:</strong> <?= date('d M, Y', strtotime($row['created_at'])); ?></p>
                            <p><strong>Retailer:</strong> <?= htmlspecialchars($row['retailer_name']); ?></p>
                        </div>
                        <i class="fas fa-phone call-icon"></i>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-muted">No completed transactions yet.</p>
            <?php endif; ?>
        </main>
    </div>

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