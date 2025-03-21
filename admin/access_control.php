<?php
session_start();

// Function to check if the user is an admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Function to check if the user is an approver
function isApprover() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'approver';
}

// Function to check if the user is a borrower
function isBorrower() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'borrower';
}

// Function to check if the user is a delivery personnel
function isDeliveryPersonnel() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'delivery';
}

// Function to check if the user is a receiver
function isReceiver() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'receiver';
}

// Redirect to login if not authorized
function checkAccess($role) {
    if (!call_user_func($role)) {
        header("Location: ../login.php");
        exit();
    }
}

// Example usage
// checkAccess('isAdmin'); // Uncomment this line to restrict access to admin only
?>