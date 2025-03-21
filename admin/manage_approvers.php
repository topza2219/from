<?php
//session_start();

// Check if the user is logged in and has admin privileges
//if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
  //  header('Location: login.php');
   // exit();
//}

// Include necessary files
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';
require_once __DIR__ . '/api/get_approvers.php';

// Fetch approvers data
$approvers = getApprovers();
?>

<div class="flex-1 p-6">
    <h1 class="text-4xl font-bold text-gray-800 mb-6">Manage Approvers</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-lg rounded-lg">
            <thead class="bg-blue-100">
                <tr>
                    <th class="py-3 px-6 text-left text-md font-medium text-blue-700">ID</th>
                    <th class="py-3 px-6 text-left text-md font-medium text-blue-700">Name</th>
                    <th class="py-3 px-6 text-left text-md font-medium text-blue-700">Email</th>
                    <th class="py-3 px-6 text-center text-md font-medium text-blue-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($approvers)): ?>
                    <?php foreach ($approvers as $approver): ?>
                        <tr class="border-b hover:bg-blue-50 transition">
                            <td class="py-3 px-6 text-gray-700"><?php echo htmlspecialchars($approver['id']); ?></td>
                            <td class="py-3 px-6 text-gray-700"><?php echo htmlspecialchars($approver['name']); ?></td>
                            <td class="py-3 px-6 text-gray-700"><?php echo htmlspecialchars($approver['email']); ?></td>
                            <td class="py-3 px-6 text-center">
                                <a href="approve_request.php?id=<?php echo $approver['id']; ?>" 
                                   class="inline-block px-5 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                   Approve
                                </a>
                                <a href="reject_request.php?id=<?php echo $approver['id']; ?>" 
                                   class="inline-block px-5 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                   Reject
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="py-4 px-6 text-center text-gray-500">No approvers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
<style>
    .flex-1 {
        margin-left: 300px;
    }


</style>