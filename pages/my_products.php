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

    echo "Only sellers can manage products.";
    exit;

}

$sellerId = $_SESSION["user_id"];

$sql = "SELECT * FROM products WHERE seller_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $sellerId);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Products - Agora</title>

</head>

<body>

    <h1>My Products</h1>

    <p>
        Seller: <?= $_SESSION["full_name"] ?>
    </p>

    <a href="add_product.php">Add New Product</a>

    <hr>

    <?php

    if ($result->num_rows > 0) {

        while ($product = $result->fetch_assoc()) {

            echo "<h2>";
            echo htmlspecialchars($product["product_name"]);
            echo "</h2>";

            echo "<p>";
            echo htmlspecialchars($product["description"]);
            echo "</p>";

            echo "<p>";
            echo "Price: $" . $product["price"];
            echo "</p>";

            echo "<p>";
            echo "Quantity: " . $product["quantity"];
            echo "</p>";

            echo "<a href='edit_product.php?id=" . $product["product_id"] . "'>";
            echo "Edit";
            echo "</a>";

            echo " | ";

            echo "<a href='delete_product.php?id=" . $product["product_id"] . "'>";
            echo "Delete";
            echo "</a>";

            echo "<hr>";
        }

    } else {

        echo "<p>You have not added any products yet.</p>";

    }

    ?>

    <a href="../public/dashboard.php">
        Back to Dashboard
    </a>

</body>

</html>