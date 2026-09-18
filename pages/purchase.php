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

    echo "Only buyers can purchase products.";
    exit;

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $productId = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    $buyerId = $_SESSION["user_id"];

    // Find the product
    $sql = "SELECT * FROM products
            WHERE product_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $productId);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $product = $result->fetch_assoc();

    } else {

        echo "Product not found.";
        exit;

    }

    $stmt->close();


    // Check if there is enough stock
    if ($quantity > $product["quantity"]) {

        echo "Not enough products available.";
        exit;

    }


    // Create the order
    $sql = "INSERT INTO orders (buyer_id)
            VALUES (?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $buyerId);

    $stmt->execute();

    $orderId = $conn->insert_id;

    $stmt->close();


    // Create the order item
    $sql = "INSERT INTO order_items
            (order_id, product_id, quantity)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iii",
        $orderId,
        $productId,
        $quantity
    );

    $stmt->execute();

    $stmt->close();


    // Reduce the product quantity
    $newQuantity = $product["quantity"] - $quantity;

    $sql = "UPDATE products
            SET quantity = ?
            WHERE product_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ii",
        $newQuantity,
        $productId
    );

    $stmt->execute();

    $stmt->close();

    echo "<h1>Purchase Successful!</h1>";

    echo "<p>";
    echo "You purchased " . $quantity . " ";
    echo htmlspecialchars($product["product_name"]);
    echo ".</p>";

    echo "<p>";
    echo "Order number: " . $orderId;
    echo "</p>";

    echo "<a href='products.php'>";
    echo "Back to Products";
    echo "</a>";

}

?>