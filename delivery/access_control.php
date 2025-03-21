<?php
session_start();

// Function to check if the user has the required role
function hasAccess($requiredRole) {
    if (!isset($_SESSION['user_role'])) {
        return false; // User is not logged in
    }
    return $_SESSION['user_role'] === $requiredRole;
}

// Check access for delivery personnel
if (!hasAccess('delivery')) {
    header('HTTP/1.0 403 Forbidden');
    echo 'You do not have permission to access this page.';
    exit;
}

// Additional access control logic can be added here
?>