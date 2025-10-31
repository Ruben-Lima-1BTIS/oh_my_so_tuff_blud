<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InternHub Dashboard</title>
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
            <a href="log_hours.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                <i class="fas fa-clock"></i>
                <span class="font-medium">Log Hours</span>
            </a>
            <a href="submit-reports.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                <i class="fas fa-file-alt"></i>
                <span class="font-medium">Submit Reports</span>
            </a>
            <a href="messages.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
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
                        <h2 class="text-2xl font-semibold text-gray-800">Hello, Alex Johnson</h2>
                        <p class="text-gray-600">Welcome back to your dashboard</p>
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

            <!-- Dashboard Content -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <!-- Action Widgets -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-600 text-white p-6 rounded-xl shadow-lg hover:bg-blue-700 transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold">Hours Left to Do</h3>
                                <p class="text-3xl font-bold mt-2">12 hours</p>
                            </div>
                            <i class="fas fa-clock text-4xl opacity-80"></i>
                        </div>
                    </div>
                    <div class="bg-blue-600 text-white p-6 rounded-xl shadow-lg hover:bg-blue-700 transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold">Submitted Reports</h3>
                                <p class="text-3xl font-bold mt-2">3 reports</p>
                            </div>
                            <i class="fas fa-file-alt text-4xl opacity-80"></i>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Pie Chart -->
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Hour Status Overview</h3>
                        <div class="h-80">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>

                    <!-- Bar Chart -->
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Hours Made This Week</h3>
                        <div class="h-80">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Hours Missing', 'Hours to Approve', 'Hours Approved', 'Hours Made'],
            datasets: [{
                data: [24, 18, 45, 12],
                backgroundColor: ['#ef4444', '#f97316', '#10b981', '#3b82f6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.raw} hours`;
                        }
                    }
                }
            }
        }
    });

    const barCtx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
            datasets: [
                {
                    label: 'Hours Logged',
                    data: [8, 7, 9, 6, 8],
                    backgroundColor: '#3b82f6',
                    borderRadius: 4,
                    borderSkipped: false
                },
                {
                    label: 'Target Hours',
                    data: [6, 6, 6, 6, 6],
                    backgroundColor: '#94a3b8',
                    borderRadius: 4,
                    borderSkipped: false
                }
            ]
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