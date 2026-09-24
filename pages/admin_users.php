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


/* Get all users and their business */

$sql = "SELECT
            users.user_id,
            users.full_name,
            users.email,
            users.role,
            businesses.business_name
        FROM users
        LEFT JOIN businesses
            ON users.business_id = businesses.business_id
        ORDER BY users.full_name";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>All Users - Agora</title>

    <link rel="stylesheet" href="../public/dashboard.css">

</head>

<body>

<header>

    <h1>Agora</h1>

    <p>Master Administration</p>

</header>


<main>

    <section class="role-box">

        <h2>All Agora Users</h2>

        <p>
            View users from all businesses and account types.
        </p>


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

                    <p>
                        <strong>Business:</strong>

                        <?php

                        if ($user["business_name"] != null) {
                            echo htmlspecialchars($user["business_name"]);
                        } else {
                            echo "No business";
                        }

                        ?>

                    </p>

                </div>

                <?php

            }

        } else {

            ?>

            <p>No users found.</p>

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
