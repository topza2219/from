<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';
require_once __DIR__ . '/api/get_borrowers.php';

// Fetch borrowers data
$borrowers = get_borrowers();
?>

<div class="max-w-screen-2xl mx-auto" style="padding-top: 3%;"> <!-- เพิ่ม padding-top -->
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">จัดการผู้ยืม</h1>
            <button onclick="document.getElementById('addBorrowerModal').classList.remove('hidden')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg shadow-sm text-sm">
                <i class="fas fa-plus mr-2"></i>เพิ่มผู้ยืมใหม่
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <!-- Total Borrowers Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">ผู้ยืมทั้งหมด</h3>
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900"><?php echo count($borrowers); ?></p>
                <p class="text-sm text-gray-500 mt-2">จำนวนผู้ยืมในระบบ</p>
            </div>

            <!-- Active Borrowers -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">ผู้ยืมที่กำลังยืม</h3>
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-user-check text-green-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900">
                    <?php echo array_reduce($borrowers, function($carry, $item) {
                        return $carry + ($item['status'] === 'active' ? 1 : 0);
                    }, 0); ?>
                </p>
                <p class="text-sm text-gray-500 mt-2">ผู้ยืมที่มีการยืมอยู่</p>
            </div>

            <!-- Total Borrows -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">จำนวนการยืมรวม</h3>
                    <div class="bg-purple-100 rounded-full p-3">
                        <i class="fas fa-chart-line text-purple-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900">
                    <?php echo array_reduce($borrowers, function($carry, $item) {
                        return $carry + $item['total_borrows'];
                    }, 0); ?>
                </p>
                <p class="text-sm text-gray-500 mt-2">จำนวนการยืมทั้งหมด</p>
            </div>
        </div>

        <!-- Borrowers Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
            <div class="p-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">รายชื่อผู้ยืมทั้งหมด</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">รหัส</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ชื่อ-นามสกุล</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">อีเมล</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">จำนวนการยืม</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">สถานะ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php if (!empty($borrowers)): ?>
                            <?php foreach ($borrowers as $borrower): ?>
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($borrower['id']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-500"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($borrower['name']); ?>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($borrower['email']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <i class="fas fa-book mr-2 text-gray-400"></i>
                                            <?php echo htmlspecialchars($borrower['total_borrows']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo $borrower['status'] === 'active' ? 
                                                'bg-green-100 text-green-800 border border-green-200' : 
                                                'bg-red-100 text-red-800 border border-red-200'; ?>">
                                            <?php echo htmlspecialchars($borrower['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                        <a href="edit_borrower.php?id=<?php echo $borrower['id']; ?>" 
                                           class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                            <i class="fas fa-edit mr-1"></i> แก้ไข
                                        </a>
                                        <a href="delete_borrower.php?id=<?php echo $borrower['id']; ?>" 
                                           class="text-red-600 hover:text-red-900 inline-flex items-center"
                                           onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบผู้ยืมนี้?')">
                                            <i class="fas fa-trash-alt mr-1"></i> ลบ
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex flex-col items-center py-8">
                                        <i class="fas fa-users text-gray-400 text-5xl mb-4"></i>
                                        <p class="text-lg">ไม่พบข้อมูลผู้ยืม</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Add Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    *
    .flex-1 {
        margin-left: 300px;
    }
    .dashboard-card {
        animation: fadeIn 0.5s ease-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .table-wrapper {
        animation: slideIn 0.5s ease-out forwards;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-10px); }
        to { opacity: 1; transform: translateX(0); }
    }

    /* Hover Effects */
    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.1);
    }

    /* Status Badge Styles */
    .status-active {
        background-color: rgba(16, 185, 129, 0.1);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .status-inactive {
        background-color: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Responsive Layout */
    @media (max-width: 1536px) {
        .max-w-screen-2xl {
            max-width: 100%;
            padding: 0 1rem;
        }
    }

    @media (max-width: 1280px) {
        .ml-64 {
            margin-left: 16rem;
        }
    }

    @media (max-width: 1024px) {
        .grid-cols-3 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .ml-64 {
            margin-left: 0;
        }
        
        .grid-cols-2 {
            grid-template-columns: 1fr;
        }
        
        .p-8 {
            padding: 1rem;
        }
        
        .text-3xl {
            font-size: 1.5rem;
        }
    }

    /* Table Responsive */
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }

    /* Card Animations */
    .dashboard-card {
        animation: fadeIn 0.5s ease-out forwards;
        min-width: 250px;
    }

    /* Compact Table */
    @media (max-width: 640px) {
        .px-6 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .py-4 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        
        .text-sm {
            font-size: 0.8125rem;
        }
    }

    /* Status Badge Compact */
    .status-active, .status-inactive {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    /* Action Buttons Compact */
    @media (max-width: 480px) {
        .space-x-3 > * + * {
            margin-left: 0.5rem;
        }
        
        .px-4 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
    }
</style>

<?php
//include_once 'templates/footer.php';
?>