<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$error = '';
$success = '';
$formData = [
    'name' => '',
    'description' => '',
    'location' => '',
    'event_date' => '',
    'deadline' => '',
    'total_seats' => ''
];

if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success = 'Event created successfully!';
}

// Step 1: Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require '../config/db.php';

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $event_date = trim($_POST['event_date'] ?? '');
    $deadline = trim($_POST['deadline'] ?? '');
    $total_seats = trim($_POST['total_seats'] ?? '');

    $formData = [
        'name' => $name,
        'description' => $description,
        'location' => $location,
        'event_date' => $event_date,
        'deadline' => $deadline,
        'total_seats' => $total_seats
    ];

    try {
        $stmt = $pdo->prepare("INSERT INTO events 
            (name, description, location, event_date, deadline, total_seats) 
            VALUES (?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $name,
            $description,
            $location,
            $event_date,
            $deadline,
            $total_seats
        ]);

        // 🔥 FIX: Redirect instead of staying on POST page
        header("Location: create_event.php?success=1");
        exit();

    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
    <style>
        body {
            margin: 0;
            background: #f5f7fb;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 32px auto;
            padding: 0 16px;
        }
        .top-actions {
            margin-bottom: 14px;
        }
        .card {
            background: #ffffff;
            border: 1px solid #d8e0ea;
            border-radius: 10px;
            padding: 20px;
        }
        h1 {
            margin: 0 0 16px;
            font-size: 28px;
        }
        .status {
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 12px;
            font-size: 14px;
        }
        .status.success {
            background: #eaf7ef;
            color: #1f7a3a;
            border: 1px solid #bfe4cc;
        }
        .status.error {
            background: #fdecec;
            color: #a32a2a;
            border: 1px solid #f5c2c2;
        }
        .form-row {
            margin-bottom: 14px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }
        input,
        textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 10px 12px;
            border: 1px solid #c8d2df;
            border-radius: 8px;
            font-size: 14px;
        }
        textarea {
            min-height: 110px;
            resize: vertical;
        }
        .btn {
            display: inline-block;
            padding: 10px 14px;
            border: none;
            border-radius: 8px;
            background: #0f5fd7;
            color: #ffffff;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn.secondary {
            background: #4b5563;
        }
    </style>
</head>
<body>

<div class="container">

<div class="top-actions">
    <a class="btn secondary" href="dashboard.php">Back to Dashboard</a>
</div>

<div class="card">

<h1>Create Event</h1>

<?php if (!empty($success)) { ?>
    <div class="status success"><?php echo e($success); ?></div>
<?php } ?>

<?php if (!empty($error)) { ?>
    <div class="status error"><?php echo e($error); ?></div>
<?php } ?>

<form method="POST">

    <div class="form-row">
        <label for="name">Event Name</label>
        <input type="text" id="name" name="name" value="<?php echo e($formData['name']); ?>" required>
    </div>

    <div class="form-row">
        <label for="description">Description</label>
        <textarea id="description" name="description" required><?php echo e($formData['description']); ?></textarea>
    </div>

    <div class="form-row">
        <label for="location">Location</label>
        <input type="text" id="location" name="location" value="<?php echo e($formData['location']); ?>" required>
    </div>

    <div class="form-row">
        <label for="event_date">Event Date</label>
        <input type="date" id="event_date" name="event_date" value="<?php echo e($formData['event_date']); ?>" required>
    </div>

    <div class="form-row">
        <label for="deadline">Registration Deadline</label>
        <input type="date" id="deadline" name="deadline" value="<?php echo e($formData['deadline']); ?>" required>
    </div>

    <div class="form-row">
        <label for="total_seats">Total Seats</label>
        <input type="number" id="total_seats" name="total_seats" value="<?php echo e($formData['total_seats']); ?>" required>
    </div>

    <button class="btn" type="submit">Create Event</button>

</form>

</div>

</div>

</body>
</html>