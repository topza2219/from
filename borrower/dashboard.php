<?php
require_once dirname(__FILE__) . '/auth_check.php';
require_once dirname(__FILE__) . '/../config.php';
require_once __DIR__ . '/api/get_borrower_stats.php';

// Set page title
$page_title = "หน้าหลัก";

// Fetch user information
try {
    $user_query = "SELECT full_name FROM users WHERE user_id = ?";
    $user_stmt = mysqli_prepare($conn, $user_query);
    
    if ($user_stmt === false) {
        throw new Exception("Query preparation failed: " . mysqli_error($conn));
    }

    if (!mysqli_stmt_bind_param($user_stmt, 'i', $_SESSION['user_id'])) {
        throw new Exception("Parameter binding failed: " . mysqli_stmt_error($user_stmt));
    }

    if (!mysqli_stmt_execute($user_stmt)) {
        throw new Exception("Query execution failed: " . mysqli_stmt_error($user_stmt));
    }

    $user_result = mysqli_stmt_get_result($user_stmt);
    $user = mysqli_fetch_assoc($user_result);
    $_SESSION['full_name'] = $user['full_name'] ?? 'ผู้ใช้';
    
    mysqli_stmt_close($user_stmt);
} catch (Exception $e) {
    error_log("Error fetching user info: " . $e->getMessage());
    $_SESSION['full_name'] = 'ผู้ใช้';
}

require_once dirname(__FILE__) . '/templates/header.php';
require_once dirname(__FILE__) . '/templates/sidebar.php';

// Get borrower's data
$borrower_id = $_SESSION['user_id'];
$stats = getBorrowerStats($borrower_id);

try {
    // Fetch user's active requests
    $query = "SELECT br.*, 
              i.item_name,
              DATE_FORMAT(br.created_at, '%d/%m/%Y %H:%i') as formatted_created_at,
              DATE_FORMAT(br.borrow_date, '%d/%m/%Y') as formatted_borrow_date,
              DATE_FORMAT(br.expected_return_date, '%d/%m/%Y') as formatted_return_date
              FROM borrow_requests br
              LEFT JOIN items i ON br.item_id = i.item_id
              WHERE br.borrower_id = ?
              ORDER BY br.created_at DESC";

    $stmt = mysqli_prepare($conn, $query);
    if ($stmt === false) {
        throw new Exception("Query preparation failed: " . mysqli_error($conn));
    }

    if (!mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id'])) {
        throw new Exception("Parameter binding failed: " . mysqli_stmt_error($stmt));
    }

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Query execution failed: " . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_get_result($stmt);
    $requests = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

} catch (Exception $e) {
    error_log("Error in dashboard.php: " . $e->getMessage());
    $error = $e->getMessage();
    $requests = [];
}
?>

<div class="container mx-auto px-4 pt-16">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">ยินดีต้อนรับ, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h1>
            <p class="mt-2 text-sm text-gray-600">ระบบจัดการการยืม-คืนอุปกรณ์</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <a href="new_request.php" class="bg-blue-50 p-4 rounded-lg hover:bg-blue-100">
                <div class="flex items-center">
                    <i class="fas fa-plus-circle text-blue-600 text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-medium text-blue-900">ขอยืมอุปกรณ์</h3>
                        <p class="text-sm text-blue-600">สร้างคำขอยืมใหม่</p>
                    </div>
                </div>
            </a>
            <!-- Add more quick actions here -->
        </div>

        <!-- Recent Requests -->
        <div class="mt-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">คำขอล่าสุดของคุณ</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">รหัสคำขอ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">อุปกรณ์</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">วันที่ยืม</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">วันที่คืน</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">สถานะ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($requests)): ?>
                            <?php foreach ($requests as $request): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #<?php echo str_pad($request['request_id'], 5, '0', STR_PAD_LEFT); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($request['item_name']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo $request['formatted_borrow_date']; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo $request['formatted_return_date']; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo getStatusClass($request['status']); ?>">
                                            <?php echo getStatusText($request['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="view_request.php?id=<?php echo $request['request_id']; ?>" 
                                           class="text-blue-600 hover:text-blue-900">
                                            ดูรายละเอียด
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    ไม่พบรายการคำขอ
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Helper functions
function getStatusClass($status) {
    switch ($status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'approved':
            return 'bg-green-100 text-green-800';
        case 'rejected':
            return 'bg-red-100 text-red-800';
        case 'borrowed':
            return 'bg-blue-100 text-blue-800';
        case 'returned':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

function getStatusText($status) {
    switch ($status) {
        case 'pending':
            return 'รอการอนุมัติ';
        case 'approved':
            return 'อนุมัติแล้ว';
        case 'rejected':
            return 'ปฏิเสธแล้ว';
        case 'borrowed':
            return 'กำลังยืม';
        case 'returned':
            return 'คืนแล้ว';
        default:
            return $status;
    }
}

require_once dirname(__FILE__) . '/templates/footer.php';
?>