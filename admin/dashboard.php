<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';
require_once __DIR__ . '/api/get_dashboard_stats.php';

// Get dashboard statistics with date filter
$start_date = isset($_GET['start']) ? $_GET['start'] : null;
$end_date = isset($_GET['end']) ? $_GET['end'] : null;
$stats = getDashboardStats($start_date, $end_date);
?>

<!-- Add required libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.4.1/socket.io.js"></script>

<div class="flex-1 p-8 ml-48">
    <!-- Header with Export Buttons -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">แดชบอร์ดผู้ดูแลระบบ</h1>
        <div class="space-x-4">
            <button onclick="exportReport('csv')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm">
                <i class="fas fa-file-csv mr-2"></i>Export CSV
            </button>
            <button onclick="exportReport('pdf')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-sm">
                <i class="fas fa-file-pdf mr-2"></i>Export PDF
            </button>
        </div>
    </div>

    <!-- Date Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-8">
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">จากวันที่:</label>
                <input type="date" id="startDate" value="<?php echo $start_date; ?>" 
                       class="border rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">ถึงวันที่:</label>
                <input type="date" id="endDate" value="<?php echo $end_date; ?>"
                       class="border rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button onclick="filterStats()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow-sm">
                <i class="fas fa-filter mr-2"></i>กรองข้อมูล
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <section class="dashboard-grid mb-8">
        <?php
        $cards = [
            ['link' => 'manage_borrows.php', 'title' => 'การยืมทั้งหมด', 'count' => $stats['total_borrows'], 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'bgColor' => 'bg-blue-100', 'textColor' => 'text-blue-600'],
            ['link' => 'manage_approvals.php', 'title' => 'รอการอนุมัติ', 'count' => $stats['pending_approvals'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'bgColor' => 'bg-amber-100', 'textColor' => 'text-amber-600'],
            ['link' => 'manage_items.php', 'title' => 'อุปกรณ์ทั้งหมด', 'count' => $stats['total_items'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M19 13l-4 4m0 0l-4-4m4 4V7', 'bgColor' => 'bg-emerald-100', 'textColor' => 'text-emerald-600'],
            ['link' => 'manage_users.php', 'title' => 'ผู้ใช้งานทั้งหมด', 'count' => $stats['total_users'], 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'bgColor' => 'bg-violet-100', 'textColor' => 'text-violet-600'],
        ];

        foreach ($cards as $card) {
            echo '
            <a href="' . $card['link'] . '" class="dashboard-card bg-white hover:bg-gray-50" aria-label="' . $card['title'] . '">
                <div class="header">
                    <div class="title">' . $card['title'] . '</div>
                    <div class="count">' . $card['count'] . '</div>
                </div>
                <div class="icon-wrapper ' . $card['bgColor'] . ' rounded-full p-4">
                    <svg class="w-10 h-10 ' . $card['textColor'] . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="' . $card['icon'] . '"></path>
                    </svg>
                </div>
            </a>';
        }
        ?>
    </section>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Borrow Statistics Chart -->
        <section class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">สถิติการยืมรายเดือน</h2>
            <canvas id="borrowChart"></canvas>
        </section>

        <!-- User Distribution Chart -->
        <section class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">สัดส่วนผู้ใช้งาน</h2>
            <canvas id="userChart"></canvas>
        </section>
    </div>

    <!-- Recent Activity Section -->
    <section class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">กิจกรรมล่าสุด</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">วันที่</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ผู้ใช้</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">กิจกรรม</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($stats['recent_activities'] as $activity): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo htmlspecialchars($activity['date']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo htmlspecialchars($activity['user']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo htmlspecialchars($activity['action']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo $activity['status'] === 'completed' ? 'status-completed' : 
                                    ($activity['status'] === 'pending' ? 'status-pending' : 
                                    'status-failed'); ?>">
                                <?php echo htmlspecialchars($activity['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- Add Charts and Functionality -->
<script>
// Borrow Statistics Chart
const borrowCtx = document.getElementById('borrowChart').getContext('2d');
new Chart(borrowCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($stats['months'] ?? []); ?>,
        datasets: [{
            label: 'จำนวนการยืม',
            data: <?php echo json_encode($stats['borrows_per_month'] ?? []); ?>,
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'สถิติการยืมรายเดือน'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// User Distribution Chart
const userCtx = document.getElementById('userChart').getContext('2d');
new Chart(userCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($stats['user_roles'] ?? []); ?>,
        datasets: [{
            data: <?php echo json_encode($stats['user_counts'] ?? []); ?>,
            backgroundColor: [
                'rgba(59, 130, 246, 0.5)',
                'rgba(16, 185, 129, 0.5)',
                'rgba(245, 158, 11, 0.5)',
                'rgba(139, 92, 246, 0.5)'
            ],
            borderColor: [
                'rgb(59, 130, 246)',
                'rgb(16, 185, 129)',
                'rgb(245, 158, 11)',
                'rgb(139, 92, 246)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'สัดส่วนผู้ใช้งาน'
            }
        }
    }
});

// Export Functions
function exportReport(format) {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    window.location.href = `export.php?format=${format}&start=${startDate}&end=${endDate}`;
}

// Date Filter Function
function filterStats() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    window.location.href = `dashboard.php?start=${startDate}&end=${endDate}`;
}

// Live Notifications
const socket = io('http://localhost:3000');
socket.on('new_notification', function(data) {
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-white shadow-lg rounded-lg p-4 transition-all transform duration-300';
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-bell text-blue-500 mr-2"></i>
            <p class="text-gray-800">${data.message}</p>
        </div>
    `;
    document.body.appendChild(toast);
    
    // Remove toast after 5 seconds
    setTimeout(() => {
        toast.remove();
    }, 5000);
});
</script>

<style>
    .flex-1 {
        margin-left: 300px;
    }

    /* Dashboard Layout */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Card Styling */
    .dashboard-card {
        display: block;
        text-decoration: none;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                    0 2px 4px -1px rgba(0, 0, 0, 0.06);
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(229, 231, 235, 0.5);
    }

    .dashboard-card:hover {
        transform: translateY(-4px) scale(1.01);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 
                    0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Card Header */
    .dashboard-card .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .dashboard-card .header .title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #374151;
        line-height: 1.25;
        margin-bottom: 0.25rem;
    }

    .dashboard-card .header .count {
        font-size: 2.25rem;
        font-weight: 700;
        color: #111827;
        line-height: 1;
        font-feature-settings: "tnum";
        font-variant-numeric: tabular-nums;
    }

    /* Icon Styling */
    .dashboard-card .icon-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 9999px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .dashboard-card:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }

    .dashboard-card .icon-wrapper svg {
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));
    }

    /* Status Badges */
    .status-completed {
        background-color: rgba(16, 185, 129, 0.1);
        color: #059669;
        font-weight: 600;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .status-pending {
        background-color: rgba(245, 158, 11, 0.1);
        color: #d97706;
        font-weight: 600;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .status-failed {
        background-color: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        font-weight: 600;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Recent Activity Table */
    .table-wrapper {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    table {
        border-collapse: separate;
        border-spacing: 0;
    }

    /* Add responsive styles */
    @media (max-width: 1024px) {
        .grid-cols-2 {
            grid-template-columns: 1fr;
        }
    }

    /* Add animation for charts */
    canvas {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Toast notification styles */
    .notification-toast {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); }
        to { transform: translateX(0); }
    }
</style>
