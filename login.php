<?php
session_start();
require_once './config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'กรุณากรอกชื่อผู้ใช้และรหัสผ่าน';
    } else {
        $sql = "SELECT user_id, username, password, role, full_name 
                FROM users 
                WHERE username = ? AND status = 'active'";
                
        try {
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result && $result->num_rows == 1) {
                    $user = $result->fetch_assoc();
                    // For testing purposes, using plain password comparison
                    // In production, use: password_verify($password, $user['password'])
                    if ($password === $user['password']) {
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['user_role'] = $user['role'];
                        $_SESSION['user_name'] = $user['full_name'];
                        
                        // Redirect based on role
                        $redirect_path = '';
                        switch($user['role']) {
                            case 'admin':
                                $redirect_path = 'admin/dashboard.php';
                                break;
                            case 'approver':
                                $redirect_path = 'approver/dashboard.php';
                                break;
                            case 'borrower':
                                $redirect_path = 'borrower/dashboard.php';
                                break;
                            case 'delivery':
                                $redirect_path = 'delivery/dashboard.php';
                                break;
                            default:
                                throw new Exception('Invalid user role');
                        }
                        
                        if (!empty($redirect_path)) {
                            header('Location: ' . $redirect_path);
                            exit();
                        }
                    } else {
                        $error = 'รหัสผ่านไม่ถูกต้อง';
                    }
                } else {
                    $error = 'ไม่พบบัญชีผู้ใช้';
                }
                $stmt->close();
             }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $error = 'เกิดข้อผิดพลาดในการเข้าสู่ระบบ กรุณาลองใหม่อีกครั้ง';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - ระบบยืม-คืนอุปกรณ์</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-bold text-center mb-6">เข้าสู่ระบบ</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-700 mb-2">ชื่อผู้ใช้</label>
                <input type="text" id="username" name="username" required
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="password" class="block text-gray-700 mb-2">รหัสผ่าน</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                เข้าสู่ระบบ
            </button>
        </form>
    </div>
</body>
</html>