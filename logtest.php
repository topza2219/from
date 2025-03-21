if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "admin") {
    header("Location:   ../login.php");
    exit(); 
}
$display_name = $_SESSION['username'] ?? 'Admin';

?>