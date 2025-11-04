<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinator Dashboard - InternHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-white text-blue-700 border-l-4 border-blue-500">
                        <i class="fas fa-home"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-file-alt"></i>
                        <span class="font-medium">Review Reports</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-file-alt"></i>
                        <span class="font-medium">Student Progress</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
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
                        <h2 class="text-2xl font-semibold text-gray-800">Coordinator Dashboard</h2>
                        <p class="text-gray-600">Class: CS Internship 2025 — Dr. Lee</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Dr. Lee</p>
                            <p class="text-sm text-gray-500">Coordinator</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <!-- Overview Widgets -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                        <p class="text-sm text-gray-600">Total Students</p>
                        <p class="text-2xl font-bold text-gray-800">24</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                        <p class="text-sm text-gray-600">Avg. Hours Logged</p>
                        <p class="text-2xl font-bold text-gray-800">19.4 / 35</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                        <p class="text-sm text-gray-600">On Track</p>
                        <p class="text-2xl font-bold text-green-600">83%</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                        <p class="text-sm text-gray-600">Needs Attention</p>
                        <p class="text-2xl font-bold text-red-600">4</p>
                    </div>
                </div>

                <!-- Chart: Hours by Student -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Hours Logged This Week</h3>
                    <div class="h-80">
                        <canvas id="studentHoursChart"></canvas>
                    </div>
                </div>

                <!-- Student Progress Table -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Student Progress</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Hours (Logged / Total)</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reports</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">Alex Johnson</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">TechCorp</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">120 / 200</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">3/4 submitted</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">On Track</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                            <i class="fas fa-comment mr-1"></i> Message
                                        </button>
                                    </td>
                                </tr>
                                <tr class="bg-red-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">Maria Chen</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">InnovateX</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">45 / 200</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">1/4 submitted</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">At Risk</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                            <i class="fas fa-comment mr-1"></i> Message
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Student Hours Bar Chart
        const ctx = document.getElementById('studentHoursChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Alex J.', 'Maria T.', 'James T.', 'Lena K.', 'Omar R.'],
                datasets: [{
                    label: 'Hours Logged this Week',
                    data: [30, 5, 22, 15, 25],
                    backgroundColor: '#3b82f6',
                    borderRadius: 4,
                    borderSkipped: false
                }, {
                    label: 'Weekly Target (35 hrs)',
                    data: [35, 35, 35, 35, 35],
                    backgroundColor: '#e5e7eb',
                    borderColor: '#d1d5db',
                    borderWidth: 1,
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' hrs';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
</body>
</html>