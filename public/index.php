<?php

$storeName = "Agora";
$tagline = "Digital Marketplace";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $storeName ?></title>
</head>

<body>

    <header>
        <h1><?= $storeName ?></h1>
        <p><?= $tagline ?></p>

        <nav>
            <a href="login.php">Login</a>
            <a href="signup.php">Sign Up</a>
        </nav>
    </header>

    <main>

        <h2>Welcome to Agora</h2>

        <p>
            Find products from businesses across the marketplace.
        </p>

        <form action="pages/search.php" method="GET">

            <label for="search">Search products:</label>

            <input
                type="text"
                id="search"
                name="search"
                placeholder="Search for a product"
            >

            <button type="submit">Search</button>

        </form>

        <p>
            <a href="pages/products.php">Browse Products</a>
        </p>

    </main>

</body>
</html>