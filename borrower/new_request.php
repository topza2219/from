<?php
ob_start(); // Start output buffering

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__FILE__) . '/auth_check.php';
require_once dirname(__FILE__) . '/../config.php';

$page_title = "ขอยืมอุปกรณ์";
require_once dirname(__FILE__) . '/templates/header.php';
require_once dirname(__FILE__) . '/templates/sidebar.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate input
        $item_id = $_POST['item_id'] ?? null;
        $quantity = $_POST['quantity'] ?? null;
        $borrow_date = $_POST['borrow_date'] ?? null;
        $return_date = $_POST['return_date'] ?? null;
        $notes = $_POST['notes'] ?? null;

        if (!$item_id || !$quantity || !$borrow_date || !$return_date) {
            throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
        }

        // Insert into borrow_requests
        $query = "INSERT INTO borrow_requests 
                  (borrower_id, item_id, quantity, borrow_date, expected_return_date, status, notes, created_at) 
                  VALUES (?, ?, ?, ?, ?, 'pending', ?, NOW())";

        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            throw new Exception('Failed to prepare statement: ' . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, 'iiisss', $_SESSION['user_id'], $item_id, $quantity, $borrow_date, $return_date, $notes);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Failed to execute statement: ' . mysqli_stmt_error($stmt));
        }

        // After successfully inserting the borrow request
        $request_id = mysqli_insert_id($conn); // Get the last inserted request ID

        // Fetch all approvers
        $approvers_query = "SELECT user_id FROM users WHERE role = 'approver' AND status = 'active'";
        $approvers_result = mysqli_query($conn, $approvers_query);

        if ($approvers_result) {
            while ($approver = mysqli_fetch_assoc($approvers_result)) {
                $notification_query = "INSERT INTO notifications (user_id, request_id, message) 
                                       VALUES (?, ?, ?)";
                $notification_stmt = mysqli_prepare($conn, $notification_query);
                $message = "คำขอยืมใหม่จาก " . $_SESSION['user_id'];
                mysqli_stmt_bind_param($notification_stmt, 'iis', $approver['user_id'], $request_id, $message);
                mysqli_stmt_execute($notification_stmt);
            }
        }

        // Success
        $_SESSION['success_message'] = 'คำขอยืมถูกบันทึกเรียบร้อยแล้ว';
        header('Location: new_request.php');
        exit();
    }
} catch (Exception $e) {
    error_log('Error in new_request.php: ' . $e->getMessage());
    $_SESSION['error_message'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
    header('Location: new_request.php');
    exit();
}

// Fetch available items
try {
    $query = "SELECT i.*, c.category_name 
              FROM items i 
              LEFT JOIN categories c ON i.category_id = c.category_id
              WHERE i.status = 'available'
              AND i.quantity > 0
              ORDER BY c.category_name, i.item_name";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        throw new Exception('Failed to prepare query: ' . mysqli_error($conn));
    }

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Failed to execute query: ' . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        throw new Exception('Failed to get result: ' . mysqli_stmt_error($stmt));
    }

    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Group items by category
    $categories = [];
    foreach ($items as $item) {
        $category = $item['category_name'] ?? 'ไม่มีหมวดหมู่';
        $categories[$category][] = $item;
    }

    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
} catch (Exception $e) {
    error_log('Error in fetching items: ' . $e->getMessage());
    $categories = [];
    $_SESSION['error_message'] = 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $e->getMessage();
}
?>

<!-- Display success or error messages -->
<?php if (isset($_SESSION['success_message'])): ?>
<div class="alert alert-success">
    <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
<div class="alert alert-danger">
    <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
</div>
<?php endif; ?>

<!-- Form for new request -->
<div class="main-content p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm">
        <!-- Header Section -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-hand-holding mr-3 text-blue-600"></i>ขอยืมอุปกรณ์
                </h1>
                <a href="item_list.php" class="text-sm text-blue-600 hover:text-blue-800">
                    <i class="fas fa-search mr-1"></i>ดูรายการอุปกรณ์ทั้งหมด
                </a>
            </div>
        </div>

        <!-- Form Section -->
        <div class="p-6">
            <form id="borrowForm" action="new_request.php" method="POST" class="space-y-8">
                <!-- Item Selection -->
                <div class="form-group">
                    <label for="item_id" class="form-label required text-gray-700">
                        <i class="fas fa-box-open mr-2 text-gray-400"></i>เลือกอุปกรณ์
                    </label>
                    <select name="item_id" id="item_id" class="form-input mt-2 bg-gray-50 focus:bg-white" required>
                        <option value="">-- เลือกอุปกรณ์ --</option>
                        <?php foreach ($categories as $category => $items): ?>
                            <optgroup label="<?php echo htmlspecialchars($category); ?>" class="font-medium">
                                <?php foreach ($items as $item): ?>
                                    <option value="<?php echo $item['item_id']; ?>" 
                                            data-max="<?php echo $item['quantity']; ?>">
                                        <?php echo htmlspecialchars($item['item_name']); ?>
                                        (คงเหลือ: <?php echo $item['quantity']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Quantity and Dates Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Quantity -->
                    <div class="form-group">
                        <label for="quantity" class="form-label required text-gray-700">
                            <i class="fas fa-sort-amount-up mr-2 text-gray-400"></i>จำนวน
                        </label>
                        <input type="number" name="quantity" id="quantity" 
                               class="form-input mt-2 bg-gray-50 focus:bg-white" 
                               min="1" required>
                        <p class="mt-1 text-sm text-gray-500" id="quantity-helper"></p>
                    </div>

                    <!-- Borrow Date -->
                    <div class="form-group">
                        <label for="borrow_date" class="form-label required text-gray-700">
                            <i class="fas fa-calendar mr-2 text-gray-400"></i>วันที่ต้องการยืม
                        </label>
                        <input type="date" name="borrow_date" id="borrow_date" 
                               class="form-input mt-2 bg-gray-50 focus:bg-white" 
                               required min="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <!-- Return Date -->
                    <div class="form-group">
                        <label for="return_date" class="form-label required text-gray-700">
                            <i class="fas fa-calendar-check mr-2 text-gray-400"></i>วันที่จะคืน
                        </label>
                        <input type="date" name="return_date" id="return_date" 
                               class="form-input mt-2 bg-gray-50 focus:bg-white" 
                               required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label for="notes" class="form-label text-gray-700">
                        <i class="fas fa-comment-alt mr-2 text-gray-400"></i>หมายเหตุ
                    </label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="form-input mt-2 bg-gray-50 focus:bg-white resize-none"
                              placeholder="ระบุรายละเอียดเพิ่มเติม (ถ้ามี)"></textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="history.back()" 
                                class="btn btn-secondary transition-colors duration-150">
                            <i class="fas fa-times mr-2"></i>ยกเลิก
                        </button>
                        <button type="submit" class="btn btn-primary transition-colors duration-150">
                            <i class="fas fa-paper-plane mr-2"></i>ส่งคำขอยืม
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('borrowForm');
    const quantityInput = document.getElementById('quantity');
    const itemSelect = document.getElementById('item_id');
    const borrowDate = document.getElementById('borrow_date');
    const returnDate = document.getElementById('return_date');

    // Validate dates
    borrowDate.addEventListener('change', function() {
        returnDate.min = borrowDate.value;
        if (returnDate.value < borrowDate.value) {
            returnDate.value = borrowDate.value;
        }
    });

    // Add quantity helper text
    itemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const maxQuantity = selectedOption.dataset.max;
        const helper = document.getElementById('quantity-helper');
        if (maxQuantity) {
            helper.textContent = `สูงสุด ${maxQuantity} ชิ้น`;
            quantityInput.max = maxQuantity;
        }
    });

    // Enhanced form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Basic validation
        if (!itemSelect.value) {
                Swal.fire({
                title: 'กรุณาเลือกอุปกรณ์',
                icon: 'warning',
                confirmButtonText: 'ตกลง',
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
            return;
        }

        if (quantityInput.value < 1) {
            Swal.fire('จำนวนต้องมากกว่า 0', '', 'warning');
            return;
        }

        if (!borrowDate.value || !returnDate.value) {
            Swal.fire('กรุณาระบุวันที่ยืมและวันที่คืน', '', 'warning');
            return;
        }

        // Submit form if validation passes
        Swal.fire({
            title: 'ยืนยันการส่งคำขอ',
            text: 'คุณต้องการส่งคำขอยืมอุปกรณ์นี้ใช่หรือไม่?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ใช่, ส่งคำขอ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Enhanced validation
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        try {
            // Validate item selection
            if (!itemSelect.value) {
                throw new Error('กรุณาเลือกอุปกรณ์');
            }

            // Validate quantity
            if (!quantityInput.value || quantityInput.value < 1) {
                throw new Error('กรุณาระบุจำนวนที่ถูกต้อง');
            }

            const maxQty = parseInt(itemSelect.options[itemSelect.selectedIndex].dataset.max);
            if (parseInt(quantityInput.value) > maxQty) {
                throw new Error(`จำนวนต้องไม่เกิน ${maxQty} ชิ้น`);
            }

            // Validate dates
            if (!borrowDate.value || !returnDate.value) {
                throw new Error('กรุณาระบุวันที่ยืมและวันที่คืน');
            }

            if (new Date(returnDate.value) <= new Date(borrowDate.value)) {
                throw new Error('วันที่คืนต้องมากกว่าวันที่ยืม');
            }

            // Confirm submission
            const result = await Swal.fire({
                title: 'ยืนยันการส่งคำขอ',
                text: 'คุณต้องการส่งคำขอยืมอุปกรณ์นี้ใช่หรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ใช่, ส่งคำขอ',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            });

            if (result.isConfirmed) {
                form.submit();
            }

        } catch (error) {
            Swal.fire({
                title: 'ข้อผิดพลาด',
                text: error.message,
                icon: 'error',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#3085d6'
            });
        }
    });

    // ... rest of your existing event listeners ...
});
</script>

<?php
ob_end_flush(); // Send output to the browser
require_once dirname(__FILE__) . '/templates/footer.php'; 
?>
