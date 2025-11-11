<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Hours - InternHub</title>
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
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-white text-blue-700 border-l-4 border-blue-500">
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
        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Log Your Internship Hours</h2>
                        <p class="text-gray-600">Track your daily work and stay on schedule</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Ruben Lima</p>
                            <p class="text-sm text-gray-500">Intern</p>
                        </div>
                    </div>
                </div>
            </header>
            <div class="flex-1 overflow-y-auto p-6 space-y-8">
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 max-w-2xl">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Add New Hours</h3>
                    <form id="logHoursForm">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Hours Worked</label>
                                <input type="number" min="0.5" step="0.5" placeholder="e.g. 8 or 4.5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <p class="mt-1 text-sm text-gray-500">Minimum: 0.5 hours</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Task Description</label>
                                <textarea rows="3" placeholder="What did you work on today?" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                                <i class="fas fa-plus-circle mr-2"></i> Log Hours
                            </button>
                        </div>
                    </form>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Entries</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Task</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-800">2025-06-10</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">8.0</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">Fiz Drop da base de dados da empresa</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-800">2025-06-07</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">7.5</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">Curso de SQL</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Approved</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div id="hoursRegisteredModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full mx-4" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <div class="flex items-start">
                <div class="flex-1">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Hours registered</h3>
                    <p class="mt-2 text-sm text-gray-600">Your hours have been successfully registered.</p>
                </div>
                <button id="closeModalBtn" class="ml-4 text-gray-400 hover:text-gray-600 text-2xl leading-none" aria-label="Close modal">&times;</button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var form = document.getElementById('logHoursForm');
            var modal = document.getElementById('hoursRegisteredModal');
            var closeBtn = document.getElementById('closeModalBtn');

            function showModal() {
                modal.classList.remove('hidden');
                modal.querySelector('[role="dialog"]').focus && modal.querySelector('[role="dialog"]').focus();
            }

            function hideModal() {
                modal.classList.add('hidden');
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                showModal();
                form.reset();
                setTimeout(hideModal, 2000);
            });

            closeBtn.addEventListener('click', hideModal);

            modal.addEventListener('click', function(e) {
                if (e.target === modal) hideModal();
            });
        })();
    </script>
</body>
</html>