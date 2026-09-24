```php
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

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign Up - Agora</title>

    <link rel="stylesheet" href="signup.css">

</head>

<body>

    <header>

        <h1>Agora</h1>

        <p>Digital Marketplace</p>

    </header>


    <main>

        <section class="signup-card">

            <h2>Create an Agora Account</h2>


            <?php

            if ($message != "") {

                echo "<p class='message'>" .
                     htmlspecialchars($message) .
                     "</p>";

            }

            ?>


            <form method="POST">

                <label for="full_name">
                    Full Name:
                </label>

                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    required
                >


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


                <label for="role">
                    Account Type:
                </label>

                <select
                    id="role"
                    name="role"
                    required
                >

                    <option value="buyer">
                        Buyer
                    </option>

                    <option value="seller">
                        Seller
                    </option>

                    <option value="business_admin">
                        Business Account Admin
                    </option>

                </select>


                <button type="submit">
                    Create Account
                </button>

            </form>


            <div class="links">

                <a href="login.php">
                    Already have an account? Log in
                </a>

                <a href="index.php">
                    Back to Agora
                </a>

            </div>

        </section>

    </main>

</body>

</html>

