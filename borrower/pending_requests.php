<?php
require_once dirname(__FILE__) . '/auth_check.php';
require_once dirname(__FILE__) . '/../config.php';

$page_title = "คำขอที่รออนุมัติ";
require_once dirname(__FILE__) . '/templates/header.php';
require_once dirname(__FILE__) . '/templates/sidebar.php';

try {
    // Fetch pending requests with item details
    $query = "SELECT br.*, 
              i.item_name,
              i.description,
              i.category,
              DATE_FORMAT(br.created_at, '%d/%m/%Y %H:%i') as formatted_created_at,
              DATE_FORMAT(br.borrow_date, '%d/%m/%Y') as formatted_borrow_date,
              DATE_FORMAT(br.expected_return_date, '%d/%m/%Y') as formatted_return_date
              FROM borrow_requests br
              LEFT JOIN items i ON br.item_id = i.item_id
              WHERE br.borrower_id = ? 
              AND br.status = 'pending'
              ORDER BY br.created_at DESC";

    $stmt = mysqli_prepare($conn, $query);
    if ($stmt === false) {
        throw new Exception('Failed to prepare statement: ' . mysqli_error($conn));
    }

    if (!mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id'])) {
        throw new Exception('Failed to bind parameters: ' . mysqli_stmt_error($stmt));
    }

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Failed to execute query: ' . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_get_result($stmt);
    if ($result === false) {
        throw new Exception('Failed to get result: ' . mysqli_stmt_error($stmt));
    }

    $requests = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);
    mysqli_free_result($result);

} catch (Exception $e) {
    error_log('Error in pending_requests.php: ' . $e->getMessage());
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
            <h1 class="text-2xl font-bold text-gray-900">คำขอที่รออนุมัติ</h1>
            <a href="new_request.php" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>ขอยืมใหม่
            </a>
        </div>

        <?php if (!empty($requests)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="table-header">รหัสคำขอ</th>
                            <th class="table-header">อุปกรณ์</th>
                            <th class="table-header">หมวดหมู่</th>
                            <th class="table-header">จำนวน</th>
                            <th class="table-header">วันที่ยืม</th>
                            <th class="table-header">วันที่คืน</th>
                            <th class="table-header">วันที่ขอ</th>
                            <th class="table-header">การดำเนินการ</th>
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
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($request['description']); ?></div>
                                </td>
                                <td class="table-cell">
                                    <?php echo htmlspecialchars($request['category']); ?>
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
                                    <?php echo $request['formatted_created_at']; ?>
                                </td>
                                <td class="table-cell">
                                    <button onclick="cancelRequest(<?php echo $request['request_id']; ?>)" 
                                            class="btn btn-danger">
                                        ยกเลิกคำขอ
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-4"></i>
                <p>ไม่มีคำขอที่รออนุมัติ</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function cancelRequest(requestId) {
    Swal.fire({
        title: 'ยืนยันการยกเลิก',
        text: 'คุณต้องการยกเลิกคำขอยืมนี้ใช่หรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ใช่, ยกเลิกคำขอ',
        cancelButtonText: 'ไม่ใช่',
        confirmButtonColor: '#dc2626'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `cancel_request.php?id=${requestId}`;
        }
    });
}
</script>

<?php require_once dirname(__FILE__) . '/templates/footer.php'; ?>