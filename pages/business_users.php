```php
<?php

session_start();

require_once "../config/database.php";

$database = new Database();
$conn = $database->connection;


/* Check if user is logged in */

if (!isset($_SESSION["user_id"])) {

    header("Location: ../public/login.php");
    exit;

}


/* Only Business Admins can access this page */

if ($_SESSION["role"] != "business_admin") {

    echo "Only Business Account Administrators can access this page.";
    exit;

}


/* Get the Business Admin's business ID */

$businessId = $_SESSION["business_id"];


/* Get business name */

$sql = "SELECT business_name
        FROM businesses
        WHERE business_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $businessId);

$stmt->execute();

$result = $stmt->get_result();

$business = $result->fetch_assoc();

$stmt->close();


/* Get users belonging to this business */

$sql = "SELECT
            user_id,
            full_name,
            email,
            role
        FROM users
        WHERE business_id = ?
        ORDER BY full_name";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $businessId);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Business Users - Agora</title>

    <link rel="stylesheet" href="../public/dashboard.css">

</head>

<body>

<header>

    <h1>Agora</h1>

    <p>Business Account Management</p>

</header>


<main>

    <section class="role-box">

        <h2>Business Users</h2>

        <?php if ($business) { ?>

            <p>
                <strong>Business:</strong>
                <?= htmlspecialchars($business["business_name"]) ?>
            </p>

        <?php } ?>


        <h3>Users in Your Business</h3>


        <?php

        if ($result->num_rows > 0) {

            while ($user = $result->fetch_assoc()) {

                ?>

                <div class="welcome">

                    <h3>
                        <?= htmlspecialchars($user["full_name"]) ?>
                    </h3>

                    <p>
                        <strong>Email:</strong>
                        <?= htmlspecialchars($user["email"]) ?>
                    </p>

                    <p>
                        <strong>Role:</strong>
                        <?= htmlspecialchars($user["role"]) ?>
                    </p>

                </div>

                <?php

            }

        } else {

            ?>

            <p>
                No users were found for this business.
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
