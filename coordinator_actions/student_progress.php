<?php 
// build a consistent relative URL based at the application's script directory
function relUrl($path) {
    // e.g. /20_11/oh_my_so_tuff_blud/coordinator_actions -> /20_11/oh_my_so_tuff_blud
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    // ensure single slash between base and path
    return $base . '/' . ltrim($path, '/');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Progress | InternHub</title>
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
        <aside class="w-64 bg-blue-700 text-white flex flex-col">
            <div class="p-6 border-b border-blue-600">
                <h1 class="text-2xl font-bold">InternHub</h1>
            </div>
            <nav class="p-4 flex flex-col min-h-[calc(100vh-5rem)]">
                <div class="space-y-2 flex-1">
                    <a href="<?= relUrl('/dashboard_coordinator.php') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-home"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="<?= relUrl('/review_reports.php') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-file-alt"></i>
                        <span class="font-medium">Review Reports</span>
                    </a>
                    <a href="<?= relUrl('/student_progress.php') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-white text-blue-700 border-l-4 border-blue-500">
                        <i class="fas fa-file-alt"></i>
                        <span class="font-medium">Student Progress</span>
                    </a>
                    <a href="<?= relUrl('/overall_actions/messages.php') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
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
        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Student Progress</h2>
                        <p class="text-gray-600">CS Internship 2025 — Dr. Lee</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Dr. Marco</p>
                            <p class="text-sm text-gray-500">Coordinator</p>
                        </div>
                    </div>
                </div>
            </header>
            <div class="flex-1 overflow-y-auto p-6">
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">All Students — CS Internship 2025</h3>
                    <div id="studentsList" class="space-y-3">
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="flex justify-between items-center p-4 bg-gray-50 cursor-pointer hover:bg-gray-100" onclick="toggleStudent('student-1')">
                                <div class="flex items-center space-x-4">
                                    <i id="icon-student-1" class="fas fa-chevron-right text-gray-500"></i>
                                    <span class="font-medium text-gray-800">Alex Johnson</span>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span>TechCorp</span>
                                    <span>120 / 200 hrs</span>
                                    <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">On Track</span>
                                </div>
                            </div>
                            <div id="detail-student-1" class="hidden p-4 border-t border-gray-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Weekly Hours</h4>
                                        <canvas id="chart-student-1" height="150"></canvas>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Recent Hour Logs</h4>
                                        <ul class="text-sm space-y-1">
                                            <li class="flex justify-between"><span>2025-06-10</span> <span>8.0 hrs — Approved</span></li>
                                            <li class="flex justify-between"><span>2025-06-07</span> <span>7.5 hrs — Pending</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-2">Reports</h4>
                                    <ul class="text-sm space-y-1">
                                        <li>Week 3 — <span class="text-green-600">Approved</span></li>
                                        <li>Week 2 — <span class="text-yellow-600">Pending</span></li>
                                    </ul>
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <button class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded flex items-center">
                                        <i class="fas fa-comment mr-1"></i> Message Student
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="flex justify-between items-center p-4 bg-gray-50 cursor-pointer hover:bg-gray-100" onclick="toggleStudent('student-2')">
                                <div class="flex items-center space-x-4">
                                    <i id="icon-student-2" class="fas fa-chevron-right text-gray-500"></i>
                                    <span class="font-medium text-gray-800">Maria Chen</span>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span>InnovateX</span>
                                    <span>45 / 200 hrs</span>
                                    <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs">At Risk</span>
                                </div>
                            </div>
                            <div id="detail-student-2" class="hidden p-4 border-t border-gray-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Weekly Hours</h4>
                                        <canvas id="chart-student-2" height="150"></canvas>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Recent Hour Logs</h4>
                                        <ul class="text-sm space-y-1">
                                            <li class="flex justify-between"><span>2025-06-05</span> <span>4.0 hrs — Approved</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-2">Reports</h4>
                                    <ul class="text-sm space-y-1">
                                        <li>Week 1 — <span class="text-yellow-600">Pending</span></li>
                                    </ul>
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <button class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded flex items-center">
                                        <i class="fas fa-comment mr-1"></i> Message Student
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleStudent(id) {
            const detail = document.getElementById('detail-' + id);
            const icon = document.getElementById('icon-' + id);
            
            if (detail.classList.contains('hidden')) {
                // Close all other details
                document.querySelectorAll('[id^="detail-student"]').forEach(el => {
                    el.classList.add('hidden');
                    const elId = el.id.replace('detail-', '');
                    document.getElementById('icon-' + elId).className = 'fas fa-chevron-right text-gray-500';
                });
                
                // Open this one
                detail.classList.remove('hidden');
                icon.className = 'fas fa-chevron-down text-gray-700';
                
                // Initialize chart only once
                if (!window['chart_' + id]) {
                    const ctx = document.getElementById('chart-' + id).getContext('2d');
                    const data = id === 'student-1' ? [25, 30, 28, 37] : [10, 12, 8, 15];
                    window['chart_' + id] = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                            datasets: [{
                                label: 'Hours',
                                data: data,
                                backgroundColor: '#3b82f6'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value + ' hrs';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            } else {
                detail.classList.add('hidden');
                icon.className = 'fas fa-chevron-right text-gray-500';
            }
        }
    </script>
</body>
</html>