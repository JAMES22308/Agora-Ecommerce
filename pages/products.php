```php
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

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Products - Agora</title>

    <link rel="stylesheet" href="products.css">

</head>

<body>

    <header>

        <h1>Agora</h1>

        <p>Digital Marketplace</p>

    </header>


    <main>

        <h2>Agora Products</h2>


        <div class="home-link">

            <a href="../public/index.php">
                Home
            </a>

        </div>


        <hr>


        <?php

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

                    <a class="view-product"
                       href="product.php?id=<?= $product["product_id"] ?>">
                        View Product
                    </a>

                </section>

                <?php

            }

        } else {

            ?>

            <p class="no-products">
                No products found.
            </p>

            <?php

        }

        ?>

    </main>

</body>

</html>
```
