<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'consumer') {
    header("Location: ../login.php");
    exit();
}
include "../config/db.php";

$query = "SELECT orders.*, inventory.img_url, inventory.item_name, inventory.price
          FROM orders
          JOIN inventory ON orders.inventory_id = inventory.id
          WHERE orders.farmer_id = ? AND orders.admin_assigned = 1 AND orders.status = 'pending'";?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kisan Bazaar</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" 
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" 
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        /* Global Styles */
        body {
            font-family:Arial, Helvetica, sans-serif, 
            
            
        }
        .farmer-img {
            max-width: 50%; /* Adjust percentage to control size */
            max-height: 200px; /* Set maximum height */
            margin: 0 auto; /* Center images */
            display: block; /* Ensure proper alignment */
}

        /* Navbar */
        .navbar {
            background-color: #4CAF50;
            margin-bottom:100px;
            
        }
        /* 3-Liner Menu Icon (Not Inside Navbar) */
        .menu-icon {
            position: fixed;
            top: 60px;
            left: 0;
            font-size: 30px;
            cursor: pointer;
            color: white;
            background: #4CAF50;
            padding: 10px;
            border-radius: 5px;
            z-index: 1001;
        }

        /* Sidebar Menu (Slides in from Left) */
        .sidebar-menu {
            position: fixed;
            top: 50px;
            left: -250px;
            width: 250px;
            height: 100vh;
            background: #3A9142;
            color: white;
            padding-top: 60px;
            transition: 0.4s;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        /* Hero Section - Center GIF */
.hero {
    text-align: center;
    padding: 50px 0;
    background-color: #eaf5e3; 
    margin-top: 40px;/* Light green background */
}

/* Centered GIF */
.hero img {
    width: 80%; /* Adjust width to make it proportional */
    max-height: 400px; /* Sets a good height */
    border-radius: 15px; /* Rounded corners */
    display: block;
    margin: auto; /* Center the GIF */
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1); /* Adds a shadow effect */
}
        
        .hero {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }
        /* Video Frame Styling */
.video-frame {
    width: 400px;
    height: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    overflow: hidden;
    background-color: #f8f8f8;
}

/* GIF Image (Make it Fit) */
.video-frame img {
    width: 100%;
    height: auto;
}

        
        

        
        .sidebar-menu a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            transition: 0.3s;
        }

        .sidebar-menu a:hover {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            transform: scale(1.05);
        }
        

.reviews,
.contactus{
    margin-top: 30px;
    background: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 10px;
}
        

        /* Close Button */
        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 25px;
            color: white;
            cursor: pointer;
        }
        /* Hero Section - Full Width */
.hero {
    text-align: center;
    padding: 50px 0;
    background-color: #d0f6b9; /* Light green background */
}

/* Main heading */
.hero h1 {
    font-size: 2.8rem; /* Adjust font size */
    font-weight: bold;
}

/* Subheading */
.hero p {
    font-size: 1.2rem;
    margin-bottom: 20px;
}

/* Centered GIF */
.hero .gif-container {
    position: relative;
    width: 85%;  /* Adjust width */
    max-width: 900px; /* Limit max size */
    margin: auto;
    border-radius: 20px; /* Rounded corners */
    overflow: hidden; /* Keeps edges neat */
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
}

/* Ensure GIF fits properly */
.hero .gif-container img {
    width: 100%;
    height: auto;
    display: block;
}
/* Hero Section Styling */
.hero {
    padding: 50px 0;
    background-color: #f8fff4; /* Light green background for a soft look */
}

/* Title Styling */
.hero h1 {
    font-size: 3rem;
    font-weight: bold;
    color: black;
}

/* Subtitle Styling */
.hero p {
    font-size: 1.5rem;
    color: black;
}

/* Button Styling */
.btn-outline-success {
    border: 2px solid green;  
    color: green;  
    font-size: 1.25rem;  
    padding: 10px 20px;  
    border-radius: 5px;  
    margin: 10px 0;  
}

/* On Hover Effect */
.btn-outline-success:hover {
    background-color: green;  
    color: white;  
}

/* GIF Container Styling */
.gif-container {
    width: 80%; /* Makes it responsive */
    max-width: 900px; /* Ensures it doesn't get too big */
    margin: 30px auto; /* Centers it */
    border-radius: 15px; /* Rounded edges */
    overflow: hidden; /* Ensures no border leaking */
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); /* Nice soft shadow */
}/* Hero Section Styling */
.hero {
    padding: 50px 0;
    background-color: #f8fff4; /* Light green background */
}

/* Title Styling */
.hero h1 {
    font-size: 3rem;
    font-weight: bold;
    color: black;
}

/* Subtitle Styling */
.hero p {
    font-size: 1.5rem;
    color: black;
}

/* GIF Container with Positioning */
.gif-container {
    position: relative; /* Enables absolute positioning for button */
    width: 80%; /* Responsive width */
    max-width: 900px; /* Limits max size */
    margin: 30px auto; /* Centers it */
    border-radius: 15px; /* Rounded edges */
    overflow: hidden; /* Makes sure GIF stays within border */
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); /* Soft shadow */
}

/* GIF Image Responsive Styling */
.gif-container img {
    width: 100%;  /* Makes GIF responsive */
    display: block;
    object-fit: cover;
}

/* Shop Now Button Styling (On GIF) */
.shop-button {
    position: absolute;
    top: 75%; /* Center button */
    left: 50%;
    transform: translate(-50%, -50%); /* Perfect centering */
    background-color: rgb(227, 236, 227); /* Green background */
    color: rgb(44, 176, 99); /* White text */
    font-size: 1.25rem;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s ease-in-out;
}

/* Hover effect */
.shop-button:hover {
    background-color: rgba(247, 242, 242, 0.065); /* White background */
    color: rgb(118, 126, 118); /* Green text */
    border: 2px solid green;
}

/* Ensures GIF fits inside the container */
.gif-container img {
    width: 100%;  
    display: block;  
    object-fit: cover;  
}

/* Position buttons inside GIF */
.hero .gif-buttons {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    gap: 20px; /* Space between buttons */
}/* GIF Container Styling */
.gif-container {
    width: 80%; /* Adjusted width to fit the screen */
    max-width: 900px; /* Maximum size for responsiveness */
    margin: 20px auto; /* Centers it horizontally */
    border-radius: 20px; /* Rounded edges like in your reference */
    overflow: hidden; /* Ensures the rounded edges apply */
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); /* Optional shadow for depth */
}

/* GIF Image Styling */
.gif-container img {
    width: 100%; /* Ensures it fills the container */
    display: block; /* Removes unwanted spacing */
    object-fit: cover; /* Makes it look proportionate */
}

        /* Carousel */
        .carousel-item img {
            width: 50%;
            max-height: 250px;
            border-radius: 10px;
        }

        /* Card Section */
        .product-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .contact-section {
            background: url('https://source.unsplash.com/1600x900/?field,grass') center/cover;
            padding: 40px 0;
            text-align: center;
            color: black;
        }

        .contact-section i {
            font-size: 24px;
            margin-right: 10px;

        }
        /* Dropdown Menu - Styled to Look Beautiful */
        .dropdown-menu-custom {
            position: absolute;
            top: 56px;
            left: 0;
            width: 100%;
            background: #3A9142;
            color: white;
            padding: 100px 0;
            display: none;
            text-align: center;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
            animation: ease-in-out 0.3s;
            border-radius: 0 0 10px 10px;
        }

        .dropdown-menu-custom a {
            display: block;
            color: white;
            padding: 12px;
            text-decoration: none;
            font-size: 18px;
            transition: all 0.3s;
        }

        .dropdown-menu-custom a:hover {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            transform: scale(1.05);
        }

        /* Dropdown Animation */
        @keyframes dropdownSlide {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes dropdownSlide {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        /* Footer */
        footer {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>
    <!-- 3-Liner Menu Button (Independent, Not Inside Navbar) -->
    <span class="menu-icon" onclick="toggleSidebar()">&#9776;</span>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand text-white" href="#">
                <img src="kisanlogo.png" alt="Kisan Bazaar Logo" width="40"> Kisan Bazaar
            </a>

            <div class="ms-auto">
                <a class="text-white mx-3" href="#contact">Contact</a>
                <a class="text-white mx-3" href="faqConsumers.html">FAQ</a>
                <a class="text-white mx-3" href="#testimonials">Testimonials</a>
                <a class="text-white mx-3" href="cart.php">
                <i class="bi bi-cart-fill"></i>
            </a>

                <a class="text-white" href="../consumerProfile/consumer_profile.php"><i class="bi bi-person-circle"></i></a>
            </div>
        </div>
    </nav>
    <!-- Sidebar Menu -->
    <div id="sidebar-menu" class="sidebar-menu">
        <span class="close-btn" onclick="toggleSidebar()">&times;</span>
        <a href="consumer_categories.php">Shop Now</a>
        <a href="consumer_order.php">Orders</a>
        <!-- <a href="#">Contact Us</a> -->
        <a href="../consumerProfile/consumer_profile.php">Profile</a>
        <a href="../auth/logout.php">Log Out</a>
    </div>
    
    <!-- Hero Section -->
<header class="hero text-center">
    <div class="container">
        <!-- Heading -->
        <h1 class="display-4 fw-bold">Kisan Bazaar</h1>
        
        <!-- Subheading -->
        <p class="lead">Empowering Farmers & Buyers</p>

        <!-- GIF Container with Shop Now Button -->
        <div class="gif-container">
            <img src="https://corporate.walmart.com/content/dam/corporate/images/newsroom/business/20161003/the-grocery-list-why-140-million-americans-choose-walmart/customers-in-produce.gif" 
                 alt="Farming GIF">
            <!-- Shop Now Button (Inside GIF) -->
            <a href="consumer_categories.php" class="btn btn-success btn-lg shop-button">Shop Now</a>
        </div>
    </div>
</header>
    
        

    <!-- Farmer Carousel -->
    <section id="about" class="container my-5 text-center">
        <h2>Welcome Consumer</h2>
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active"data-bs-interval="1700">
                    <img src="consumer.png" >
                </div>
                <div class="carousel-item"data-bs-interval="1700">
                    <img src="consumer0.png ">
                </div>
                    <div class="carousel-item"data-bs-interval="1700">
                        <img src="consumer1.png" >
                    </div>
                </div>
                
            
            <button class="carousel-control-prev" type="button" 
                    data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" 
                    data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>
    <div id="Testimonials">

       <!-- Testimonials Section -->
<section id="testimonials" class="my-5">
    <div class="container reviews">
        <h2 class="text-center">What Our Consumers Say</h2>
        
        <!-- Testimonial Carousel -->
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

                <!-- Testimonial 1 -->
                <div class="carousel-item active">
                    <div class="card p-4 text-center product-card">
                       
                        <h5 class="card-title">Amit Sharma</h5>
                        <p class="card-text">"Buying fresh produce has never been easier. Best prices and great quality!"</p>
                        <p class="text-warning">⭐⭐⭐⭐⭐</p>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="carousel-item">
                    <div class="card p-4 text-center product-card">
                       
                        <h5 class="card-title">Priya Verma</h5>
                        <p class="card-text">"I love the convenience of getting organic vegetables straight from farmers."</p>
                        <p class="text-warning">⭐⭐⭐⭐⭐</p>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="carousel-item">
                    <div class="card p-4 text-center product-card">
        
                        <h5 class="card-title">Rahul Desai</h5>
                        <p class="card-text">"Fair-trade system ensures that farmers get their due share. Amazing initiative!"</p>
                        <p class="text-warning">⭐⭐⭐⭐⭐</p>
                    </div>
                </div>

            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>
    </div>
</section>
        </div>
    </div>
    </section>

    <!-- Contact Us Section -->
<section id="contact" class="my-5">
    <div class="container">
        <h2 class="text-center">Contact Us</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4 product-card">
                    <div class="card-body text-center">
                        <p><i class="bi bi-telephone-fill"></i> <strong>Phone:</strong> +91 9868375012</p>
                        <p><i class="bi bi-envelope-fill"></i> <strong>Email:</strong> kisanbazaar@gmail.com</p>
                        <p><i class="bi bi-geo-alt-fill"></i> <strong>Location:</strong> Delhi, India</p>
                        <button class="btn btn-success">Send Email</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Kisan Bazaar. All rights reserved.</p>
    </footer>

   <!-- JavaScript -->
    <script>
        function toggleSidebar() {
            let sidebar = document.getElementById("sidebar-menu");
            if (sidebar.style.left === "-250px") {
                sidebar.style.left = "0";
            } else {
                sidebar.style.left = "-250px";
            }
        }
        var tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        var tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//code.tidio.co/620t1rxpqz5xjxrzhji9czytijbzftcu.js" async></script>


</body>
</html>