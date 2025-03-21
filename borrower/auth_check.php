<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and has approver role
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "borrower") {
    header("Location:   ../login.php");
    exit(); 
}
$display_name = $_SESSION['username'] ?? 'Borrower';


/**
 * Check if user is logged in and has required role
 * @param string|array $allowed_roles Roles that can access
 * @return bool
 */
function checkAuth($allowed_roles = [ 'borrower']) {
    // Check if session exists
    if (!isset($_SESSION['role']) || !isset($_SESSION['role'])) {
        return false;
    }

    // Convert single role to array
    if (!is_array($allowed_roles)) {
        $allowed_roles = [$allowed_roles];
    }

    // Check if user's role is allowed
    return in_array($_SESSION['role'], $allowed_roles);
}

/**
 * Require authentication to access page
 * @param string|array $allowed_roles Roles that can access
 */
function requireAuth($allowed_roles = ['boorower']) {
    if (!checkAuth($allowed_roles)) {
        // Store intended URL for redirect after login
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('Location: ../login.php?error=auth_required');
        exit();
    }
}

/**
 * Get current user info
 * @return array|null User data or null if not logged in
 */
function getCurrentUser() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    return [
        'user_id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'name' => $_SESSION['name'] ?? '',
        'role' => $_SESSION['role'] ?? '',
        'email' => $_SESSION['email'] ?? '',
        'department' => $_SESSION['department'] ?? ''
    ];
}

/**
 * Check if current user has specific role
 * @param string|array $roles Role(s) to check
 * @return bool
 */
function hasRole($roles) {
    if (!isset($_SESSION['role'])) {
        return false;
    }

    if (!is_array($roles)) {
        $roles = [$roles];
    }

    return in_array($_SESSION['role'], $roles);
}

// Define role constants
define('ROLE_ADMIN', 'admin');
define('ROLE_APPROVER', 'approver');
define('ROLE_USER', 'user');
define('ROLE_MANAGER', 'manager');