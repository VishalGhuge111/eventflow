<?php
require '../config/db.php';

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Validate ID
if (!isset($_GET['id'])) {
    echo "Invalid request";
    exit();
}

$event_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$event_id) {
    echo "Invalid request";
    exit();
}

$error = '';
$success = '';
$formData = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'college' => '',
    'branch' => '',
    'year' => ''
];

if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success = "Registered successfully!";
}

// Fetch event
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    echo "Event not found";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $college = trim($_POST['college'] ?? '');
    $branch = trim($_POST['branch'] ?? '');
    $year = trim($_POST['year'] ?? '');

    $formData = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'college' => $college,
        'branch' => $branch,
        'year' => $year
    ];

    try {
        // 🔥 STEP 1: Check seats
        if ($event['total_seats'] <= 0) {
            $error = "Sorry, this event is full.";
        } else {

            // 🔥 STEP 2: Insert registration
            $stmt = $pdo->prepare("INSERT INTO registrations 
                (event_id, name, email, phone, college, branch, year) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                $event_id,
                $name,
                $email,
                $phone,
                $college,
                $branch,
                $year
            ]);

            // 🔥 STEP 3: Decrease seat
            $stmt = $pdo->prepare("UPDATE events SET total_seats = total_seats - 1 WHERE id = ?");
            $stmt->execute([$event_id]);

            // PRG pattern: avoid duplicate POST on refresh and load fresh seat count.
            header("Location: event.php?id=" . $event_id . "&success=1");
            exit();
        }

    } catch (PDOException $e) {

        if ($e->getCode() == 23000) {
            $error = "You have already registered for this event.";
        } else {
            $error = "Something went wrong!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo e($event['name']); ?></title>
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
        .card {
            background: #ffffff;
            border: 1px solid #d8e0ea;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 16px;
        }
        h1, h2 {
            margin: 0 0 16px;
            font-size: 28px;
        }
        h3 {
            margin: 0 0 14px;
            font-size: 20px;
        }
        p {
            margin: 8px 0;
            line-height: 1.5;
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
        input {
            width: 100%;
            box-sizing: border-box;
            padding: 10px 12px;
            border: 1px solid #c8d2df;
            border-radius: 8px;
            font-size: 14px;
        }
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 8px;
            align-items: center;
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

<div class="card">
<h1><?php echo e($event['name']); ?></h1>

<p><strong>Description:</strong> <?php echo e($event['description']); ?></p>
<p><strong>Location:</strong> <?php echo e($event['location']); ?></p>
<p><strong>Date:</strong> <?php echo e($event['event_date']); ?></p>
<p><strong>Deadline:</strong> <?php echo e($event['deadline']); ?></p>
<p><strong>Available Seats:</strong> <?php echo e($event['total_seats']); ?></p>
</div>

<div class="card">
<h3>Register for this Event</h3>

<?php if (!empty($success)) { ?>
    <div class="status success"><?php echo e($success); ?></div>
<?php } ?>

<?php if (!empty($error)) { ?>
    <div class="status error"><?php echo e($error); ?></div>
<?php } ?>

<form method="POST">

    <div class="form-row">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo e($formData['name']); ?>" required>
    </div>

    <div class="form-row">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo e($formData['email']); ?>" required>
    </div>

    <div class="form-row">
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" value="<?php echo e($formData['phone']); ?>" required>
    </div>

    <div class="form-row">
        <label for="college">College</label>
        <input type="text" id="college" name="college" value="<?php echo e($formData['college']); ?>" required>
    </div>

    <div class="form-row">
        <label for="branch">Branch</label>
        <input type="text" id="branch" name="branch" value="<?php echo e($formData['branch']); ?>" required>
    </div>

    <div class="form-row">
        <label for="year">Year</label>
        <input type="text" id="year" name="year" value="<?php echo e($formData['year']); ?>" required>
    </div>

    <div class="actions">
        <button class="btn" type="submit">Register</button>
        <a class="btn secondary" href="index.php">Back to Events</a>
    </div>

</form>
</div>

</div>

</body>
</html>