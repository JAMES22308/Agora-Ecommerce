<?php

require_once "../config/database.php";

$database = new Database();
$conn = $database->connection;

$search = "";

if (isset($_GET["search"])) {

    $search = $_GET["search"];

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Search - Agora</title>

</head>

<body>

    <h1>Search Agora Products</h1>

    <form method="GET">

        <label>Search:</label>

        <input
            type="text"
            name="search"
            value="<?= $search ?>"
        >

        <button type="submit">Search</button>

    </form>

    <br>

    <a href="products.php">View All Products</a>

    <hr>

    <?php

    if ($search != "") {

        $searchValue = "%" . $search . "%";

        $sql = "SELECT * FROM products
                WHERE product_name LIKE ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("s", $searchValue);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            while ($product = $result->fetch_assoc()) {

                echo "<h2>" . htmlspecialchars($product["product_name"]) . "</h2>";

                echo "<p>";
                echo htmlspecialchars($product["description"]);
                echo "</p>";

                echo "<p>";
                echo "Price: $" . $product["price"];
                echo "</p>";

                echo "<p>";
                echo "Available: " . $product["quantity"];
                echo "</p>";

                echo "<hr>";
            }

        } else {

            echo "<p>No products found.</p>";

        }

        $stmt->close();

    }

    ?>

    <br>

    <a href="../public/index.php">Back to Agora</a>

</body>

</html>