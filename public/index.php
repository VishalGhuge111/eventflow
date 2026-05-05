<?php
require '../config/db.php';

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Fetch all events (latest first)
$stmt = $pdo->prepare("SELECT * FROM events ORDER BY created_at DESC");
$stmt->execute();
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Events</title>
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
        h1 {
            margin: 0 0 18px;
            font-size: 28px;
        }
        .card {
            background: #ffffff;
            border: 1px solid #d8e0ea;
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 14px;
        }
        .card h2 {
            margin: 0 0 10px;
            font-size: 21px;
        }
        .card h2 a {
            color: #0f5fd7;
            text-decoration: none;
        }
        .card h2 a:hover {
            text-decoration: underline;
        }
        p {
            margin: 7px 0;
            line-height: 1.5;
        }
        .empty {
            background: #ffffff;
            border: 1px dashed #c8d2df;
            border-radius: 10px;
            padding: 16px;
        }
    </style>
</head>
<body>

<div class="container">

<h1>All Events</h1>

<?php if (empty($events)) { ?>
    <div class="empty">No events available.</div>
<?php } else { ?>

    <?php foreach ($events as $event) { ?>
        <div class="card">
            
            <h2><a href="event.php?id=<?php echo (int) $event['id']; ?>"><?php echo e($event['name']); ?></a></h2>
            
            <p><strong>Location:</strong> <?php echo e($event['location']); ?></p>
            
            <p><strong>Date:</strong> <?php echo e($event['event_date']); ?></p>
            
            <p><strong>Seats:</strong> <?php echo e($event['total_seats']); ?></p>
        
        </div>
    <?php } ?>

<?php } ?>

</div>

</body>
</html>