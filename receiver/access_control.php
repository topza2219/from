<?php
session_start();

// Function to check if the user is logged in and has the appropriate role
function check_access($required_role) {
    if (!isset($_SESSION['user_role'])) {
        header("Location: /login.php"); // Redirect to login if not logged in
        exit();
    }

    if ($_SESSION['user_role'] !== $required_role) {
        header("HTTP/1.0 403 Forbidden"); // Deny access if the role does not match
        echo "You do not have permission to access this page.";
        exit();
    }
}

// Check access for receiver role
check_access('receiver');
?>