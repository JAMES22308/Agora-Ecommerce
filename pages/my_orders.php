<?php

session_start();

require_once "../config/database.php";

$database = new Database();
$conn = $database->connection;

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {

    header("Location: ../public/login.php");
    exit;

}

// Check if user is a buyer
if ($_SESSION["role"] != "buyer") {

    echo "Only buyers can view orders.";
    exit;

}

$buyerId = $_SESSION["user_id"];

// Get the buyer's orders and products
$sql = "SELECT
            orders.order_id,
            orders.order_date,
            products.product_name,
            products.price,
            order_items.quantity
        FROM orders
        JOIN order_items
            ON orders.order_id = order_items.order_id
        JOIN products
            ON order_items.product_id = products.product_id
        WHERE orders.buyer_id = ?
        ORDER BY orders.order_date DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $buyerId);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Orders - Agora</title>

</head>

<body>

    <h1>My Orders</h1>

    <p>
        Buyer: <?= htmlspecialchars($_SESSION["full_name"]) ?>
    </p>

    <hr>

    <?php

    if ($result->num_rows > 0) {

        while ($order = $result->fetch_assoc()) {

            $total = $order["price"] * $order["quantity"];

            echo "<h2>Order #" . $order["order_id"] . "</h2>";

            echo "<p>";
            echo "Date: " . $order["order_date"];
            echo "</p>";

            echo "<p>";
            echo "Product: " . htmlspecialchars($order["product_name"]);
            echo "</p>";

            echo "<p>";
            echo "Price: $" . $order["price"];
            echo "</p>";

            echo "<p>";
            echo "Quantity: " . $order["quantity"];
            echo "</p>";

            echo "<p>";
            echo "Total: $" . $total;
            echo "</p>";

            echo "<hr>";
        }

    } else {

        echo "<p>You have not placed any orders yet.</p>";

    }

    ?>

    <p>
        <a href="products.php">Browse Products</a>
    </p>

    <p>
        <a href="../public/dashboard.php">Back to Dashboard</a>
    </p>

</body>

</html>