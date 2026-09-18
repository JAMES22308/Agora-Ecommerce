<?php

require_once "../config/database.php";

$database = new Database();
$conn = $database->connection;

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName = $_POST["full_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    // Hash the password before saving it
    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (full_name, email, password, role)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssss",
        $fullName,
        $email,
        $password,
        $role
    );

    if ($stmt->execute()) {
        $message = "Account created successfully!";
    } else {
        $message = "Error creating account.";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Sign Up - Agora</title>

</head>

<body>

    <h1>Create an Agora Account</h1>

    <?php

    if ($message != "") {
        echo "<p>" . $message . "</p>";
    }

    ?>

    <form method="POST">

        <label>Full Name:</label>
        <input type="text" name="full_name" required>

        <br><br>

        <label>Email:</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>

        <br><br>

        <label>Account Type:</label>

        <select name="role" required>

            <option value="buyer">Buyer</option>

            <option value="seller">Seller</option>

            <option value="business_admin">
                Business Account Admin
            </option>

        </select>

        <br><br>

        <button type="submit">Create Account</button>

    </form>

    <br>

    <a href="login.php">Already have an account? Log in</a>

    <br><br>

    <a href="index.php">Back to Agora</a>

</body>

</html>