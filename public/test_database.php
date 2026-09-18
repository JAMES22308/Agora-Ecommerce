<?php

require_once "../config/database.php";

$database = new Database();

if ($database->testConnection) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed.";
}

