<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — InternHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            500: '#2563eb',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .form-container { transition: opacity 0.3s ease; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-700 text-white hidden md:flex flex-col items-center justify-center p-8 text-center">
            <h1 class="text-3xl font-bold mb-4">InternHub</h1>
            <p class="text-blue-200">Track your internship hours, reports, and progress.</p>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                <!-- Step 1: Email Input -->
                <div id="step1" class="form-container bg-white p-8 rounded-xl shadow-lg border border-gray-200">
                    <div class="text-center mb-6">
                        <div class="mx-auto bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-key text-blue-600 text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Forgot your password?</h2>
                        <p class="text-gray-600 mt-2">Enter your email and we’ll send you a <strong>reset code</strong>.</p>
                    </div>
                    <form>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                            <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="you@domain.com" required>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                            Send Reset Code
                        </button>
                    </form>
                    <div class="mt-6 text-center">
                        <a href="auth.php" class="text-sm text-blue-600 hover:underline font-medium">
                            ← Back to login
                        </a>
                    </div>
                </div>

                <!-- Step 2: Code + New Password -->
                <div id="step2" class="form-container bg-white p-8 rounded-xl shadow-lg border border-gray-200 hidden">
                    <div class="text-center mb-6">
                        <div class="mx-auto bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-lock text-blue-600 text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Enter your reset code</h2>
                        <p class="text-gray-600 mt-2">Check your email for the <strong>6-digit code</strong> we sent you.</p>
                    </div>
                    <form>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Reset Code</label>
                            <input type="text" inputmode="numeric" pattern="[0-9]*" placeholder="123456" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" placeholder="••••••••" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" placeholder="••••••••" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                            Reset Password
                        </button>
                    </form>
                    <div class="mt-6 text-center">
                        <button id="backToStep1" class="text-sm text-blue-600 hover:underline font-medium">
                            ← Back to email step
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Show Step 2 after "Send Reset Code"
        document.querySelector('#step1 form').addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
        });

        // Go back to Step 1
        document.getElementById('backToStep1').addEventListener('click', function() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
        });
    </script>
</body>
</html>