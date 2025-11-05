<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InternHub — Login / Register</title>
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
        .form-container { transition: transform 0.3s ease, opacity 0.3s ease; }
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
            <!-- Go Back Button -->
            <div class="absolute top-6 left-[calc(256px+24px)] md:left-[calc(256px+24px)]">
                <a href="index.php" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Home
                </a>
            </div>

            <div class="w-full max-w-md">
                <!-- Login Form (default) -->
                <div id="loginForm" class="form-container bg-white p-8 rounded-xl shadow-lg border border-gray-200">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Welcome back</h2>
                        <p class="text-gray-600">Sign in to your account</p>
                    </div>
                    <form>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input 
                                type="password" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                required
                            >
                            <div class="text-right mt-1">
                                <a href="forgot_password.php" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                            Sign In
                        </button>
                    </form>
                    <div class="mt-6 text-center">
                        <p class="text-gray-600">
                            Don't have an account? 
                            <button id="showRegister" class="text-blue-600 font-medium hover:underline">Register</button>
                        </p>
                    </div>
                </div>

                <!-- Register Form (hidden by default) -->
                <div id="registerForm" class="form-container bg-white p-8 rounded-xl shadow-lg border border-gray-200 hidden">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Create account</h2>
                        <p class="text-gray-600">Join InternHub today</p>
                    </div>
                    <form>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                            Create Account
                        </button>
                    </form>
                    <div class="mt-6 text-center">
                        <p class="text-gray-600">
                            Already have an account? 
                            <button id="showLogin" class="text-blue-600 font-medium hover:underline">Sign in</button>
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Add this at the beginning of the script section
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('form') === 'register') {
                document.getElementById('loginForm').classList.add('hidden');
                document.getElementById('registerForm').classList.remove('hidden');
            }
        };

        document.getElementById('showRegister').addEventListener('click', function() {
            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('registerForm').classList.remove('hidden');
        });

        document.getElementById('showLogin').addEventListener('click', function() {
            document.getElementById('registerForm').classList.add('hidden');
            document.getElementById('loginForm').classList.remove('hidden');
        });
    </script>
</body>
</html>