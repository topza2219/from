<?php
require_once dirname(__FILE__) . '/auth_check.php';
require_once dirname(__FILE__) . '/../config.php';

$page_title = "รายการอุปกรณ์";
require_once dirname(__FILE__) . '/templates/header.php';
require_once dirname(__FILE__) . '/templates/sidebar.php';

try {
    // Get filter parameters
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : 'available';

    // Base query
    $query = "SELECT i.*, 
              (SELECT COUNT(*) FROM borrow_requests WHERE item_id = i.item_id AND status = 'borrowed') as borrowed_count
              FROM items i 
              WHERE 1=1";
    
    $params = [];
    $types = '';

    // Add filters
    if ($category) {
        $query .= " AND i.category = ?";
        $params[] = $category;
        $types .= 's';
    }
    
    if ($search) {
        $query .= " AND (i.item_name LIKE ? OR i.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $types .= 'ss';
    }
    
    if ($status === 'available') {
        $query .= " AND i.quantity > 0 AND i.status = 'available'";
    }

    $query .= " ORDER BY i.category, i.item_name";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        throw new Exception('Database error: ' . mysqli_error($conn));
    }

    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Get unique categories for filter
    $cat_query = "SELECT DISTINCT category FROM items ORDER BY category";
    $categories = mysqli_query($conn, $cat_query);
    $categories = mysqli_fetch_all($categories, MYSQLI_ASSOC);

} catch (Exception $e) {
    error_log($e->getMessage());
    $error = 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $e->getMessage();
    $items = [];
    $categories = [];
}
?>

<div class="main-content p-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">รายการอุปกรณ์</h1>
            
            <!-- Filters -->
            <form class="flex items-center space-x-4">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                       class="form-input" placeholder="ค้นหาอุปกรณ์...">
                
                <select name="category" class="form-input">
                    <option value="">หมวดหมู่ทั้งหมด</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['category']); ?>"
                                <?php echo $category == $cat['category'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['category']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="status" class="form-input">
                    <option value="available" <?php echo $status == 'available' ? 'selected' : ''; ?>>
                        มีให้ยืม
                    </option>
                    <option value="all" <?php echo $status == 'all' ? 'selected' : ''; ?>>
                        ทั้งหมด
                    </option>
                </select>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search mr-2"></i>ค้นหา
                </button>
            </form>
        </div>

        <?php if (!empty($items)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($items as $item): ?>
                    <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900">
                                    <?php echo htmlspecialchars($item['item_name']); ?>
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <?php echo htmlspecialchars($item['description']); ?>
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                       <?php echo $item['quantity'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo $item['quantity'] > 0 ? 'พร้อมให้ยืม' : 'ไม่พร้อมให้ยืม'; ?>
                            </span>
                        </div>
                        
                        <div class="mt-4 flex justify-between items-center">
                            <div class="text-sm">
                                <p class="text-gray-600">
                                    หมวดหมู่: <span class="font-medium"><?php echo htmlspecialchars($item['category']); ?></span>
                                </p>
                                <p class="text-gray-600">
                                    จำนวนคงเหลือ: <span class="font-medium"><?php echo $item['quantity']; ?></span>
                                </p>
                                <p class="text-gray-600">
                                    กำลังถูกยืม: <span class="font-medium"><?php echo $item['borrowed_count']; ?></span>
                                </p>
                            </div>
                            
                            <?php if ($item['quantity'] > 0): ?>
                                <a href="new_request.php?item_id=<?php echo $item['item_id']; ?>" 
                                   class="btn btn-primary">
                                    <i class="fas fa-hand-holding mr-2"></i>ขอยืม
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-box-open text-4xl mb-4"></i>
                <p>ไม่พบอุปกรณ์</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/templates/footer.php'; ?>