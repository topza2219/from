<?php
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';
require_once __DIR__ . '/api/get_deliveries.php';

// Fetch delivery data
$deliveries = getDeliveries();
?>

<div class="flex-1 p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Deliveries</h1>
    
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($deliveries)): ?>
                    <?php foreach ($deliveries as $delivery): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($delivery['id']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($delivery['name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($delivery['contact']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($delivery['status']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                <a href="edit_delivery.php?id=<?php echo $delivery['id']; ?>" 
                                   class="inline-flex px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                                    Edit
                                </a>
                                <a href="delete_delivery.php?id=<?php echo $delivery['id']; ?>" 
                                   class="inline-flex px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600"
                                   onclick="return confirm('Are you sure you want to delete this delivery?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include_once 'templates/footer.php';
?>
<style>
    .flex-1 {
        margin-left: 300px;
    }
    </style>
