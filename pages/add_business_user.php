
<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

requireRole(["business_admin"]);

$database = new Database();
$conn = $database->connection;

$businessId = (int) ($_SESSION["business_id"] ?? 0);

if ($businessId <= 0) {
    http_response_code(403);
    exit("No business is associated with this account.");
}

$message = "";

// Generate CSRF token
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {

        $message = "Invalid form submission. Please try again.";

    } else {

        $fullName = trim($_POST["full_name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $role = $_POST["role"] ?? "";

        if ($fullName === "" || $email === "" || $password === "") {

            $message = "Please complete all required fields.";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $message = "Please enter a valid email address.";

        } elseif (strlen($fullName) > 100 || strlen($email) > 150) {

            $message = "One or more fields exceed the allowed length.";

        } elseif (strlen($password) < 8) {

            $message = "Password must contain at least 8 characters.";

        } elseif (!in_array($role, ["buyer", "seller"], true)) {

            $message = "Invalid account role selected.";

        } else {

            // Check whether email already exists
            $sql = "SELECT user_id
                    FROM users
                    WHERE email = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $existingUser = $stmt->get_result()->fetch_assoc();

            $stmt->close();

            if ($existingUser) {

                $message = "An account with this email already exists.";

            } else {

                // Hash the password before saving it
                $hashedPassword = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                // Associate the new user with this business
                $sql = "INSERT INTO users
                        (business_id, full_name, email, password, role)
                        VALUES (?, ?, ?, ?, ?)";

                $stmt = $conn->prepare($sql);

                $stmt->bind_param(
                    "issss",
                    $businessId,
                    $fullName,
                    $email,
                    $hashedPassword,
                    $role
                );

                if ($stmt->execute()) {

                    $message = "User account created successfully!";

                } else {

                    $message = "Unable to create the user account.";

                }

                $stmt->close();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Business User - Agora</title>

    <link rel="stylesheet" href="../public/dashboard.css">

</head>

<body>

<header>

    <h1>Agora</h1>

    <p>Business Account Management</p>

</header>

<main>

    <section class="welcome">

        <h2>Create Business User</h2>

        <?php if ($message !== "") { ?>

            <p><?= htmlspecialchars($message) ?></p>

        <?php } ?>

        <form method="POST">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>"
            >

            <label for="full_name">Full Name</label>

            <input
                type="text"
                id="full_name"
                name="full_name"
                maxlength="100"
                required
            >

            <label for="email">Email</label>

            <input
                type="email"
                id="email"
                name="email"
                maxlength="150"
                required
            >

            <label for="password">Temporary Password</label>

            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                required
            >

            <label for="role">Account Type</label>

            <select id="role" name="role" required>

                <option value="">Select account type</option>

                <option value="buyer">Buyer</option>

                <option value="seller">Seller</option>

            </select>

            <button type="submit">
                Create User
            </button>

        </form>

    </section>

    <div class="bottom-links">

        <a href="business_users.php">
            Back to Business Users
        </a>

        <a href="../public/dashboard.php">
            Dashboard
        </a>

    </div>

</main>

</body>

</html>
