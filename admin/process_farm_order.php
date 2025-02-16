<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Unauthorized access. Please log in as an admin.'); window.location.href='admin_login.php';</script>";
    exit;
}

// ✅ Validate POST data
if (!isset($_POST['inventory_id'], $_POST['farmer_id'], $_POST['order_quantity'])) {
    echo "<script>alert('Invalid request. Missing details.'); window.location.href='admin_inventory.php';</script>";
    exit;
}

$inventory_id = intval($_POST['inventory_id']);
$farmer_id = intval($_POST['farmer_id']);
$order_quantity = intval($_POST['order_quantity']);

// ✅ Fetch Inventory Details
$query = "SELECT item_name, price, quantity FROM inventory WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $inventory_id);
$stmt->execute();
$inventory = $stmt->get_result()->fetch_assoc();

if (!$inventory) {
    echo "<script>alert('Inventory item not found!'); window.location.href='admin_inventory.php';</script>";
    exit;
}

$item_name = htmlspecialchars($inventory['item_name']);
$price_per_unit = $inventory['price'];
$total_price = $order_quantity * $price_per_unit;

if ($order_quantity > $inventory['quantity']) {
    echo "<script>alert('Not enough stock available!'); window.history.back();</script>";
    exit;
}

// ✅ Insert Order into `orders` Table (Farmer Orders from Admin)
$query = "INSERT INTO orders (farmer_id, admin_assigned, inventory_id, item_name, quantity, price, status, created_at) 
          VALUES (?, 1, ?, ?, ?, ?, 'pending', NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param("iisid", $farmer_id, $inventory_id, $item_name, $order_quantity, $total_price);

if ($stmt->execute()) {
    echo "<script>alert('Order placed successfully with the farmer!'); window.location.href='admin_dashboard.php';</script>";
} else {
    echo "<script>alert('Error placing the order.'); window.history.back();</script>";
}
?>