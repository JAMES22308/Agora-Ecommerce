
<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

requireRole(["business_admin"]);

$database = new Database();
$conn = $database->connection;

$businessId = (int) ($_SESSION["business_id"] ?? 0);
$message = "";
$messageType = "";

// Check business association
if ($businessId <= 0) {
    http_response_code(403);
    exit("No business is associated with this account.");
}

// Create CSRF token
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Handle business name update
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {

        $message = "Invalid form submission. Please try again.";
        $messageType = "error";

    } else {

        $businessName = trim($_POST["business_name"] ?? "");

        if ($businessName === "") {

            $message = "Business name cannot be empty.";
            $messageType = "error";

        } elseif (strlen($businessName) > 100) {

            $message = "Business name cannot exceed 100 characters.";
            $messageType = "error";

        } else {

            $sql = "UPDATE businesses
                    SET business_name = ?
                    WHERE business_id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $businessName, $businessId);

            if ($stmt->execute()) {

                $message = "Business information updated successfully!";
                $messageType = "success";

            } else {

                $message = "Unable to update business information.";
                $messageType = "error";

            }

            $stmt->close();
        }
    }
}

// Retrieve current business information
$sql = "SELECT business_id, business_name
        FROM businesses
        WHERE business_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $businessId);
$stmt->execute();

$result = $stmt->get_result();
$business = $result->fetch_assoc();

$stmt->close();

if (!$business) {
    http_response_code(404);
    exit("Business information could not be found.");
}

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

        <?php if ($message !== "") { ?>

            <p>
                <?= htmlspecialchars($message) ?>
            </p>

        <?php } ?>

        <p>
            <strong>Business ID:</strong>
            <?= htmlspecialchars((string) $business["business_id"]) ?>
        </p>

        <form method="POST">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>"
            >

            <label for="business_name">
                Business Name:
            </label>

            <input
                type="text"
                id="business_name"
                name="business_name"
                value="<?= htmlspecialchars($business["business_name"]) ?>"
                maxlength="100"
                required
            >

            <button type="submit">
                Save Changes
            </button>

        </form>

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
