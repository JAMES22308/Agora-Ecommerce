<?php

session_start();

require_once "../config/database.php";

$database = new Database();
$conn = $database->connection;

$message = "";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {

    header("Location: ../public/login.php");
    exit;

}

// Check if user is a seller
if ($_SESSION["role"] != "seller") {

    echo "Only sellers can add products.";
    exit;

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $productName = $_POST["product_name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    $sellerId = $_SESSION["user_id"];

    $sql = "INSERT INTO products
            (seller_id, product_name, description, price, quantity)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "issdi",
        $sellerId,
        $productName,
        $description,
        $price,
        $quantity
    );

    if ($stmt->execute()) {

        $message = "Product added successfully!";

    } else {

        $message = "Error adding product.";

    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Product - Agora</title>

</head>

<body>

    <h1>Add Product</h1>

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
            required
        >

        <br><br>

        <label>Description:</label>

        <textarea
            name="description"
            required
        ></textarea>

        <br><br>

        <label>Price:</label>

        <input
            type="number"
            name="price"
            step="0.01"
            required
        >

        <br><br>

        <label>Quantity:</label>

        <input
            type="number"
            name="quantity"
            required
        >

        <br><br>

        <button type="submit">
            Add Product
        </button>

    </form>

    <br>

    <a href="../public/dashboard.php">
        Back to Dashboard
    </a>

</body>

</html>