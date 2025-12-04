<?php

// Fix: Use correct path to db.php (now inside dont_touch_kinda_stuff)
if (file_exists(__DIR__ . '/../dont_touch_kinda_stuff/db.php')) {
    require_once __DIR__ . '/../dont_touch_kinda_stuff/db.php';
} elseif (file_exists(__DIR__ . '/../db.php')) {
    require_once __DIR__ . '/../db.php';
} elseif (file_exists(__DIR__ . '/db.php')) {
    require_once __DIR__ . '/db.php';
} else {
    die('Database connection file not found.');
}

// ensure session available
if (session_status() === PHP_SESSION_NONE) session_start();

// robust relUrl builder
function relUrl($path) {
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    return $base . '/' . ltrim($path, '/');
}

// determine current user role and id
$role = $_SESSION['role'] ?? 'student';
$user_id = $_SESSION['user_id'] ?? null;

// fetch display name depending on role (best-effort)
$user_name = $_SESSION['email'] ?? 'User';
try {
    if ($user_id) {
        if ($role === 'student') {
            $stmt = $conn->prepare("SELECT name FROM students WHERE id = ? LIMIT 1");
            $stmt->execute([$user_id]);
            $tmp = $stmt->fetchColumn();
            if ($tmp) $user_name = $tmp;
        } elseif ($role === 'supervisor') {
            $stmt = $conn->prepare("SELECT name FROM supervisors WHERE id = ? LIMIT 1");
            $stmt->execute([$user_id]);
            $tmp = $stmt->fetchColumn();
            if ($tmp) $user_name = $tmp;
        } elseif ($role === 'coordinator') {
            $stmt = $conn->prepare("SELECT name FROM coordinators WHERE id = ? LIMIT 1");
            $stmt->execute([$user_id]);
            $tmp = $stmt->fetchColumn();
            if ($tmp) $user_name = $tmp;
        } elseif ($role === 'admin') {
            $stmt = $conn->prepare("SELECT name FROM admins WHERE id = ? LIMIT 1");
            $stmt->execute([$user_id]);
            $tmp = $stmt->fetchColumn();
            if ($tmp) $user_name = $tmp;
        }
    }
} catch (Exception $e) {
    // ignore lookup errors
}

// role label
$roleLabel = ucfirst($role);

// build role-specific navigation
$navItems = [];
if ($role === 'coordinator') {
    $navItems = [
        ['href' => relUrl('/coordinator_actions/dashboard_coordinator.php'), 'icon' => 'fas fa-home', 'label' => 'Dashboard'],
        ['href' => relUrl('/coordinator_actions/review_reports.php'), 'icon' => 'fas fa-file-alt', 'label' => 'Review Reports'],
        ['href' => relUrl('/coordinator_actions/student_progress.php'), 'icon' => 'fas fa-chart-line', 'label' => 'Student Progress'],
        ['href' => relUrl('/overall_actions/messages.php'), 'icon' => 'fas fa-comments', 'label' => 'Messages'],
    ];
} elseif ($role === 'supervisor') {
    $navItems = [
        ['href' => relUrl('/supervisor_actions/dashboard_supervisor.php'), 'icon' => 'fas fa-home', 'label' => 'Dashboard'],
        ['href' => relUrl('/supervisor_actions/review_hours.php'), 'icon' => 'fas fa-clock', 'label' => 'Review Hours'],
        ['href' => relUrl('/overall_actions/messages.php'), 'icon' => 'fas fa-comments', 'label' => 'Messages'],
    ];
} else { // student or default
    $navItems = [
        ['href' => relUrl('/student_actions/dashboard.php'), 'icon' => 'fas fa-home', 'label' => 'Dashboard'],
        ['href' => relUrl('/student_actions/log_hours.php'), 'icon' => 'fas fa-clock', 'label' => 'Log Hours'],
        ['href' => relUrl('/student_actions/submit-reports.php'), 'icon' => 'fas fa-file-alt', 'label' => 'Submit Reports'],
        ['href' => relUrl('/overall_actions/messages.php'), 'icon' => 'fas fa-comments', 'label' => 'Messages'],
    ];
}

// current script to mark active nav
$current = $_SERVER['SCRIPT_NAME'];

?>
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
        <aside class="w-64 bg-blue-700 text-white flex flex-col">
            <div class="p-6 border-b border-blue-600">
                <h1 class="text-2xl font-bold">InternHub</h1>
            </div>
            <nav class="p-4 flex flex-col min-h-[calc(100vh-5rem)]">
                <div class="space-y-2 flex-1">
                    <?php foreach ($navItems as $item): 
                        $isActive = (strpos($current, $item['href']) !== false) || ($current === $item['href']);
                        $classes = 'flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600';
                        if ($isActive) $classes .= ' bg-white text-blue-700 border-l-4 border-blue-500';
                    ?>
                        <a href="<?= $item['href'] ?>" class="<?= $classes ?>">
                            <i class="<?= $item['icon'] ?>"></i>
                            <span class="font-medium"><?= htmlspecialchars($item['label']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="space-y-2 mt-auto">
                    <a href="<?= relUrl('/overall_actions/settings.php') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-cog"></i>
                        <span class="font-medium">Settings</span>
                    </a>
                    <a href="<?= relUrl('/overall_actions/logout.php') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-600">
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
                        <h2 class="text-2xl font-semibold text-gray-800">Messages</h2>
                        <p class="text-gray-600">Communicate with your supervisor and coordinator</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-12 h-12 flex items-center justify-center">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800"><?= htmlspecialchars($user_name) ?></p>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($roleLabel) ?></p>
                        </div>
                    </div>
                </div>
            </header>
            <div class="flex-1 overflow-y-auto p-6">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <ul class="divide-y divide-gray-200">
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
            </div>
        </main>
    </div>
</body>
</html>