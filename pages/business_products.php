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

if ($_SESSION["role"] != "business_admin") {
    echo "Only Business Account Administrators can access this page.";
    exit;
}

$businessId = $_SESSION["business_id"];

$sql = "SELECT
            products.product_id,
            products.product_name,
            products.description,
            products.price,
            products.quantity,
            users.full_name AS seller_name
        FROM products
        JOIN users
            ON products.seller_id = users.user_id
        WHERE users.business_id = ?
        ORDER BY products.product_name";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $businessId);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Business Products - Agora</title>

    <link rel="stylesheet" href="../public/dashboard.css">

</head>

<body>

<header>

    <h1>Agora</h1>

    <p>Business Products</p>

</header>


<main>

    <section class="role-box">

        <h2>Products for Your Business</h2>

        <p>
            These products are listed by sellers belonging to your business.
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

            <p>
                Your business currently has no products.
            </p>

            <?php

        }

        ?>

    </section>


    <div class="bottom-links">

        <a href="../public/dashboard.php">
            Back to Dashboard
        </a>

        <a href="../public/logout.php">
            Logout
        </a>

    </div>

</main>

</body>

</html>
```
