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

    echo "Only sellers can edit products.";
    exit;

}

$productId = $_GET["id"];
$sellerId = $_SESSION["user_id"];

$message = "";

// Get the product
$sql = "SELECT * FROM products
        WHERE product_id = ? AND seller_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ii", $productId, $sellerId);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {

    $product = $result->fetch_assoc();

} else {

    echo "Product not found.";
    exit;

}

$stmt->close();


// Update the product
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $productName = $_POST["product_name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    $sql = "UPDATE products
            SET product_name = ?,
                description = ?,
                price = ?,
                quantity = ?
            WHERE product_id = ?
            AND seller_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssdiii",
        $productName,
        $description,
        $price,
        $quantity,
        $productId,
        $sellerId
    );

    if ($stmt->execute()) {

        $message = "Product updated successfully!";

        $product["product_name"] = $productName;
        $product["description"] = $description;
        $product["price"] = $price;
        $product["quantity"] = $quantity;

    } else {

        $message = "Error updating product.";

    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Product - Agora</title>

</head>

<body>

    <h1>Edit Product</h1>

    <?php

    if ($message != "") {

        echo "<p>" . $message . "</p>";

    }

    ?>

    <form method="POST">

        <label>Product Name:</label>

        <input
            type="text"
            name="product_name"
            value="<?= htmlspecialchars($product["product_name"]) ?>"
            required
        >

        <br><br>

        <label>Description:</label>

        <textarea
            name="description"
            required
        ><?= htmlspecialchars($product["description"]) ?></textarea>

        <br><br>

        <label>Price:</label>

        <input
            type="number"
            name="price"
            step="0.01"
            value="<?= $product["price"] ?>"
            required
        >

        <br><br>

        <label>Quantity:</label>

        <input
            type="number"
            name="quantity"
            value="<?= $product["quantity"] ?>"
            required
        >

        <br><br>

        <button type="submit">
            Update Product
        </button>

    </form>

    <br>

    <a href="my_products.php">
        Back to My Products
    </a>

</body>

</html>