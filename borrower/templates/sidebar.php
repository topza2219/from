<!-- Mobile Menu -->
<div class="md:hidden">
    <div class="fixed inset-0 flex z-40 lg:hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true"></div>
        
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <nav class="mt-5 px-2 space-y-1">
                    <a href="index.php" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-900 hover:bg-gray-50">
                        <i class="fas fa-home mr-4 text-gray-400"></i>
                        หน้าหลัก
                    </a>
                    <a href="new_request.php" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-900 hover:bg-gray-50">
                        <i class="fas fa-plus-circle mr-4 text-gray-400"></i>
                        ขอยืมอุปกรณ์
                    </a>
                    <a href="history.php" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-900 hover:bg-gray-50">
                        <i class="fas fa-history mr-4 text-gray-400"></i>
                        ประวัติการยืม
                    </a>
                </nav>
            </div>
            <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                <div class="flex items-center">
                    <div>
                        <i class="fas fa-user-circle text-2xl text-gray-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-base font-medium text-gray-700"><?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
                        <a href="../logout.php" class="text-sm font-medium text-gray-500 hover:text-gray-700">ออกจากระบบ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg hidden lg:block pt-16">
    <div class="overflow-y-auto h-full">
        <!-- User Profile -->
        <div class="p-4 border-b">
            <div class="flex items-center">
                <i class="fas fa-user-circle text-3xl text-gray-400"></i>
                <div class="ml-3">
                    <p class="font-medium text-gray-800"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'ผู้ใช้งาน'); ?></p>
                    <p class="text-sm text-gray-500">ผู้ยืม</p>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="mt-4">
            <div class="px-4 space-y-2">
                <!-- หน้าหลัก -->
                <a href="dashboard.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg
                    <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-home w-6"></i>
                    <span>หน้าหลัก</span>
                </a>

                <!-- โปรไฟล์ -->
                <a href="profile.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg
                    <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-user w-6"></i>
                    <span>โปรไฟล์</span>
                </a>

                <!-- ขอยืมอุปกรณ์ -->
                <a href="new_request.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg
                    <?php echo basename($_SERVER['PHP_SELF']) == 'new_request.php' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-plus-circle w-6"></i>
                    <span>ขอยืมอุปกรณ์</span>
                </a>

                <!-- คำขอที่รออนุมัติ -->
                <a href="pending_requests.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg relative
                    <?php echo basename($_SERVER['PHP_SELF']) == 'pending_requests.php' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-clock w-6"></i>
                    <span>รออนุมัติ</span>
                    <?php if ($pending_count > 0): ?>
                    <span class="ml-auto bg-yellow-500 text-white text-xs rounded-full px-2 py-1">
                        <?php echo $pending_count; ?>
                    </span>
                    <?php endif; ?>
                </a>

                <!-- คำขอที่อนุมัติแล้ว -->
                <a href="approved_requests.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg relative
                    <?php echo basename($_SERVER['PHP_SELF']) == 'approved_requests.php' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-check-circle w-6"></i>
                    <span>อนุมัติแล้ว</span>
                    <?php if ($approved_count > 0): ?>
                    <span class="ml-auto bg-green-500 text-white text-xs rounded-full px-2 py-1">
                        <?php echo $approved_count; ?>
                    </span>
                    <?php endif; ?>
                </a>

                <!-- คำขอที่ถูกปฏิเสธ -->
                <a href="rejected_requests.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg relative
                    <?php echo basename($_SERVER['PHP_SELF']) == 'rejected_requests.php' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-times-circle w-6"></i>
                    <span>ปฏิเสธแล้ว</span>
                    <?php if ($rejected_count > 0): ?>
                    <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">
                        <?php echo $rejected_count; ?>
                    </span>
                    <?php endif; ?>
                </a>

                <!-- อุปกรณ์ที่ต้องคืน -->
                <a href="return_items.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg relative
                    <?php echo basename($_SERVER['PHP_SELF']) == 'return_items.php' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-undo w-6"></i>
                    <span>อุปกรณ์ที่ต้องคืน</span>
                    <?php if ($return_count > 0): ?>
                    <span class="ml-auto bg-blue-500 text-white text-xs rounded-full px-2 py-1">
                        <?php echo $return_count; ?>
                    </span>
                    <?php endif; ?>
                </a>

                <!-- ประวัติการยืม -->
                <a href="borrow_history.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg
                    <?php echo basename($_SERVER['PHP_SELF']) == 'borrow_history.php' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-history w-6"></i>
                    <span>ประวัติการยืม</span>
                </a>
                
                <!-- รายการอุปกรณ์ -->
                <a href="item_list.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg
                    <?php echo basename($_SERVER['PHP_SELF']) == 'item_list.php' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-box w-6"></i>
                    <span>รายการอุปกรณ์</span>
                </a>
            </div>
        </nav>

        <!-- Logout -->
        <div class="absolute bottom-0 w-full p-4 border-t">
            <a href="../logout.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span>ออกจากระบบ</span>
            </a>
        </div>
    </div>
</div>

<!-- Mobile menu button -->
<div class="lg:hidden fixed top-4 left-4 z-20">
    <button type="button" class="mobile-menu-button p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
        <i class="fas fa-bars"></i>
    </button>
</div>

<!-- Mobile menu (hidden by default) -->
<div class="mobile-menu lg:hidden fixed inset-0 z-10 bg-gray-600 bg-opacity-75 hidden">
    <div class="fixed inset-y-0 left-0 w-64 bg-white">
        <div class="h-16 flex items-center justify-between px-4 border-b">
            <h2 class="text-xl font-bold text-gray-800">เมนู</h2>
            <button type="button" class="mobile-menu-close p-2 text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Mobile Navigation -->
        <nav class="mt-6">
            <div class="px-4 space-y-2">
                <!-- เมนูเหมือนด้านบน -->
                <a href="dashboard.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-home mr-3"></i>
                    <span>หน้าหลัก</span>
                </a>
                <a href="new_request.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-plus-circle mr-3"></i>
                    <span>ขอยืมอุปกรณ์</span>
                </a>
                <a href="history.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-history mr-3"></i>
                    <span>ประวัติการยืม</span>
                </a>
            </div>
        </nav>
    </div>
</div>

<script>
// Toggle mobile menu
document.querySelector('.mobile-menu-button')?.addEventListener('click', function() {
    document.querySelector('.mobile-menu').classList.remove('hidden');
});

document.querySelector('.mobile-menu-close')?.addEventListener('click', function() {
    document.querySelector('.mobile-menu').classList.add('hidden');
});
</script>
<style>
    .flex-1 {
        flex: 300px;
    }

</style>