<?php
session_start();

// DB connection
$dsn = "mysql:host=localhost;dbname=internhub_nova;charset=utf8mb4";
$user = "root";
$pass = "";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];

    try {
        if ($type === 'class') {
            $name = $_POST['name'];
            $stmt = $pdo->prepare("INSERT INTO classes (name) VALUES (?)");
            $stmt->execute([$name]);
            $success = "Class created!";
        }

        elseif ($type === 'company') {
            $name = $_POST['name'];
            $address = $_POST['address'];
            $stmt = $pdo->prepare("INSERT INTO companies (name, address) VALUES (?, ?)");
            $stmt->execute([$name, $address]);
            $success = "Company created!";
        }

        elseif ($type === 'coordinator') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO coordinators (name, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password]);
            $success = "Coordinator created!";
        }

        elseif ($type === 'internship') {
            $name = $_POST['name'];
            $company_id = $_POST['company_id'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $min_hours_day = $_POST['min_hours_day'] ?? 0;
            $lunch_break_minutes = $_POST['lunch_break_minutes'] ?? 60;
            $stmt = $pdo->prepare("INSERT INTO internships (name, company_id, start_date, end_date, min_hours_day, lunch_break_minutes) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $company_id, $start_date, $end_date, $min_hours_day, $lunch_break_minutes]);
            $success = "Internship created!";
        }

        elseif ($type === 'student') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $class_id = $_POST['class_id'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO students (name, email, class_id, password_hash) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $class_id, $password]);
            $success = "Student created!";
        }

        elseif ($type === 'supervisor') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO supervisors (name, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password]);
            $success = "Supervisor created!";
        }

        else {
            $error = "Unknown type!";
        }

    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch companies and classes for selects
$companies = $pdo->query("SELECT id, name FROM companies")->fetchAll(PDO::FETCH_ASSOC);
$classes = $pdo->query("SELECT id, name FROM classes")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>InternHub — HR User Creation</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">
    <h1 class="text-2xl font-bold mb-6">HR — Create Users & Entities</h1>

    <?php if($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4"><?= $success ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <!-- CLASS -->
    <form method="POST" class="bg-white p-4 rounded mb-4 shadow">
        <h2 class="font-semibold mb-2">Create Class</h2>
        <input type="hidden" name="type" value="class">
        <input type="text" name="name" placeholder="Class Name" class="border p-2 rounded w-full mb-2" required>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create Class</button>
    </form>

    <!-- COMPANY -->
    <form method="POST" class="bg-white p-4 rounded mb-4 shadow">
        <h2 class="font-semibold mb-2">Create Company</h2>
        <input type="hidden" name="type" value="company">
        <input type="text" name="name" placeholder="Company Name" class="border p-2 rounded w-full mb-2" required>
        <input type="text" name="address" placeholder="Address" class="border p-2 rounded w-full mb-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create Company</button>
    </form>

    <!-- COORDINATOR -->
    <form method="POST" class="bg-white p-4 rounded mb-4 shadow">
        <h2 class="font-semibold mb-2">Create Coordinator</h2>
        <input type="hidden" name="type" value="coordinator">
        <input type="text" name="name" placeholder="Full Name" class="border p-2 rounded w-full mb-2" required>
        <input type="email" name="email" placeholder="Email" class="border p-2 rounded w-full mb-2" required>
        <input type="password" name="password" placeholder="Password" class="border p-2 rounded w-full mb-2" required>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create Coordinator</button>
    </form>

    <!-- INTERNSHIP -->
    <form method="POST" class="bg-white p-4 rounded mb-4 shadow">
        <h2 class="font-semibold mb-2">Create Internship</h2>
        <input type="hidden" name="type" value="internship">
        <input type="text" name="name" placeholder="Internship Name" class="border p-2 rounded w-full mb-2" required>
        <select name="company_id" class="border p-2 rounded w-full mb-2" required>
            <option value="">Select Company</option>
            <?php foreach($companies as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="start_date" class="border p-2 rounded w-full mb-2" required>
        <input type="date" name="end_date" class="border p-2 rounded w-full mb-2" required>
        <input type="number" name="min_hours_day" placeholder="Min Hours/Day" class="border p-2 rounded w-full mb-2">
        <input type="number" name="lunch_break_minutes" placeholder="Lunch Break Minutes" class="border p-2 rounded w-full mb-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create Internship</button>
    </form>

    <!-- STUDENT -->
    <form method="POST" class="bg-white p-4 rounded mb-4 shadow">
        <h2 class="font-semibold mb-2">Create Student</h2>
        <input type="hidden" name="type" value="student">
        <input type="text" name="name" placeholder="Full Name" class="border p-2 rounded w-full mb-2" required>
        <input type="email" name="email" placeholder="Email" class="border p-2 rounded w-full mb-2" required>
        <select name="class_id" class="border p-2 rounded w-full mb-2" required>
            <option value="">Select Class</option>
            <?php foreach($classes as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="password" name="password" placeholder="Password" class="border p-2 rounded w-full mb-2" required>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create Student</button>
    </form>

    <!-- SUPERVISOR -->
    <form method="POST" class="bg-white p-4 rounded mb-4 shadow">
        <h2 class="font-semibold mb-2">Create Supervisor</h2>
        <input type="hidden" name="type" value="supervisor">
        <input type="text" name="name" placeholder="Full Name" class="border p-2 rounded w-full mb-2" required>
        <input type="email" name="email" placeholder="Email" class="border p-2 rounded w-full mb-2" required>
        <input type="password" name="password" placeholder="Password" class="border p-2 rounded w-full mb-2" required>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create Supervisor</button>
    </form>
</body>
</html>
