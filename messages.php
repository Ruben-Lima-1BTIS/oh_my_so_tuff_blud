<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - InternHub</title>
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
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-700 text-white flex flex-col">
            <div class="p-6 border-b border-blue-600">
                <h1 class="text-2xl font-bold">InternHub</h1>
            </div>
            <nav class="p-4 flex flex-col min-h-[calc(100vh-5rem)]">
                <div class="space-y-2 flex-1">
                    <a href="dashboard.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-home"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="log_hours.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-clock"></i>
                        <span class="font-medium">Log Hours</span>
                    </a>
                    <a href="submit-reports.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-file-alt"></i>
                        <span class="font-medium">Submit Reports</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-white text-blue-700 border-l-4 border-blue-500">
                        <i class="fas fa-comments"></i>
                        <span class="font-medium">Messages</span>
                    </a>
                </div>
                <div class="space-y-2 mt-auto">
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-cog"></i>
                        <span class="font-medium">Settings</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="font-medium">Logout</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Messages</h2>
                        <p class="text-gray-600">Communicate with your supervisor and coordinator</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Alex Johnson</p>
                            <p class="text-sm text-gray-500">Intern</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Messages List -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        <!-- Conversation 1 -->
                        <li class="p-4 hover:bg-gray-50 cursor-pointer">
                            <div class="flex items-start">
                                <div class="bg-blue-100 text-blue-800 w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">S</div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-gray-900">Sarah Miller (Supervisor)</p>
                                        <span class="text-xs text-gray-500">2 days ago</span>
                                    </div>
                                    <p class="text-sm text-gray-600 truncate">Your hours from Monday look great! Approved.</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full">Internship: TechCorp</span>
                                </div>
                            </div>
                        </li>
                        <!-- Conversation 2 -->
                        <li class="p-4 hover:bg-gray-50 cursor-pointer bg-white">
                            <div class="flex items-start bg-white">
                                <div class="bg-green-100 text-green-800 w-10 h-10 rounded-full flex items-center justify-center font-bold mr-3">C</div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-gray-900">Dr. Lee (Coordinator)</p>
                                        <span class="text-xs text-gray-500">1 week ago</span>
                                    </div>
                                    <p class="text-sm text-gray-600 truncate">Don’t forget to submit your Week 3 report by Friday!</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-xs bg-gray-100 text-gray-800 rounded-full">Class: CS Internship 2025</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Empty state (optional) -->
                <!--
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-3"></i>
                    <p>No messages yet</p>
                </div>
                -->
            </div>
        </main>
    </div>
</body>
</html>