
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

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Search - Agora</title>

    <link rel="stylesheet" href="search.css">

</head>

<body>

    <header>

        <h1>Agora</h1>

        <p>Digital Marketplace</p>

    </header>


    <main>

        <h2>Search Agora Products</h2>


        <form method="GET">

            <label for="search">Search:</label>

            <input
                type="text"
                id="search"
                name="search"
                value="<?= htmlspecialchars($search) ?>"
                placeholder="Search for a product"
            >

            <button type="submit">
                Search
            </button>

        </form>


        <p>

            <a class="products-link" href="products.php">
                View All Products
            </a>

        </p>


        <hr>


        <?php

        if ($search != "") {

            $searchValue = "%" . $search . "%";

            $sql = "SELECT *
                    FROM products
                    WHERE product_name LIKE ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param("s", $searchValue);

            $stmt->execute();

            $result = $stmt->get_result();


            if ($result->num_rows > 0) {

                while ($product = $result->fetch_assoc()) {

                    ?>

                    <section class="product-card">

                        <h3>
                            <?= htmlspecialchars($product["product_name"]) ?>
                        </h3>

                        <p>
                            <?= htmlspecialchars($product["description"]) ?>
                        </p>

                        <p>
                            <strong>Price:</strong>
                            $<?= htmlspecialchars($product["price"]) ?>
                        </p>

                        <p>
                            <strong>Available:</strong>
                            <?= htmlspecialchars($product["quantity"]) ?>
                        </p>

                    </section>

                    <?php

                }

            } else {

                ?>

                <p class="no-results">
                    No products found.
                </p>

                <?php

            }

            $stmt->close();

        }

        ?>


        <div class="bottom-link">

            <a href="../public/index.php">
                Back to Agora
            </a>

        </div>

    </main>

</body>

</html>

