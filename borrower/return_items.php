<?php
require_once dirname(__FILE__) . '/auth_check.php';
require_once dirname(__FILE__) . '/../config.php';

$page_title = "อุปกรณ์ที่ต้องคืน";
require_once dirname(__FILE__) . '/templates/header.php';
require_once dirname(__FILE__) . '/templates/sidebar.php';


try {
    $query = "SELECT br.*, i.item_name,
              DATE_FORMAT(br.borrow_date, '%d/%m/%Y') as formatted_borrow_date,
              DATE_FORMAT(br.expected_return_date, '%d/%m/%Y') as formatted_return_date
              FROM borrow_requests br
              LEFT JOIN items i ON br.item_id = i.item_id
              WHERE br.borrower_id = ? 
              AND br.status = 'borrowed'
              AND br.expected_return_date <= CURDATE()
              ORDER BY br.expected_return_date ASC";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
} catch (Exception $e) {
    error_log($e->getMessage());
    $items = [];
}
?>

<div class="container mx-auto px-4 pt-16">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">อุปกรณ์ที่ต้องคืน</h1>
        
        <?php if (!empty($items)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">อุปกรณ์</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">วันที่ยืม</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">กำหนดคืน</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($item['item_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo $item['formatted_borrow_date']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo $item['formatted_return_date']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="returnItem(<?php echo $item['request_id']; ?>)" 
                                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        คืนอุปกรณ์
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-500 text-center py-4">ไม่มีอุปกรณ์ที่ต้องคืน</p>
        <?php endif; ?>
    </div>
</div>

<script>
function returnItem(requestId) {
    Swal.fire({
        title: 'ยืนยันการคืนอุปกรณ์',
        text: 'คุณต้องการคืนอุปกรณ์นี้ใช่หรือไม่?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ใช่, คืนอุปกรณ์',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `process_return.php?request_id=${requestId}`;
        }
    });
}
</script>

<?php require_once dirname(__FILE__) . '/templates/footer.php'; ?>