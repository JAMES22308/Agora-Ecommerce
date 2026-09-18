<?php

session_start();

// Check if the user is logged in
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

    <title>Dashboard - Agora</title>

</head>

<body>

    <h1>Welcome to Agora</h1>

    <p>
        Hello, <?= $fullName ?>!
    </p>

    <p>
        Your role is: <?= $role ?>
    </p>

    <hr>

    <?php

    if ($role == "agora_admin") {

        echo "<h2>Agora Master Admin</h2>";
        echo "<p>You can manage the Agora marketplace.</p>";

    } elseif ($role == "business_admin") {

        echo "<h2>Business Account Administrator</h2>";
        echo "<p>You can manage your business account and users.</p>";

    } elseif ($role == "seller") {

        echo "<h2>Seller</h2>";
        echo "<p>You can manage your products and listings.</p>";

    } elseif ($role == "buyer") {

        echo "<h2>Buyer</h2>";
        echo "<p>You can browse, search and purchase products.</p>";

    } else {

        echo "<p>Unknown account type.</p>";

    }

    ?>

    <hr>

    <p>
        <a href="index.php">Back to Agora</a>
    </p>

    <p>
        <a href="logout.php">Logout</a>
    </p>

</body>

</html>