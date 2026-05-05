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

$status = '';
if (isset($_GET['event_deleted']) && $_GET['event_deleted'] == '1') {
    $status = 'Event deleted successfully.';
}

$stmt = $pdo->prepare("SELECT id, name, event_date, total_seats FROM events ORDER BY created_at DESC");
$stmt->execute();
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
            padding: 22px;
            margin-bottom: 14px;
        }
        h1 {
            margin: 0 0 18px;
            font-size: 28px;
        }
        h2 {
            margin: 0 0 14px;
            font-size: 21px;
        }
        .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 14px;
            border: none;
            border-radius: 8px;
            background: #0f5fd7;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        .btn.secondary {
            background: #4b5563;
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
    <div class="card">
        <h1>Admin Dashboard</h1>

        <div class="nav-links">
            <a class="btn" href="create_event.php">Create Event</a>
            <a class="btn secondary" href="registrations.php">View Registrations</a>
            <a class="btn danger" href="logout.php">Logout</a>
        </div>
    </div>

    <div class="card">
        <h2>Manage Events</h2>

        <?php if (!empty($status)) { ?>
            <div class="status"><?php echo e($status); ?></div>
        <?php } ?>

        <?php if (empty($events)) { ?>
            <div class="empty">No events available.</div>
        <?php } else { ?>
            <div class="table-wrap">
                <table>
                    <tr>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Seats</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($events as $event) { ?>
                        <tr>
                            <td><?php echo e($event['name']); ?></td>
                            <td><?php echo e($event['event_date']); ?></td>
                            <td><?php echo e($event['total_seats']); ?></td>
                            <td>
                                <a
                                    class="btn danger"
                                    href="delete_event.php?id=<?php echo (int) $event['id']; ?>"
                                    onclick="return confirm('Delete this event and all related registrations?');"
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