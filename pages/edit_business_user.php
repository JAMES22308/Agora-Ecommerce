
<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

requireRole(["business_admin"]);

$database = new Database();
$conn = $database->connection;

$businessId = (int) ($_SESSION["business_id"] ?? 0);
$userId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($businessId <= 0 || !$userId || $userId <= 0) {
    http_response_code(400);
    exit("Invalid business or user.");
}

// Generate CSRF token
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$message = "";

// Find a Buyer or Seller belonging to this business
$sql = "SELECT user_id, full_name, email, role
        FROM users
        WHERE user_id = ?
        AND business_id = ?
        AND role IN ('buyer', 'seller')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $businessId);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    http_response_code(404);
    exit("User not found or access denied.");
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {

        $message = "Invalid form submission. Please try again.";

    } else {

        $fullName = trim($_POST["full_name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $role = $_POST["role"] ?? "";

        if ($fullName === "" || $email === "") {

            $message = "Please complete all required fields.";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $message = "Please enter a valid email address.";

        } elseif (strlen($fullName) > 100 || strlen($email) > 150) {

            $message = "One or more fields exceed the allowed length.";

        } elseif (!in_array($role, ["buyer", "seller"], true)) {

            $message = "Invalid account type.";

        } else {

            // Check whether another user already uses this email
            $sql = "SELECT user_id
                    FROM users
                    WHERE email = ?
                    AND user_id != ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $email, $userId);
            $stmt->execute();

            $existingUser = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($existingUser) {

                $message = "This email address is already registered.";

            } else {

                // Update only the selected business user
                $sql = "UPDATE users
                        SET full_name = ?, email = ?, role = ?
                        WHERE user_id = ?
                        AND business_id = ?
                        AND role IN ('buyer', 'seller')";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    "sssii",
                    $fullName,
                    $email,
                    $role,
                    $userId,
                    $businessId
                );

                if ($stmt->execute()) {
                    $message = "User information updated successfully!";
                } else {
                    $message = "Unable to update user information.";
                }

                $stmt->close();

                // Refresh the displayed information
                $sql = "SELECT user_id, full_name, email, role
                        FROM users
                        WHERE user_id = ?
                        AND business_id = ?
                        AND role IN ('buyer', 'seller')";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $userId, $businessId);
                $stmt->execute();

                $user = $stmt->get_result()->fetch_assoc();
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
    <title>Edit Business User - Agora</title>
    <link rel="stylesheet" href="../public/dashboard.css">
</head>

<body>

<header>
    <h1>Agora</h1>
    <p>Business Account Management</p>
</header>

<main>

    <section class="welcome">

        <h2>Edit User</h2>

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
                value="<?= htmlspecialchars($user["full_name"]) ?>"
                maxlength="100"
                required
            >

            <label for="email">Email</label>

            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($user["email"]) ?>"
                maxlength="150"
                required
            >

            <label for="role">Account Type</label>

            <select id="role" name="role" required>

                <option value="buyer"
                    <?= $user["role"] === "buyer" ? "selected" : "" ?>>
                    Buyer
                </option>

                <option value="seller"
                    <?= $user["role"] === "seller" ? "selected" : "" ?>>
                    Seller
                </option>

            </select>

            <button type="submit">Save Changes</button>

        </form>

    </section>

    <div class="bottom-links">
        <a href="business_users.php">Back to Business Users</a>
        <a href="../public/dashboard.php">Dashboard</a>
    </div>

</main>

</body>
</html>
