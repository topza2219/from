<?php
require_once dirname(__FILE__) . '/auth_check.php';
require_once dirname(__FILE__) . '/../config.php';

$page_title = "คำขอที่อนุมัติแล้ว";
require_once dirname(__FILE__) . '/templates/header.php';
require_once dirname(__FILE__) . '/templates/sidebar.php';

try {
    $query = "SELECT br.*, 
              i.item_name,
              i.description,
              i.category,
              u.full_name as approver_name,
              DATE_FORMAT(br.created_at, '%d/%m/%Y %H:%i') as formatted_created_at,
              DATE_FORMAT(br.approved_at, '%d/%m/%Y %H:%i') as formatted_approved_at,
              DATE_FORMAT(br.borrow_date, '%d/%m/%Y') as formatted_borrow_date,
              DATE_FORMAT(br.expected_return_date, '%d/%m/%Y') as formatted_return_date
              FROM borrow_requests br
              LEFT JOIN items i ON br.item_id = i.item_id
              LEFT JOIN users u ON br.approver_id = u.user_id
              WHERE br.borrower_id = ? 
              AND br.status = 'approved'
              ORDER BY br.approved_at DESC";

    // Prepare statement with error checking
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt === false) {
        throw new Exception('Failed to prepare statement: ' . mysqli_error($conn));
    }

    // Bind parameters with error checking
    if (!mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id'])) {
        throw new Exception('Failed to bind parameters: ' . mysqli_stmt_error($stmt));
    }

    // Execute with error checking
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Failed to execute query: ' . mysqli_stmt_error($stmt));
    }

    // Get results with error checking
    $result = mysqli_stmt_get_result($stmt);
    if ($result === false) {
        throw new Exception('Failed to get result: ' . mysqli_stmt_error($stmt));
    }

    $requests = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Clean up
    mysqli_stmt_close($stmt);
    mysqli_free_result($result);

} catch (Exception $e) {
    error_log('Error in approved_requests.php: ' . $e->getMessage());
    $error = 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $e->getMessage();
    $requests = [];
}

// Show error message if exists
if (isset($error)): ?>
    <div class="main-content p-6">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">ผิดพลาด!</strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
        </div>
    </div>
    <?php return;
endif;
?>

<div class="main-content p-6">
    <div class="stat-card">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">คำขอที่อนุมัติแล้ว</h1>
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
                            <th class="table-header">ผู้อนุมัติ</th>
                            <th class="table-header">วันที่อนุมัติ</th>
                            <th class="table-header">หมายเหตุ</th>
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
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($request['item_code']); ?></div>
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
                                    <?php echo htmlspecialchars($request['approver_name']); ?>
                                </td>
                                <td class="table-cell">
                                    <?php echo $request['formatted_approved_at']; ?>
                                </td>
                                <td class="table-cell text-gray-600">
                                    <?php echo htmlspecialchars($request['approval_notes'] ?? '-'); ?>
                                </td>
                                <td class="table-cell">
                                    <?php if (strtotime('now') > strtotime($request['expected_return_date'])): ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            เลยกำหนดคืน
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            อยู่ระหว่างการยืม
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-check-circle text-4xl mb-4"></i>
                <p>ไม่มีคำขอที่อนุมัติ</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/templates/footer.php'; ?>