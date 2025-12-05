<?php
session_start();

// Include database connection
if (file_exists(__DIR__ . '/../dont_touch_kinda_stuff/db.php')) {
    require_once __DIR__ . '/../dont_touch_kinda_stuff/db.php';
} elseif (file_exists(__DIR__ . '/../db.php')) {
    require_once __DIR__ . '/../db.php';
} else {
    die('Database connection file not found.');
}

// Ensure only coordinators can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'coordinator') {
    header("Location: ../auth.php");
    exit;
}

$coordinator_id = $_SESSION['user_id'];

// Fetch all classes for the coordinator
$stmt = $conn->prepare("SELECT id, sigla AS class_name FROM classes WHERE coordinator_id = ?");
$stmt->execute([$coordinator_id]);
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Determine selected class
$class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : ($classes[0]['id'] ?? null);

// Fetch selected class name
$selected_class_name = null;
foreach ($classes as $c) {
    if ($c['id'] == $class_id) {
        $selected_class_name = $c['class_name'];
        break;
    }
}

// If no class is assigned
if (!$class_id) {
    die("No classes assigned to this coordinator.");
}

// Fetch students and their progress for the selected class
$stmt = $conn->prepare("
    SELECT 
        s.id AS student_id,
        s.name AS student_name,
        comp.name AS company_name,
        i.total_hours_required,
        COALESCE((SELECT SUM(h.duration_hours) FROM hours h WHERE h.student_id = s.id), 0) AS logged_hours,
        (SELECT COUNT(*) FROM reports r WHERE r.student_id = s.id) AS reports_submitted,
        CEILING(TIMESTAMPDIFF(MONTH, i.start_date, i.end_date)) AS reports_required,
        (SELECT JSON_ARRAYAGG(JSON_OBJECT('date', h.date, 'hours', h.duration_hours, 'status', h.status)) 
         FROM hours h WHERE h.student_id = s.id ORDER BY h.date DESC LIMIT 5) AS recent_hours,
        (SELECT JSON_ARRAYAGG(JSON_OBJECT('week', r.title, 'status', r.status)) 
         FROM reports r WHERE r.student_id = s.id ORDER BY r.submitted_at DESC LIMIT 5) AS recent_reports
    FROM students s
    LEFT JOIN student_internships si ON s.id = si.student_id
    LEFT JOIN internships i ON si.internship_id = i.id
    LEFT JOIN companies comp ON i.company_id = comp.id
    WHERE s.class_id = ?
");
$stmt->execute([$class_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

function relUrl($path) {
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
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
                        <form method="get" class="mt-1">
                            <label for="class_id" class="text-gray-700 font-medium mr-2">Class:</label>
                            <select name="class_id" id="class_id" onchange="this.form.submit()" class="border-gray-300 rounded">
                                <?php foreach ($classes as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= $c['id'] == $class_id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['class_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Coordinator</p>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($selected_class_name) ?></p>
                        </div>
                    </div>
                </div>
            </header>
            <div class="flex-1 overflow-y-auto p-6">
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Students in <?= htmlspecialchars($selected_class_name) ?></h3>
                    <div id="studentsList" class="space-y-3">
                        <?php foreach ($students as $s): ?>
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="flex justify-between items-center p-4 bg-gray-50 cursor-pointer hover:bg-gray-100" onclick="toggleStudent('student-<?= $s['student_id'] ?>')">
                                    <div class="flex items-center space-x-4">
                                        <i id="icon-student-<?= $s['student_id'] ?>" class="fas fa-chevron-right text-gray-500"></i>
                                        <span class="font-medium text-gray-800"><?= htmlspecialchars($s['student_name']) ?></span>
                                    </div>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                        <span><?= htmlspecialchars($s['company_name'] ?? 'N/A') ?></span>
                                        <span><?= $s['logged_hours'] ?> / <?= $s['total_hours_required'] ?> hrs</span>
                                        <span class="px-2 py-0.5 <?= $s['logged_hours'] >= ($s['total_hours_required'] * 0.7) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> rounded-full text-xs">
                                            <?= $s['logged_hours'] >= ($s['total_hours_required'] * 0.7) ? 'On Track' : 'At Risk' ?>
                                        </span>
                                    </div>
                                </div>
                                <div id="detail-student-<?= $s['student_id'] ?>" class="hidden p-4 border-t border-gray-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                        <div>
                                            <h4 class="font-medium text-gray-800 mb-2">Weekly Hours</h4>
                                            <canvas id="chart-student-<?= $s['student_id'] ?>" height="150"></canvas>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-800 mb-2">Recent Hour Logs</h4>
                                            <ul class="text-sm space-y-1">
                                                <?php 
                                                $recent_hours = json_decode($s['recent_hours'] ?? '[]', true) ?: [];
                                                foreach ($recent_hours as $hour): ?>
                                                    <li class="flex justify-between">
                                                        <span><?= htmlspecialchars($hour['date']) ?></span>
                                                        <span><?= htmlspecialchars($hour['hours']) ?> hrs — <?= htmlspecialchars(ucfirst($hour['status'])) ?></span>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Reports</h4>
                                        <ul class="text-sm space-y-1">
                                            <?php 
                                            $recent_reports = json_decode($s['recent_reports'] ?? '[]', true) ?: [];
                                            foreach ($recent_reports as $report): ?>
                                                <li class="flex justify-between">
                                                    <span><?= htmlspecialchars($report['week']) ?></span>
                                                    <span class="<?= strtolower($report['status']) === 'approved' ? 'text-green-600' : (strtolower($report['status']) === 'pending' ? 'text-yellow-600' : 'text-red-600') ?>">
                                                        <?= htmlspecialchars(ucfirst($report['status'])) ?>
                                                    </span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
                    const data = [10, 20, 15, 25]; // Replace with actual data if available
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