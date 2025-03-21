<?php
require_once dirname(__FILE__) . '/auth_check.php';
require_once dirname(__FILE__) . '/../config.php';

$page_title = "โปรไฟล์ของฉัน";
require_once dirname(__FILE__) . '/templates/header.php';
require_once dirname(__FILE__) . '/templates/sidebar.php';

try {
    // Fetch user profile and statistics
    $query = "SELECT u.*,
              (SELECT COUNT(*) FROM borrow_requests WHERE borrower_id = u.user_id) as total_requests,
              (SELECT COUNT(*) FROM borrow_requests WHERE borrower_id = u.user_id AND status = 'borrowed') as current_borrows,
              (SELECT COUNT(*) FROM borrow_requests WHERE borrower_id = u.user_id AND status = 'returned') as total_returns,
              (SELECT COUNT(*) FROM borrow_requests WHERE borrower_id = u.user_id AND status = 'rejected') as total_rejected
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

    // Fetch recent activities
    $activities_query = "SELECT br.*,
                        i.item_name,
                        DATE_FORMAT(br.created_at, '%d/%m/%Y %H:%i') as formatted_date,
                        CASE 
                            WHEN br.status = 'pending' THEN 'รออนุมัติ'
                            WHEN br.status = 'approved' THEN 'อนุมัติแล้ว'
                            WHEN br.status = 'rejected' THEN 'ไม่อนุมัติ'
                            WHEN br.status = 'borrowed' THEN 'ยืมอยู่'
                            WHEN br.status = 'returned' THEN 'คืนแล้ว'
                        END as status_text,
                        CASE 
                            WHEN br.status = 'pending' THEN 'text-yellow-600'
                            WHEN br.status = 'approved' THEN 'text-green-600'
                            WHEN br.status = 'rejected' THEN 'text-red-600'
                            WHEN br.status = 'borrowed' THEN 'text-blue-600'
                            WHEN br.status = 'returned' THEN 'text-gray-600'
                        END as status_color
                        FROM borrow_requests br
                        LEFT JOIN items i ON br.item_id = i.item_id
                        WHERE br.borrower_id = ?
                        ORDER BY br.created_at DESC
                        LIMIT 5";

    $activities_stmt = mysqli_prepare($conn, $activities_query);
    mysqli_stmt_bind_param($activities_stmt, 'i', $_SESSION['user_id']);
    mysqli_stmt_execute($activities_stmt);
    $activities = mysqli_fetch_all(mysqli_stmt_get_result($activities_stmt), MYSQLI_ASSOC);

} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'เกิดข้อผิดพลาดในการดึงข้อมูล';
    header('Location: dashboard.php');
    exit();
}
?>

<div class="main-content p-6">
    <!-- Profile Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-user-circle text-6xl text-gray-400"></i>
            </div>
            <div class="ml-6">
                <h1 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($user['full_name']); ?></h1>
                <p class="text-gray-600"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">สถิติการยืม</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-600">กำลังยืม</p>
                    <p class="text-2xl font-bold text-blue-700"><?php echo $user['current_borrows']; ?></p>
                </div>
                <div class="p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-green-600">คืนแล้ว</p>
                    <p class="text-2xl font-bold text-green-700"><?php echo $user['total_returns']; ?></p>
                </div>
                <div class="p-4 bg-yellow-50 rounded-lg">
                    <p class="text-sm text-yellow-600">คำขอทั้งหมด</p>
                    <p class="text-2xl font-bold text-yellow-700"><?php echo $user['total_requests']; ?></p>
                </div>
                <div class="p-4 bg-red-50 rounded-lg">
                    <p class="text-sm text-red-600">ไม่อนุมัติ</p>
                    <p class="text-2xl font-bold text-red-700"><?php echo $user['total_rejected']; ?></p>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">กิจกรรมล่าสุด</h2>
            <?php if (!empty($activities)): ?>
                <div class="space-y-4">
                    <?php foreach ($activities as $activity): ?>
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="font-medium"><?php echo htmlspecialchars($activity['item_name']); ?></p>
                                <p class="text-sm text-gray-500"><?php echo $activity['formatted_date']; ?></p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium <?php echo $activity['status_color']; ?>">
                                <?php echo $activity['status_text']; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">ยังไม่มีกิจกรรม</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/templates/footer.php'; ?>