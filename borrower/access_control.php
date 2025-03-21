<?php
session_start();

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if the user has borrower permissions
function hasBorrowerAccess() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'borrower';
}

// Redirect to login if not logged in
//if (!isLoggedIn()) {
  //  header("Location: /login.php");
    //exit();
//}

// Redirect to access denied page if no borrower access
//if (!hasBorrowerAccess()) {
  //  header("Location: /access_denied.php");
   // exit();
//}
?>