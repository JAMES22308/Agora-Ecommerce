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

$sql = "SELECT *
        FROM businesses
        WHERE business_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $businessId);

$stmt->execute();

$result = $stmt->get_result();

$business = $result->fetch_assoc();

$stmt->close();

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Business Information - Agora</title>

    <link rel="stylesheet" href="../public/dashboard.css">

</head>

<body>

<header>

    <h1>Agora</h1>

    <p>Business Account Management</p>

</header>


<main>

    <section class="welcome">

        <h2>Business Information</h2>

        <?php

        if ($business) {

            ?>

            <p>
                <strong>Business Name:</strong>
                <?= htmlspecialchars($business["business_name"]) ?>
            </p>

            <p>
                <strong>Business ID:</strong>
                <?= htmlspecialchars($business["business_id"]) ?>
            </p>

            <?php

        } else {

            ?>

            <p>Business information could not be found.</p>

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
