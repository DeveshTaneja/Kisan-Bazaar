<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'farmer') {
    header("Location: ../signup.php");
    exit();
}
include "../config/db.php";

$farmer_id = $_SESSION['user_id'];
// $result = $conn->query("SELECT * FROM inventory WHERE farmer_id = $farmer_id");
// $query = "SELECT orders.id, inventory.item_name, orders.quantity, orders.price 
//           FROM orders 
//           JOIN inventory ON orders.inventory_id = inventory.id
//           WHERE orders.farmer_id = ?";

$query = "SELECT orders.*, inventory.img_url, inventory.item_name, inventory.price
          FROM orders
          JOIN inventory ON orders.inventory_id = inventory.id
          WHERE inventory.farmer_id = ? AND orders.status = 'pending'";



$stmt = $conn->prepare($query);
if (!$stmt) {
    die("SQL Error: " . $conn->error);  // Debugging SQL errors
}

$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kisan Bazar</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        #tidio-chat {
            background-image: url('chatbotimage.png');
            background-size: cover;
            width: 50px;
            /* Adjust size */
            height: 50px;
            border-radius: 50%;
            /* Makes it circular */
        }
    </style>
</head>

<body>
    <div class="background-image"></div>
    <nav class="top-nav" id="topnavi">
        <div id="logo">
            <img src="logoKisan.png" alt="Kisan Bazar">
        </div>
        <a href="#contact">About</a>
        <a href="faq.html">FAQ</a>
        <div class="dropdown">
            <a href="#profile" id="profile-icon"><i class="fa-solid fa-user"></i></a>
            <ul class="dropdown-content">
                <li>
                    <div class="translate-container">
                        <div id="google_translate_element"></div>
                    </div>
                </li>
                <li><a href="../farmerProfile/dashboard.php">Profile</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
                <li><a href="#darkMode">Dark Mode</a></li>
            </ul>
        </div>
    </nav>

    <nav class="side-nav">
        <a href="/farmer/select_category.php">Inventory</a>
        <a href="/farmer/orders.php">Orders</a>
        <a href="https://www.myscheme.gov.in/schemes/aif">Govt. Schemes</a>
        <a href="../buyButton/buy.html">Buy</a>
        <a href="index.php">AI Disease Detection</a>
        <a href="../weather-website/index.html">Weather prediction</a>
        <a href="../farmerProfile/dashboard.php">Profile</a>

    </nav>

    <div class="content">
        <div class="hero">
            <!-- <div class="carousel-container">
                <div class="carousel">
                    <img src="scheme1.jpg" alt="Image 1">
                    <img src="scheme2.jpg" alt="Image 2">
                    <img src="scheme3.webp" alt="Image 3">
                    <img src="scheme4.jpg" alt="Image 4">
                    <img src="scheme5.jpg" alt="Image 5">
                </div>
            </div> -->
            <!-- <h2>Connecting Farmers and Consumers</h2> -->
            <div class="slider">
                <div class="slides">
                    <a href="https://cish.icar.gov.in/hindi/event_page.php?a=Launch%20of%20Pradhan%20Mantri%20Kisan%20Maandhan%20Yojana%20(PMKMY)#:~:text=%E0%A4%AA%E0%A5%8D%E0%A4%B0%E0%A4%A7%E0%A4%BE%E0%A4%A8%E0%A4%AE%E0%A4%82%E0%A4%A4%E0%A5%8D%E0%A4%B0%E0%A5%80%20%E0%A4%95%E0%A4%BF%E0%A4%B8%E0%A4%BE%E0%A4%A8%20%E0%A4%AE%E0%A4%BE%E0%A4%A8%E0%A4%A7%E0%A4%A8%20%E0%A4%AF%E0%A5%8B%E0%A4%9C%E0%A4%A8%E0%A4%BE%20(%E0%A4%AA%E0%A5%80%E0%A4%8F%E0%A4%AE%E0%A4%95%E0%A5%87%E0%A4%8F%E0%A4%AE%E0%A4%B5%E0%A4%BE%E0%A4%88)%20%E0%A4%95%E0%A4%BE%20%E0%A4%AA%E0%A5%8D%E0%A4%B0%E0%A4%AE%E0%A5%8B%E0%A4%9A%E0%A4%A8&text=%E0%A4%AF%E0%A4%B9%20%E0%A4%AF%E0%A5%8B%E0%A4%9C%E0%A4%A8%E0%A4%BE%2018%20%E0%A4%B8%E0%A5%87%2040,%E0%A4%89%E0%A4%A8%E0%A4%95%E0%A5%80%20%E0%A4%89%E0%A4%AE%E0%A5%8D%E0%A4%B0%20%E0%A4%AA%E0%A4%B0%20%E0%A4%A8%E0%A4%BF%E0%A4%B0%E0%A5%8D%E0%A4%AD%E0%A4%B0%20%E0%A4%B9%E0%A5%88%E0%A5%A4"
                        target="_blank">
                        <img src="scheme1.jpg" class="images" alt="Scheme 1">
                    </a>
                    <a href="https://pmksy.gov.in/" target="_blank">
                        <img src="scheme2.jpg" class="images" alt="Scheme 2">
                    </a>
                    <a href="https://rkvy.da.gov.in/" target="_blank">
                        <img src="scheme3new2.jpg" class="images" alt="Scheme 3">
                    </a>
                    <a href="https://www.myscheme.gov.in/schemes/aif" target="_blank">
                        <img src="scheme4.jpg" class="images" alt="Scheme 4">
                    </a>
                    <a href="https://www.soilhealth.dac.gov.in/home" target="_blank">
                        <img src="scheme5new2.webp" class="images" alt="Scheme 5">
                    </a>
                </div>
            </div>

            <div class="controls">
                <button class="prev" id="slidebutton"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="next" id="slidebutton"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

        <div class="parent-recieved">
            <div class="recieved" id="newOrders">
                <h1>New Orders</h1>
            </div>
            <?php while ($row = $result->fetch_assoc()): ?>

                <div class="recieved" data-id="<?= $row['id']; ?>">
                    <h2>Order Received</h2>
                    <img src="<?= htmlspecialchars($row['img_url']); ?>" alt="Item Image" class="item-image">
                    <p>Item: <?= htmlspecialchars($row['item_name']); ?></p>
                    <p>Quantity: <?= htmlspecialchars($row['quantity']); ?> kg</p>
                    <p>Price: ₹<?= htmlspecialchars($row['price']); ?></p>
                    <p>Duration: 1 day</p>
                    <div class="button-container">
                        <button class="accept" data-id="<?= $row['id']; ?>">Accept</button>
                        <button class="reject" data-id="<?= $row['id']; ?>">Reject</button>
                    </div>
                </div>



            <?php endwhile; ?>
           
            <div class="recieved" id="AllOrders"><a href="orders.php">Check all orders</a></div>
            <div id="acceptedOrdersPage" style="display:none;">
            </div>
        </div>



        <section id="contact">
            <h2 class="section-title">Contact Us</h2>
            <p id="contactinfo">Email: info@kisanbazar.com | Phone: +91 98765 43210</p>
        </section>

        <footer>

            <p>&copy; 2025 Kisan Bazar. All rights reserved.</p>
        </footer>

        <script src="app.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="//code.tidio.co/620t1rxpqz5xjxrzhji9czytijbzftcu.js" async></script>
        <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({
                    pageLanguage: 'en',
                    includedLanguages: 'hi,en,gu,kn,ml,mr,pa,ta,te,bn,ur', // Hindi, Gujarati, Kannada, Malayalam, Marathi, Punjabi, Tamil, Telugu, Bengali, Urdu
                    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
                }, 'google_translate_element');

                document.querySelectorAll(".accept, .reject").forEach(button => {
                    button.addEventListener("click", function () {
                        let orderId = this.getAttribute("data-id");
                        let status = this.classList.contains("accept") ? "ongoing" : "rejected";

                        fetch("update_order_status.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: `order_id=${orderId}&status=${status}`
                        })
                            .then(response => response.text())
                            .then(data => {
                                console.log("Server response:", data); // ✅ Log response for debugging

                                if (data.trim() === "success") {
                                    alert(`Order ${status}`);

                                    // ✅ Remove the card from UI
                                    let orderCard = document.querySelector(`.recieved[data-id='${orderId}']`);
                                    if (orderCard) {
                                        orderCard.remove();
                                    }
                                } else {
                                    alert("Error updating order: " + data); // ✅ Log actual error message
                                }
                            })
                            .catch(error => {
                                console.error("Fetch error:", error);
                                alert("Network error: " + error.message); // ✅ Show detailed error
                            });
                    });
                });
            }
        </script>
        <script type="text/javascript"
            src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>