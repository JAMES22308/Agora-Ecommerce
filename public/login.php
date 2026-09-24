```php
<?php

session_start();

require_once "../config/database.php";

$database = new Database();
$conn = $database->connection;

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    // Find the user from the database
    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        // Check the password stored in the database
        if ($password == $user["password"]) {

            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["full_name"] = $user["full_name"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["business_id"] = $user["business_id"];

            header("Location: dashboard.php");
            exit;

        } else {

            $message = "Incorrect password.";

        }

    } else {

        $message = "Account not found.";

    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Agora</title>

    <link rel="stylesheet" href="login.css">

</head>

<body>

    <header>

        <h1>Agora</h1>

        <p>Digital Marketplace</p>

    </header>


    <main>

        <section class="login-card">

            <h2>Login to Agora</h2>


            <?php

            if ($message != "") {

                echo "<p class='message'>" . htmlspecialchars($message) . "</p>";

            }

            ?>


            <form method="POST">

                <label for="email">
                    Email:
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                >


                <label for="password">
                    Password:
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >


                <button type="submit">
                    Login
                </button>

            </form>


            <div class="links">

                <a href="signup.php">
                    Create an account
                </a>

                <a href="index.php">
                    Back to Agora
                </a>

            </div>

        </section>

    </main>

</body>

</html>
```
