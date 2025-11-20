<?php
session_start();
require 'db.php';

// se nao tiver logado expulsa
if (!isset($_SESSION['user_id']) || !isset($_SESSION['table']) || !isset($_SESSION['role'])) {
    header("Location: auth.php?error=not_logged_in");
    exit;
}

$userId = $_SESSION['user_id'];
$table = $_SESSION['table'];
$role = $_SESSION['role'];
$firstLogin = $_SESSION['first_login'] ?? 0;

// se já mudou a password, redireciona para o dashboard correto
if ($firstLogin == 0) {
    if ($role === 'student') {
        header("Location: dashboard.php.php");
    } elseif ($role === 'coordinator') {
        header("Location: dashboard_coordinator.php");
    } elseif ($role === 'supervisor') {
        header("Location: dashboard_supervisor.php");
    }
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newPassword = $_POST["new_password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    if (empty($newPassword) || empty($confirmPassword)) {
        $error = "Please fill in both password fields.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (strlen($newPassword) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // se tudo tiver válido, vai redirecionar e atualizar a password do user
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $query = $conn->prepare("UPDATE $table SET password_hash = ?, first_login = 0 WHERE id = ?");
        $query->execute([$passwordHash, $userId]);

        // atualiza o campo first_login do user
        $_SESSION['first_login'] = 0;

        // rediciona para o dashboard correto
        if ($role === 'student') {
            header("Location: dashboard.php?changed=1");
        } elseif ($role === 'coordinator') {
            header("Location: dashboard_coordinator.php?changed=1");
        } elseif ($role === 'supervisor') {
            header("Location: dashboard_supervisor.php?changed=1");
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InternHub — Create New Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">

    <div class="flex min-h-screen">
        <aside class="w-64 bg-blue-700 text-white hidden md:flex flex-col items-center justify-center p-8 text-center">
            <h1 class="text-3xl font-bold mb-4">InternHub</h1>
            <p class="text-blue-200">Secure your account with a new password.</p>
        </aside>
        <main class="flex-1 flex items-center justify-center p-4">
            <div class="absolute top-6 left-[calc(256px+24px)] md:left-[calc(256px+24px)]">
                <a href="auth.php" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Login
                </a>
            </div>

            <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg border border-gray-200">

                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Create New Password</h2>
                    <p class="text-gray-600">Your HR-assigned password must be updated</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-300 rounded-lg">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input 
                            type="password" 
                            name="new_password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required
                        >
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input 
                            type="password" 
                            name="confirm_password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition"
                    >
                        Update Password
                    </button>
                </form>
            </div>
        </main>

    </div>

</body>
</html>
