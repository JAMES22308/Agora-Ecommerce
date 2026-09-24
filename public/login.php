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

    <title>Login - Agora</title>

</head>

<body>

    <h1>Login to Agora</h1>

    <?php

    if ($message != "") {
        echo "<p>" . $message . "</p>";
    }

    ?>

    <form method="POST">

        <label>Email:</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit">Login</button>

    </form>

    <br>

    <a href="signup.php">Create an account</a>

    <br><br>

    <a href="index.php">Back to Agora</a>

</body>

</html>
```
