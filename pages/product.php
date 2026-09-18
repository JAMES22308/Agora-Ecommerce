<?php

session_start();

require_once "../config/database.php";

$database = new Database();
$conn = $database->connection;

$productId = $_GET["id"];

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

?>

<!DOCTYPE html>
<html>

<head>

    <title><?= htmlspecialchars($product["product_name"]) ?> - Agora</title>

</head>

<body>

    <h1><?= htmlspecialchars($product["product_name"]) ?></h1>

    <p>
        <?= htmlspecialchars($product["description"]) ?>
    </p>

    <p>
        Price: $<?= $product["price"] ?>
    </p>

    <p>
        Available: <?= $product["quantity"] ?>
    </p>

    <hr>

    <?php

    if (isset($_SESSION["role"]) && $_SESSION["role"] == "buyer") {

        ?>

        <h2>Purchase Product</h2>

        <form method="POST" action="purchase.php">

            <input
                type="hidden"
                name="product_id"
                value="<?= $product["product_id"] ?>"
            >

            <label>Quantity:</label>

            <input
                type="number"
                name="quantity"
                min="1"
                max="<?= $product["quantity"] ?>"
                required
            >

            <br><br>

            <button type="submit">
                Purchase
            </button>

        </form>

        <?php

    } else {

        echo "<p>Log in as a buyer to purchase this product.</p>";

    }

    ?>

    <br>

    <a href="products.php">
        Back to Products
    </a>

</body>

</html>