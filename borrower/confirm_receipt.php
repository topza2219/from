<?php
require_once dirname(__FILE__) . '/auth_check.php';
require_once dirname(__FILE__) . '/../config.php';

$page_title = "ยืนยันการรับอุปกรณ์";
require_once dirname(__FILE__) . '/templates/header.php';
require_once dirname(__FILE__) . '/templates/sidebar.php';

// Get request ID from URL
$request_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

try {
    if (!$request_id) {
        throw new Exception('ไม่พบรหัสคำขอ');
    }

    // Fetch request details
    $query = "SELECT br.*, 
              i.item_name,
              i.description,
              i.category,
              DATE_FORMAT(br.borrow_date, '%d/%m/%Y') as formatted_borrow_date,
              DATE_FORMAT(br.expected_return_date, '%d/%m/%Y') as formatted_return_date
              FROM borrow_requests br
              LEFT JOIN items i ON br.item_id = i.item_id
              WHERE br.request_id = ? 
              AND br.borrower_id = ?
              AND br.status = 'approved'
              LIMIT 1";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        throw new Exception('Database error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, 'ii', $request_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $request = mysqli_fetch_assoc($result);

    if (!$request) {
        throw new Exception('ไม่พบคำขอหรือคุณไม่มีสิทธิ์ในการยืนยันการรับ');
    }

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: approved_requests.php');
    exit();
}
?>

<div class="main-content p-6">
    <div class="stat-card">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">ยืนยันการรับอุปกรณ์</h1>
            <p class="text-gray-600 mt-2">กรุณาตรวจสอบรายละเอียดก่อนยืนยันการรับ</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h2 class="font-semibold text-gray-700">รายละเอียดการยืม</h2>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="text-sm text-gray-600">รหัสคำขอ:</label>
                            <p class="font-medium">#<?php echo str_pad($request['request_id'], 5, '0', STR_PAD_LEFT); ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">อุปกรณ์:</label>
                            <p class="font-medium"><?php echo htmlspecialchars($request['item_name']); ?></p>
                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($request['description']); ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">จำนวน:</label>
                            <p class="font-medium"><?php echo $request['quantity']; ?> ชิ้น</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-700">กำหนดการ</h2>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="text-sm text-gray-600">วันที่ยืม:</label>
                            <p class="font-medium"><?php echo $request['formatted_borrow_date']; ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">กำหนดคืน:</label>
                            <p class="font-medium text-red-600"><?php echo $request['formatted_return_date']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="process_receipt.php" method="POST" class="mt-8">
                <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">หมายเหตุ (ถ้ามี)</label>
                    <textarea name="receipt_notes" rows="3" class="form-input w-full"
                        placeholder="บันทึกเพิ่มเติมเกี่ยวกับการรับอุปกรณ์"></textarea>
                </div>

                <div class="border-t pt-6 flex justify-end space-x-4">
                    <a href="approved_requests.php" class="btn btn-secondary">
                        ยกเลิก
                    </a>
                    <button type="submit" class="btn btn-primary">
                        ยืนยันการรับอุปกรณ์
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/templates/footer.php'; ?>
<td class="table-cell">
    <a href="confirm_receipt.php?id=<?php echo $request['request_id']; ?>" 
       class="btn btn-success">
        ยืนยันการรับ
    </a>
</td>