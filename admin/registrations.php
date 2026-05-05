<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require '../config/db.php';

// Join registrations with event name
$stmt = $pdo->query("
    SELECT r.*, e.name AS event_name 
    FROM registrations r
    JOIN events e ON r.event_id = e.id
    ORDER BY r.created_at DESC
");

$registrations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrations</title>
</head>
<body>

<h2>All Registrations</h2>

<?php if (empty($registrations)) { ?>
    <p>No registrations found.</p>
<?php } else { ?>

<table border="1" cellpadding="10">
    <tr>
        <th>Event</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>College</th>
        <th>Branch</th>
        <th>Year</th>
    </tr>

    <?php foreach ($registrations as $reg) { ?>
    <tr>
        <td><?php echo $reg['event_name']; ?></td>
        <td><?php echo $reg['name']; ?></td>
        <td><?php echo $reg['email']; ?></td>
        <td><?php echo $reg['phone']; ?></td>
        <td><?php echo $reg['college']; ?></td>
        <td><?php echo $reg['branch']; ?></td>
        <td><?php echo $reg['year']; ?></td>
    </tr>
    <?php } ?>

</table>

<?php } ?>

</body>
</html>