
<?php

session_start();

require_once "../config/database.php";

$database = new Database();
$conn = $database->connection;


/* Check if user is logged in */

if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit;
}


/* Only Agora Master Admin can access this page */

if ($_SESSION["role"] != "agora_admin") {
    echo "Only the Agora Master Admin can access this page.";
    exit;
}


/* Count businesses */

$sql = "SELECT COUNT(*) AS total_businesses
        FROM businesses";

$result = $conn->query($sql);

$businessData = $result->fetch_assoc();

$totalBusinesses = $businessData["total_businesses"];


/* Count users */

$sql = "SELECT COUNT(*) AS total_users
        FROM users";

$result = $conn->query($sql);

$userData = $result->fetch_assoc();

$totalUsers = $userData["total_users"];


/* Count products */

$sql = "SELECT COUNT(*) AS total_products
        FROM products";

$result = $conn->query($sql);

$productData = $result->fetch_assoc();

$totalProducts = $productData["total_products"];


/* Count orders */

$sql = "SELECT COUNT(*) AS total_orders
        FROM orders";

$result = $conn->query($sql);

$orderData = $result->fetch_assoc();

$totalOrders = $orderData["total_orders"];

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Agora Admin - Dashboard</title>

    <link rel="stylesheet" href="../public/dashboard.css">

</head>

<body>

<header>

    <h1>Agora</h1>

    <p>Master Administration</p>

</header>


<main>

    <section class="welcome">

        <h2>Agora Master Admin</h2>

        <p>
            Welcome back, <?= htmlspecialchars($_SESSION["full_name"]) ?>.
        </p>

        <p>
            Manage and monitor the Agora marketplace.
        </p>

    </section>


    <section class="role-box">

        <h2>Marketplace Overview</h2>

        <div class="options">

            <div class="option">
                Businesses: <?= $totalBusinesses ?>
            </div>

            <div class="option">
                Users: <?= $totalUsers ?>
            </div>

            <div class="option">
                Products: <?= $totalProducts ?>
            </div>

            <div class="option">
                Orders: <?= $totalOrders ?>
            </div>

        </div>


        <h2>Admin Options</h2>

        <div class="options">

            <a class="option" href="admin_users.php">
                View All Users
            </a>

            <a class="option" href="admin_businesses.php">
                View All Businesses
            </a>

            <a class="option" href="admin_products.php">
                View All Products
            </a>

        </div>

    </section>


    <div class="bottom-links">

        <a href="../public/dashboard.php">
            Back to Dashboard
        </a>

        <a href="../public/logout.php">
            Logout
        </a>

    </div>

</main>

</body>

</html>

