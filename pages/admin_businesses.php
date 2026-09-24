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


/* Get all businesses */

$sql = "SELECT
            business_id,
            business_name
        FROM businesses
        ORDER BY business_name";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>All Businesses - Agora</title>

    <link rel="stylesheet" href="../public/dashboard.css">

</head>

<body>

<header>

    <h1>Agora</h1>

    <p>Master Administration</p>

</header>


<main>

    <section class="role-box">

        <h2>All Businesses</h2>

        <p>
            View businesses registered on the Agora marketplace.
        </p>


        <?php

        if ($result->num_rows > 0) {

            while ($business = $result->fetch_assoc()) {

                ?>

                <div class="welcome">

                    <h3>
                        <?= htmlspecialchars($business["business_name"]) ?>
                    </h3>

                    <p>
                        <strong>Business ID:</strong>
                        <?= htmlspecialchars($business["business_id"]) ?>
                    </p>

                </div>

                <?php

            }

        } else {

            ?>

            <p>No businesses found.</p>

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
