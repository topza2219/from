<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/api/get_delivery_stats.php';

// Get delivery staff data
$delivery_id = $_SESSION['user_id'];
$stats = getDeliveryStats($delivery_id);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก - เจ้าหน้าที่จัดส่ง</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-xl font-semibold">ระบบยืม-คืนอุปกรณ์</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a href="../logout.php" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Pending Deliveries -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-1 text-sm text-gray-500">รอจัดส่ง</p>
                        <h3 class="text-2xl font-bold text-gray-900"><?php echo $stats['pending_count']; ?></h3>
                    </div>
                </div>
            </div>

            <!-- In Progress -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-1 text-sm text-gray-500">กำลังจัดส่ง</p>
                        <h3 class="text-2xl font-bold text-gray-900"><?php echo $stats['in_progress_count']; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-1 text-sm text-gray-500">จัดส่งสำเร็จ</p>
                        <h3 class="text-2xl font-bold text-gray-900"><?php echo $stats['completed_count']; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Tasks Table -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">รายการจัดส่ง</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">รหัสจัดส่ง</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">อุปกรณ์</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ผู้รับ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ที่อยู่</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">สถานะ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($stats['deliveries'] as $delivery): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo htmlspecialchars($delivery['id']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo htmlspecialchars($delivery['item_name']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo htmlspecialchars($delivery['recipient_name']); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <?php echo htmlspecialchars($delivery['address']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php echo getStatusClass($delivery['status']); ?>">
                                    <?php echo htmlspecialchars($delivery['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <?php if ($delivery['status'] === 'pending'): ?>
                                    <a href="start_delivery.php?id=<?php echo $delivery['id']; ?>" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">เริ่มจัดส่ง</a>
                                <?php elseif ($delivery['status'] === 'in_progress'): ?>
                                    <a href="complete_delivery.php?id=<?php echo $delivery['id']; ?>" 
                                       class="text-green-600 hover:text-green-900">เสร็จสิ้น</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
function getStatusClass($status) {
    switch ($status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'in_progress':
            return 'bg-blue-100 text-blue-800';
        case 'completed':
            return 'bg-green-100 text-green-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}
?>