<?php
// Step 1: Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require '../config/db.php';

    $name = $_POST['name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $event_date = $_POST['event_date'];
    $deadline = $_POST['deadline'];
    $total_seats = $_POST['total_seats'];

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
</head>
<body>

<h2>Create Event</h2>

<!-- 🔥 FIX: Show success after redirect -->
<?php if (isset($_GET['success'])) echo "<p style='color:green;'>Event created successfully!</p>"; ?>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST">

    <label>Event Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required></textarea><br><br>

    <label>Location:</label><br>
    <input type="text" name="location" required><br><br>

    <label>Event Date:</label><br>
    <input type="date" name="event_date" required><br><br>

    <label>Registration Deadline:</label><br>
    <input type="date" name="deadline" required><br><br>

    <label>Total Seats:</label><br>
    <input type="number" name="total_seats" required><br><br>

    <button type="submit">Create Event</button>

</form>

</body>
</html>