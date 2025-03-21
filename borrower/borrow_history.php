<?php
require_once dirname(__FILE__) . '/auth_check.php';
require_once dirname(__FILE__) . '/../config.php';

$page_title = "ประวัติการยืม";
require_once dirname(__FILE__) . '/templates/header.php';
require_once dirname(__FILE__) . '/templates/sidebar.php';

try {
    // Get filter parameters
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

    // Base query
    $query = "SELECT br.*, 
              i.item_name,
              i.description,
              i.category,
              u.full_name as approver_name,
              DATE_FORMAT(br.created_at, '%d/%m/%Y %H:%i') as formatted_created_at,
              DATE_FORMAT(br.borrow_date, '%d/%m/%Y') as formatted_borrow_date,
              DATE_FORMAT(br.expected_return_date, '%d/%m/%Y') as formatted_return_date,
              DATE_FORMAT(br.returned_at, '%d/%m/%Y %H:%i') as formatted_returned_at
              FROM borrow_requests br
              LEFT JOIN items i ON br.item_id = i.item_id
              LEFT JOIN users u ON br.approver_id = u.user_id
              WHERE br.borrower_id = ? ";

    $params = [$_SESSION['user_id']];
    $types = 'i';

    // Add filters
    if ($status) {
        $query .= " AND br.status = ?";
        $params[] = $status;
        $types .= 's';
    }
    
    if ($start_date) {
        $query .= " AND DATE(br.created_at) >= ?";
        $params[] = $start_date;
        $types .= 's';
    }
    
    if ($end_date) {
        $query .= " AND DATE(br.created_at) <= ?";
        $params[] = $end_date;
        $types .= 's';
    }

    $query .= " ORDER BY br.created_at DESC";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        throw new Exception('Database error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $requests = mysqli_fetch_all($result, MYSQLI_ASSOC);

} catch (Exception $e) {
    error_log($e->getMessage());
    $error = 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $e->getMessage();
    $requests = [];
}
?>

<div class="main-content p-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">ประวัติการยืม</h1>
            
            <!-- Filters -->
            <form class="flex items-center space-x-4">
                <select name="status" class="form-input">
                    <option value="">สถานะทั้งหมด</option>
                    <option value="pending" <?php echo $status == 'pending' ? 'selected' : ''; ?>>รออนุมัติ</option>
                    <option value="approved" <?php echo $status == 'approved' ? 'selected' : ''; ?>>อนุมัติแล้ว</option>
                    <option value="rejected" <?php echo $status == 'rejected' ? 'selected' : ''; ?>>ไม่อนุมัติ</option>
                    <option value="borrowed" <?php echo $status == 'borrowed' ? 'selected' : ''; ?>>ยืมอยู่</option>
                    <option value="returned" <?php echo $status == 'returned' ? 'selected' : ''; ?>>คืนแล้ว</option>
                </select>
                
                <input type="date" name="start_date" value="<?php echo $start_date; ?>" 
                       class="form-input" placeholder="วันที่เริ่มต้น">
                <input type="date" name="end_date" value="<?php echo $end_date; ?>" 
                       class="form-input" placeholder="วันที่สิ้นสุด">
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter mr-2"></i>กรอง
                </button>
            </form>
        </div>

        <?php if (!empty($requests)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="table-header">รหัสคำขอ</th>
                            <th class="table-header">อุปกรณ์</th>
                            <th class="table-header">จำนวน</th>
                            <th class="table-header">วันที่ยืม</th>
                            <th class="table-header">กำหนดคืน</th>
                            <th class="table-header">วันที่คืน</th>
                            <th class="table-header">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($requests as $request): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="table-cell font-medium">
                                    #<?php echo str_pad($request['request_id'], 5, '0', STR_PAD_LEFT); ?>
                                </td>
                                <td class="table-cell">
                                    <div class="font-medium"><?php echo htmlspecialchars($request['item_name']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($request['category']); ?></div>
                                </td>
                                <td class="table-cell text-center">
                                    <?php echo $request['quantity']; ?>
                                </td>
                                <td class="table-cell">
                                    <?php echo $request['formatted_borrow_date']; ?>
                                </td>
                                <td class="table-cell">
                                    <?php echo $request['formatted_return_date']; ?>
                                </td>
                                <td class="table-cell">
                                    <?php echo $request['formatted_returned_at'] ?? '-'; ?>
                                </td>
                                <td class="table-cell">
                                    <?php
                                    $status_classes = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'borrowed' => 'bg-blue-100 text-blue-800',
                                        'returned' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $status_text = [
                                        'pending' => 'รออนุมัติ',
                                        'approved' => 'อนุมัติแล้ว',
                                        'rejected' => 'ไม่อนุมัติ',
                                        'borrowed' => 'ยืมอยู่',
                                        'returned' => 'คืนแล้ว'
                                    ];
                                    ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                               <?php echo $status_classes[$request['status']]; ?>">
                                        <?php echo $status_text[$request['status']]; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-history text-4xl mb-4"></i>
                <p>ไม่พบประวัติการยืม</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/templates/footer.php'; ?>