
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check whether a user is logged in.
 */
function requireLogin(): void
{
    if (!isset($_SESSION["user_id"])) {
        header("Location: ../public/login.php");
        exit;
    }
}

/**
 * Check whether the user has an allowed role.
 */
function requireRole(array $allowedRoles): void
{
    requireLogin();

    if (!in_array($_SESSION["role"] ?? "", $allowedRoles, true)) {
        http_response_code(403);
        exit("Access denied. You do not have permission to access this page.");
    }
}

/**
 * Get the current user's ID.
 */
function currentUserId(): ?int
{
    return isset($_SESSION["user_id"])
        ? (int) $_SESSION["user_id"]
        : null;
}

/**
 * Get the current user's role.
 */
function currentUserRole(): ?string
{
    return $_SESSION["role"] ?? null;
}
