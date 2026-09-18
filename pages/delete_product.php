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

// Check if user is a seller
if ($_SESSION["role"] != "seller") {

    echo "Only sellers can delete products.";
    exit;

}

$productId = $_GET["id"];
$sellerId = $_SESSION["user_id"];

$sql = "DELETE FROM products
        WHERE product_id = ?
        AND seller_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ii", $productId, $sellerId);

if ($stmt->execute()) {

    header("Location: my_products.php");
    exit;

} else {

    echo "Error deleting product.";

}

$stmt->close();

?>