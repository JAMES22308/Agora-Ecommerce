```php
<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$fullName = $_SESSION["full_name"];
$role = $_SESSION["role"];

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Agora</title>

    <link rel="stylesheet" href="dashboard.css">

</head>

<body>

<header>

    <h1>Agora</h1>

    <p>Digital Marketplace</p>

</header>


<main>

    <section class="welcome">

        <h2>Welcome back, <?= htmlspecialchars($fullName) ?>!</h2>

        <p>
            Account Type:
            <strong><?= htmlspecialchars($role) ?></strong>
        </p>

    </section>


    <section class="role-box">

        <?php

        if ($role == "agora_admin") {
            ?>

            <h2>Agora Master Admin</h2>

            <p>
                You can manage the Agora marketplace.
            </p>

            <h3>Admin Options</h3>

            <div class="options">

                <a class="option" href="../pages/admin_dashboard.php">
                    Manage Agora
                </a>

            </div>

            <?php

        } elseif ($role == "business_admin") {
            ?>

            <h2>Business Account Administrator</h2>

            <p>
                Manage your business account, users and products.
            </p>

            <h3>Business Options</h3>

            <div class="options">

                <a class="option" href="../pages/business_users.php">
                    Manage Business Users
                </a>

                <a class="option" href="../pages/business_information.php">
                    Business Information
                </a>

                <a class="option" href="../pages/business_products.php">
                    View Business Products
                </a>

            </div>

            <?php

        } elseif ($role == "seller") {
            ?>

            <h2>Seller</h2>

            <p>
                You can manage your products and listings.
            </p>

            <h3>Seller Options</h3>

            <div class="options">

                <a class="option" href="../pages/my_products.php">
                    My Products
                </a>

                <a class="option" href="../pages/add_product.php">
                    Add Product
                </a>

            </div>

            <?php

        } elseif ($role == "buyer") {
            ?>

            <h2>Buyer</h2>

            <p>
                You can browse, search and purchase products.
            </p>

            <h3>Buyer Options</h3>

            <div class="options">

                <a class="option" href="../pages/products.php">
                    Browse Products
                </a>

                <a class="option" href="../pages/search.php">
                    Search Products
                </a>

                <a class="option" href="../pages/my_orders.php">
                    My Orders
                </a>

            </div>

            <?php

        } else {

            ?>

            <p>Unknown account type.</p>

            <?php

        }

        ?>

    </section>


    <div class="bottom-links">

        <a href="index.php">
            Back to Agora
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</main>

</body>

</html>
```
