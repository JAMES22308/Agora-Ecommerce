
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../config/database.php";

$database = new Database();
$conn = $database->connection;

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get and validate user input
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === "") {

        $message = "Invalid email or password.";

    } else {

        // Find the user from the database
        $sql = "SELECT
                    user_id,
                    full_name,
                    email,
                    password,
                    role,
                    business_id
                FROM users
                WHERE email = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();

        $user = $result->fetch_assoc();

        // Verify the submitted password against the stored hash
        if ($user && password_verify($password, $user["password"])) {

            // Regenerate session ID after successful login
            session_regenerate_id(true);

            // Store user information in the session
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["full_name"] = $user["full_name"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["business_id"] = $user["business_id"];

            $stmt->close();

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit;

        } else {

            // Use a generic message for security
            $message = "Invalid email or password.";

        }

        $stmt->close();
    }
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

            <?php if ($message !== "") { ?>

                <p class="message">
                    <?= htmlspecialchars($message) ?>
                </p>

            <?php } ?>

            <form method="POST">

                <label for="email">
                    Email:
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"
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
