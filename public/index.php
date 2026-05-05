<?php
require '../config/db.php';

// Fetch all events (latest first)
$stmt = $pdo->query("SELECT * FROM events ORDER BY created_at DESC");
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Events</title>
</head>
<body>

<h2>All Events</h2>

<?php if (empty($events)) { ?>
    <p>No events available.</p>
<?php } else { ?>

    <?php foreach ($events as $event) { ?>
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            
            <h3><?php echo $event['name']; ?></h3>
            
            <p><strong>Location:</strong> <?php echo $event['location']; ?></p>
            
            <p><strong>Date:</strong> <?php echo $event['event_date']; ?></p>
            
            <p><strong>Seats:</strong> <?php echo $event['total_seats']; ?></p>
        
        </div>
    <?php } ?>

<?php } ?>

</body>
</html>