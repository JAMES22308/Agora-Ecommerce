<?php

require_once "../config/database.php";

$database = new Database();
$conn = $database->connection;

$sql = "SELECT * FROM products";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Products - Agora</title>

</head>

<body>

    <h1>Agora Products</h1>

    <p>
        <a href="../public/index.php">Home</a>
    </p>

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
            echo "Available: " . $product["quantity"];
            echo "</p>";

            echo "<a href='product.php?id=" . $product["product_id"] . "'>";
            echo "View Product";
            echo "</a>";

            echo "<hr>";
        }

    } else {

        echo "<p>No products found.</p>";

    }

    ?>

</body>

</html>