<?php
session_start();

// Check if the user is logged in and has admin privileges
//if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
  //  header('Location: login.php');
   // exit();
//}

// Include header and sidebar
//include 'templates/header.php';
//include 'templates/sidebar.php';
?>

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-4">Admin Dashboard</h1>
    <p class="text-gray-600 mb-6">Manage borrowers, approvers, deliveries, and more with ease.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
        $managementLinks = [
            ['href' => 'manage_borrowers.php', 'label' => 'Manage Borrowers', 'icon' => 'user-group'],
            ['href' => 'manage_approvers.php', 'label' => 'Manage Approvers', 'icon' => 'check-circle'],
            ['href' => 'manage_deliveries.php', 'label' => 'Manage Deliveries', 'icon' => 'truck'],
            ['href' => 'manage_receivers.php', 'label' => 'Manage Receivers', 'icon' => 'inbox'],
            ['href' => 'manage_returns.php', 'label' => 'Manage Returns', 'icon' => 'refresh-cw'],
            ['href' => 'manage_users.php', 'label' => 'Manage Users', 'icon' => 'users'],
            ['href' => 'borrow_records.php', 'label' => 'Borrow Records', 'icon' => 'file-text'],
            ['href' => 'reports.php', 'label' => 'Reports', 'icon' => 'bar-chart-2'],
            ['href' => 'analytics.php', 'label' => 'Analytics', 'icon' => 'activity'],
            ['href' => 'risk_analysis.php', 'label' => 'Risk Analysis', 'icon' => 'alert-triangle'],
            ['href' => 'notifications.php', 'label' => 'Notifications', 'icon' => 'bell'],
            ['href' => 'logs.php', 'label' => 'Logs', 'icon' => 'clipboard'],
            ['href' => 'backup.php', 'label' => 'Backup', 'icon' => 'database'],
            ['href' => 'settings.php', 'label' => 'Settings', 'icon' => 'settings'],
        ];

        foreach ($managementLinks as $link) {
            echo "<a href='{$link['href']}' class='block p-4 bg-white rounded-lg shadow hover:bg-gray-100 transition'>
                    <div class='flex items-center space-x-4'>
                        <i data-feather='{$link['icon']}' class='text-gray-500'></i>
                        <span class='text-lg font-medium'>{$link['label']}</span>
                    </div>
                </a>";
        }
        ?>
    </div>
</div>

<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace();
</script>

<?php
// Include footer
include 'templates/footer.php';
?>