<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy the session
session_destroy();

// Clear any other cookies (optional)
foreach ($_COOKIE as $key => $value) {
    setcookie($key, '', time()-3600, '/');
}

// Redirect to login page with success message
$redirect_url = 'login.php?logout=success';
if (isset($_GET['expired'])) {
    $redirect_url = 'login.php?session=expired';
}

// Prevent caching of this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Redirect to login page
header('Location: ' . $redirect_url);
exit();
?>