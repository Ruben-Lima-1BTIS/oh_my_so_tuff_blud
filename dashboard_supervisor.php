<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard - InternHub</title>
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
                    <i class="fas fa-clock"></i>
                    <span class="font-medium">Approve Hours</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                    <i class="fas fa-file-alt"></i>
                    <span class="font-medium">Review Reports</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                    <i class="fas fa-chart-line"></i>
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
                    <h2 class="text-2xl font-semibold text-gray-800">Supervisor Dashboard</h2>
                    <p class="text-gray-600">Class: CS Internship 2025 — Supervisor</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                        <i class="fas fa-user text-gray-500"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Dr. Lee</p>
                        <p class="text-sm text-gray-500">Supervisor</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            <!-- Overview Widgets -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <p class="text-sm text-gray-600">Pending Hour Approvals</p>
                    <p class="text-2xl font-bold text-yellow-600">5</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <p class="text-sm text-gray-600">Reports Awaiting Feedback</p>
                    <p class="text-2xl font-bold text-blue-600">3</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <p class="text-sm text-gray-600">Students On Track</p>
                    <p class="text-2xl font-bold text-green-600">86%</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <p class="text-sm text-gray-600">Flagged Students</p>
                    <p class="text-2xl font-bold text-red-600">2</p>
                </div>
            </div>

            <!-- Chart: Hours Overview -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Average Hours Logged</h3>
                <div class="h-80">
                    <canvas id="hoursOverviewChart"></canvas>
                </div>
            </div>

            <!-- Pending Hours Table -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Pending Hours for Approval</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Hours Logged</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Submitted</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">Alex Johnson</td>
                                <td class="px-4 py-3 text-sm text-gray-600">TechCorp</td>
                                <td class="px-4 py-3 text-sm text-gray-800">8 hrs</td>
                                <td class="px-4 py-3 text-sm text-gray-600">Nov 6, 2025</td>
                                <td class="px-4 py-3 flex space-x-2">
                                    <button class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded">Approve</button>
                                    <button onclick="openRejectModal('Alex Johnson')" class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded">Reject</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reports Pending Feedback -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Reports Pending Feedback</h3>
                <ul class="divide-y divide-gray-200">
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800">Maria Chen</p>
                            <p class="text-sm text-gray-600">Report 2 — Tech Progress</p>
                        </div>
                        <div class="flex space-x-3">
                            <button class="text-blue-600 hover:underline text-sm">View Report</button>
                            <button class="text-green-600 hover:underline text-sm">Provide Feedback</button>
                        </div>
                    </li>
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800">Omar Rahman</p>
                            <p class="text-sm text-gray-600">Report 3 — Midterm Evaluation</p>
                        </div>
                        <div class="flex space-x-3">
                            <button class="text-blue-600 hover:underline text-sm">View Report</button>
                            <button class="text-green-600 hover:underline text-sm">Provide Feedback</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </main>
</div>

<!-- Rejection Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96 shadow-lg">
        <h3 class="text-lg font-semibold mb-3">Reject Hours</h3>
        <p class="text-sm text-gray-600 mb-3">Provide a reason for rejecting <span id="studentName" class="font-medium"></span>’s submission:</p>
        <textarea id="rejectReason" class="w-full border border-gray-300 rounded-md p-2 mb-4 text-sm" rows="3" placeholder="Enter rejection reason..."></textarea>
        <div class="flex justify-end space-x-3">
            <button onclick="closeRejectModal()" class="px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 rounded">Cancel</button>
            <button class="px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded">Submit</button>
        </div>
    </div>
</div>

<script>
    // Rejection Modal Logic
    function openRejectModal(student) {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('studentName').textContent = student;
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    // ChartJS Example
    const ctx = document.getElementById('hoursOverviewChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Alex J.', 'Maria C.', 'Omar R.', 'James T.', 'Lena K.'],
            datasets: [{
                label: 'Logged Hours',
                data: [120, 45, 90, 110, 80],
                backgroundColor: '#3b82f6',
                borderRadius: 4,
            }, {
                label: 'Target Hours (200)',
                data: [200, 200, 200, 200, 200],
                backgroundColor: '#e5e7eb'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
</body>
</html>
