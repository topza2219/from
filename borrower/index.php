<?php
session_start();
require_once dirname(__FILE__) . '/../config.php';

// Check if user is logged in
require_once dirname(__FILE__) .'../auth_check.php';
$display_name = $_SESSION['username'] ?? 'Borrower';
  
try {
    // Fetch user information
    $query = "SELECT u.*, 
              (SELECT COUNT(*) FROM borrow_requests WHERE borrower_id = u.user_id AND status = 'pending') as pending_count,
              (SELECT COUNT(*) FROM borrow_requests WHERE borrower_id = u.user_id AND status = 'borrowed') as active_borrows,
              (SELECT COUNT(*) FROM borrow_requests WHERE borrower_id = u.user_id AND status = 'returned') as completed_borrows
              FROM users u 
              WHERE u.user_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        throw new Exception('Database error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

    // Initialize default values in case of no user data
    if (!$user) {
        $user = [
            'full_name' => 'ผู้ใช้',
            'pending_count' => 0,
            'active_borrows' => 0,
            'completed_borrows' => 0
        ];
    }

    // Clean up database resources
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);

// Fetch recent requests
    $requests_query = "SELECT br.*, 
                      i.item_name,
                      i.description,
                      DATE_FORMAT(br.created_at, '%d/%m/%Y %H:%i') as formatted_date,
                      CASE 
                          WHEN br.status = 'pending' THEN 'รออนุมัติ'
                          WHEN br.status = 'approved' THEN 'อนุมัติแล้ว'
                          WHEN br.status = 'rejected' THEN 'ไม่อนุมัติ'
                          WHEN br.status = 'borrowed' THEN 'ยืมอยู่'
                          WHEN br.status = 'returned' THEN 'คืนแล้ว'
                      END as status_text
                      FROM borrow_requests br
                      LEFT JOIN items i ON br.item_id = i.item_id
                      WHERE br.borrower_id = ?
                      ORDER BY br.created_at DESC
                      LIMIT 5";

    $requests_stmt = mysqli_prepare($conn, $requests_query);
    mysqli_stmt_bind_param($requests_stmt, 'i', $_SESSION['user_id']);
    mysqli_stmt_execute($requests_stmt);
$requests = mysqli_fetch_all(mysqli_stmt_get_result($requests_stmt), MYSQLI_ASSOC);

} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'เกิดข้อผิดพลาดในการดึงข้อมูล';
    $user = [
        'full_name' => 'ผู้ใช้',
        'pending_count' => 0,
        'active_borrows' => 0,
        'completed_borrows' => 0
    ];
    $requests = [];
}

// Add error message display
if (isset($_SESSION['error'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">ผิดพลาด!</strong>
        <span class="block sm:inline"><?php echo htmlspecialchars($_SESSION['error']); ?></span>
    </div>
    <?php 
    unset($_SESSION['error']);
endif;

$page_title = "หน้าหลัก";
require_once dirname(__FILE__) . '/templates/header.php';
require_once dirname(__FILE__) . '/templates/sidebar.php';
?>

<div class="main-content p-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    ยินดีต้อนรับ, <?php echo htmlspecialchars($user['full_name'] ?? 'ผู้ใช้'); ?>
                </h1>
                <p class="text-gray-600 mt-1">ระบบยืม-คืนอุปกรณ์</p>
            </div>
            <a href="new_request.php" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>ขอยืมอุปกรณ์
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-blue-50 rounded-lg p-6">
            <div class="text-blue-600">
                <i class="fas fa-clock text-2xl"></i>
                <h3 class="text-lg font-semibold mt-2">รออนุมัติ</h3>
                <p class="text-3xl font-bold"><?php echo $user['pending_count'] ?? 0; ?></p>
            </div>
        </div>

        <div class="bg-green-50 rounded-lg p-6">
            <div class="text-green-600">
                <i class="fas fa-hand-holding text-2xl"></i>
                <h3 class="text-lg font-semibold mt-2">กำลังยืม</h3>
                <p class="text-3xl font-bold"><?php echo $user['active_borrows'] ?? 0; ?></p>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-6">
            <div class="text-gray-600">
                <i class="fas fa-check-circle text-2xl"></i>
                <h3 class="text-lg font-semibold mt-2">คืนแล้ว</h3>
                <p class="text-3xl font-bold"><?php echo $user['completed_borrows'] ?? 0; ?></p>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">คำขอล่าสุด</h2>
        <?php if (!empty($requests)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="table-header">รหัสคำขอ</th>
                <th class="table-header">อุปกรณ์</th>
                            <th class="table-header">จำนวน</th>
                <th class="table-header">วันที่</th>
                            <th class="table-header">สถานะ</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($requests as $request): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="table-cell">
                                    #<?php echo str_pad($request['request_id'], 5, '0', STR_PAD_LEFT); ?>
                </td>
                    <td class="table-cell">
<?php echo htmlspecialchars($request['item_name']); ?>
</td>
                    <td class="table-cell text-center">
                                    <?php echo $request['quantity']; ?>
                                </td>
                                <td class="table-cell">
                                    <?php echo $request['formatted_date']; ?>
</td>
                    <td class="table-cell">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        <?php echo match($request['status']) {
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'borrowed' => 'bg-blue-100 text-blue-800',
                                            'returned' => 'bg-gray-100 text-gray-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        }; ?>">
                                        <?php echo $request['status_text']; ?>
                                    </span>
</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-4"></i>
                <p>ยังไม่มีคำขอ</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/templates/footer.php'; ?>