<?php
require '../config/db.php';

// Step 1: Check if ID exists in URL
if (!isset($_GET['id'])) {
    echo "Invalid request";
    exit();
}

// Step 2: Get ID
$id = $_GET['id'];

// Step 3: Prepare query (single event)
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");

// Step 4: Execute
$stmt->execute([$id]);

// Step 5: Fetch single row
$event = $stmt->fetch();

// Step 6: Check if event exists
if (!$event) {
    echo "Event not found";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $event['name']; ?></title>
</head>
<body>

<h2><?php echo $event['name']; ?></h2>

<p><strong>Description:</strong> <?php echo $event['description']; ?></p>

<p><strong>Location:</strong> <?php echo $event['location']; ?></p>

<p><strong>Date:</strong> <?php echo $event['event_date']; ?></p>

<p><strong>Registration Deadline:</strong> <?php echo $event['deadline']; ?></p>

<p><strong>Total Seats:</strong> <?php echo $event['total_seats']; ?></p>

</body>
</html>