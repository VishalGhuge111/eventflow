<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require '../config/db.php';

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Join registrations with event name
$status = '';
if (isset($_GET['deleted']) && $_GET['deleted'] == '1') {
    $status = 'Registration deleted successfully.';
}

$stmt = $pdo->prepare("
    SELECT r.*, e.name AS event_name 
    FROM registrations r
    JOIN events e ON r.event_id = e.id
    ORDER BY r.created_at DESC
");
$stmt->execute();
$registrations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrations</title>
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
        }
        h1 {
            margin: 0 0 18px;
            font-size: 28px;
        }
        .top-actions {
            margin-bottom: 14px;
        }
        .btn {
            display: inline-block;
            padding: 10px 14px;
            border: none;
            border-radius: 8px;
            background: #4b5563;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        .btn.danger {
            background: #b42323;
        }
        .status {
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 12px;
            font-size: 14px;
            background: #eaf7ef;
            color: #1f7a3a;
            border: 1px solid #bfe4cc;
        }
        .empty {
            background: #ffffff;
            border: 1px dashed #c8d2df;
            border-radius: 10px;
            padding: 16px;
        }
        .table-wrap {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th,
        td {
            border: 1px solid #d8e0ea;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #eef3f9;
        }
    </style>
</head>
<body>

<div class="container">

<div class="top-actions">
    <a class="btn" href="dashboard.php">Back to Dashboard</a>
</div>

<div class="card">
<h1>All Registrations</h1>

<?php if (!empty($status)) { ?>
    <div class="status"><?php echo e($status); ?></div>
<?php } ?>

<?php if (empty($registrations)) { ?>
    <div class="empty">No registrations found.</div>
<?php } else { ?>

<div class="table-wrap">
<table>
    <tr>
        <th>Event</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>College</th>
        <th>Branch</th>
        <th>Year</th>
        <th>Action</th>
    </tr>

    <?php foreach ($registrations as $reg) { ?>
    <tr>
        <td><?php echo e($reg['event_name']); ?></td>
        <td><?php echo e($reg['name']); ?></td>
        <td><?php echo e($reg['email']); ?></td>
        <td><?php echo e($reg['phone']); ?></td>
        <td><?php echo e($reg['college']); ?></td>
        <td><?php echo e($reg['branch']); ?></td>
        <td><?php echo e($reg['year']); ?></td>
        <td>
            <a
                class="btn danger"
                href="delete_registration.php?id=<?php echo (int) $reg['id']; ?>"
                onclick="return confirm('Delete this registration?');"
            >Delete</a>
        </td>
    </tr>
    <?php } ?>

</table>
</div>

<?php } ?>

</div>

</div>

</body>
</html>