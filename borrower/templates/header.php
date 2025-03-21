<?php
require_once dirname(__FILE__) . '/../includes/notifications.php';

// Get notification counts
$notifications = getBorrowerNotifications($_SESSION['user_id']);
$pending_count = $notifications['pending'];
$approved_count = $notifications['approved'];
$rejected_count = $notifications['rejected'];
$return_count = $notifications['return'];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'ระบบยืม-คืนอุปกรณ์'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .main-content {
            margin-left: 16rem; /* w-64 = 16rem for sidebar */
            margin-right: 20%; /* New 20% right margin */
            padding-top: 1rem;
        }
        
        .stat-card {
            @apply bg-white rounded-lg shadow-sm p-6;
        }
        
        .form-group {
            @apply mb-4;
        }
        
        .form-label {
            @apply block text-sm font-medium text-gray-700 mb-1;
        }
        
        .form-label.required:after {
            content: "*";
            @apply text-red-500 ml-1;
        }
        
        .form-input {
            @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm 
                   focus:border-blue-500 focus:ring-1 focus:ring-blue-500;
        }
        
        .table-header {
            @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
        }
        
        .table-cell {
            @apply px-6 py-4 whitespace-nowrap text-sm text-gray-900;
        }
        
        .btn {
            @apply px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2;
        }
        
        .btn-primary {
            @apply bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500;
        }
        
        .btn-secondary {
            @apply bg-gray-200 text-gray-700 hover:bg-gray-300 focus:ring-gray-500;
        }
        
        .btn-danger {
            @apply bg-red-600 text-white hover:bg-red-700 focus:ring-red-500;
        }
        
        .btn-success {
            @apply bg-green-600 text-white hover:bg-green-700 focus:ring-green-500;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm fixed w-full z-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="index.php" class="text-xl font-bold text-gray-800">
                        <i class="fas fa-box-open mr-2"></i>ระบบยืม-คืนอุปกรณ์
                    </a>
                </div>

                <!-- Notifications & Profile -->
                <div class="flex items-center space-x-4">
                    <!-- Notification Icons -->
                    <div class="notifications flex space-x-3">
                        <!-- คำขอที่อนุมัติ -->
                        <?php if ($approved_count > 0): ?>
                        <a href="approved_requests.php" class="relative text-green-600" title="คำขอที่อนุมัติ">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                <?php echo $approved_count; ?>
                            </span>
                        </a>
                        <?php endif; ?>

                        <!-- คำขอที่ปฏิเสธ -->
                        <?php if ($rejected_count > 0): ?>
                        <a href="rejected_requests.php" class="relative text-red-600" title="คำขอที่ปฏิเสธ">
                            <i class="fas fa-times-circle text-xl"></i>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                <?php echo $rejected_count; ?>
                            </span>
                        </a>
                        <?php endif; ?>

                        <!-- อุปกรณ์ที่ต้องคืน -->
                        <?php if ($return_count > 0): ?>
                        <a href="return_items.php" class="relative text-yellow-600" title="อุปกรณ์ที่ต้องคืน">
                            <i class="fas fa-exclamation-circle text-xl"></i>
                            <span class="absolute -top-2 -right-2 bg-yellow-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                <?php echo $return_count; ?>
                            </span>
                        </a>
                        <?php endif; ?>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <a href="profile.php" class="flex items-center text-gray-600 hover:text-gray-900">
                            <i class="fas fa-user-circle text-xl"></i>
                            <span class="ml-2">โปรไฟล์</span>
                        </a>
                    </div>

                    <!-- Mobile menu button -->
                    <button type="button" class="mobile-menu-button lg:hidden p-2 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="stat-card">
            <!-- Content -->
        </div>
        
        <button class="btn btn-primary">
            <!-- Button content -->
        </button>
        
        <div class="table-container">
            <table>
                <th class="table-header">...</th>
                <td class="table-cell">...</td>
            </table>
        </div>
    </div>
</body>
</html>