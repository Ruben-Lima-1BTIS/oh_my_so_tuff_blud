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
        header("Location: dashboard.php"); // fixed typo
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
    } elseif (strlen($newPassword) < 8) { // changed from 6 to 8 to match client rules
        $error = "Password must be at least 8 characters.";
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <div class="flex w-full  border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="position: relative;">
                        <input 
                            id="passwordInput"
                            type="password" 
                            name="new_password"
                            class="w-full px-4 py-2"
                            required
                        >
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700" id="togglePassword">
                                olho
                            </button>
                        </div>
                        <!-- Single live feedback message (shows first unmet rule or success) -->
                        <p id="passwordMessage" class="mt-2 text-sm text-gray-600"></p>


                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input 
                            id="confirmPasswordInput"
                            type="password" 
                            name="confirm_password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required
                        >
                        <p id="matchMessage" class="mt-2 text-sm"></p>
                    </div>

                    <button 
                        id="submitButton"
                        type="submit" 
                        disabled
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition disabled:opacity-60"
                    >
                        Update Password
                    </button>
                </form>
            </div>
        </main>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const passwordInput = document.getElementById('passwordInput');
        const confirmInput = document.getElementById('confirmPasswordInput');
        const submitButton = document.getElementById('submitButton');
        const matchMessage = document.getElementById('matchMessage');
        const passwordMessage = document.getElementById('passwordMessage');

        const ruleTexts = {
            length: 'At least 8 characters',
            upper: 'At least one uppercase letter',
            lower: 'At least one lowercase letter',
            number: 'At least one number',
            symbol: 'At least one symbol (e.g. !@#$%)'
        };

        function validate() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            const rules = {
                length:    (password.length >= 8),
                upper:     (/[A-Z]/.test(password)),
                lower:     (/[a-z]/.test(password)),
                number:    (/\d/.test(password)),
                symbol:    (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password))
            };

            // Determine if all rules are met (no DOM changes to rule list)
            const allRulesMet = Object.values(rules).every(Boolean);

            // Show only one inline message: first unmet rule or success
            let firstUnmet = null;
            for (const rule of ['length','upper','lower','number','symbol']) {
                if (!rules[rule]) { firstUnmet = rule; break; }
            }

            if (password.length === 0) {
                passwordMessage.textContent = ''; // nothing until user types
                passwordMessage.className = 'mt-2 text-sm text-gray-600';
            } else if (firstUnmet) {
                passwordMessage.textContent = ruleTexts[firstUnmet];
                passwordMessage.className = 'mt-2 text-sm text-red-600';
            } else {
                passwordMessage.textContent = 'Password meets all requirements';
                passwordMessage.className = 'mt-2 text-sm text-green-600';
            }

            // Confirm password match message
            const match = password.length > 0 && password === confirm;
            if (confirm.length > 0) {
                if (match) {
                    matchMessage.textContent = 'Passwords match';
                    matchMessage.className = 'mt-2 text-sm text-green-600';
                } else {
                    matchMessage.textContent = 'Passwords do not match';
                    matchMessage.className = 'mt-2 text-sm text-red-600';
                }
            } else {
                matchMessage.textContent = '';
                matchMessage.className = 'mt-2 text-sm';
            }

            submitButton.disabled = !(allRulesMet && match);
        }

        passwordInput.addEventListener('input', validate);
        confirmInput.addEventListener('input', validate);
        validate();
    });
    </script>

    <script>
        const togglePassword = document.getElementById('passwordInput');

        // Add a toggle button/icon in your HTML first, then:
        toggleButton.style.position = 'absolute';
        toggleButton.style.cursor = 'pointer';
        
        toggleButton.addEventListener('click', function (e) {
            e.preventDefault();
            const type = togglePassword.getAttribute('type') === 'password' ? 'text' : 'password';
            togglePassword.setAttribute('type', type);
        });

        togglePassword.parentElement.style.position = 'relative';
        togglePassword.parentElement.appendChild(toggleButton);
    </script>
d