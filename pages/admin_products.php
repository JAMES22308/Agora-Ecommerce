```php
<?php

session_start();

require_once "../config/database.php";

$database = new Database();
$conn = $database->connection;


if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit;
}


if ($_SESSION["role"] != "agora_admin") {
    echo "Only the Agora Master Admin can access this page.";
    exit;
}


/* Get all products with seller and business information */

$sql = "SELECT
            products.product_id,
            products.product_name,
            products.description,
            products.price,
            products.quantity,
            users.full_name AS seller_name,
            businesses.business_name
        FROM products
        JOIN users
            ON products.seller_id = users.user_id
        LEFT JOIN businesses
            ON users.business_id = businesses.business_id
        ORDER BY products.product_name";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>All Products - Agora</title>

    <link rel="stylesheet" href="../public/dashboard.css">

</head>

<body>

<header>

    <h1>Agora</h1>

    <p>Master Administration</p>

</header>


<main>

    <section class="role-box">

        <h2>All Agora Products</h2>

        <p>
            View products listed across the entire marketplace.
        </p>


        <?php

        if ($result->num_rows > 0) {

            while ($product = $result->fetch_assoc()) {

                ?>

                <div class="welcome">

                    <h3>
                        <?= htmlspecialchars($product["product_name"]) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($product["description"]) ?>
                    </p>

                    <p>
                        <strong>Seller:</strong>
                        <?= htmlspecialchars($product["seller_name"]) ?>
                    </p>

                    <p>
                        <strong>Business:</strong>
                        <?= htmlspecialchars($product["business_name"]) ?>
                    </p>

                    <p>
                        <strong>Price:</strong>
                        $<?= htmlspecialchars($product["price"]) ?>
                    </p>

                    <p>
                        <strong>Available:</strong>
                        <?= htmlspecialchars($product["quantity"]) ?>
                    </p>

                </div>

                <?php

            }

        } else {

            ?>

            <p>No products found.</p>

            <?php

        }

        ?>

    </section>


    <div class="bottom-links">

        <a href="admin_dashboard.php">
            Back to Admin Dashboard
        </a>

        <a href="../public/logout.php">
            Logout
        </a>

    </div>

</main>

</body>

</html>
```
