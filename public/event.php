<?php
require '../config/db.php';

// Validate ID
if (!isset($_GET['id'])) {
    echo "Invalid request";
    exit();
}

$event_id = $_GET['id'];

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

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $college = $_POST['college'];
    $branch = $_POST['branch'];
    $year = $_POST['year'];

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

            $success = "Registered successfully!";
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
    <title><?php echo $event['name']; ?></title>
</head>
<body>

<h2><?php echo $event['name']; ?></h2>

<p><strong>Description:</strong> <?php echo $event['description']; ?></p>
<p><strong>Location:</strong> <?php echo $event['location']; ?></p>
<p><strong>Date:</strong> <?php echo $event['event_date']; ?></p>
<p><strong>Deadline:</strong> <?php echo $event['deadline']; ?></p>
<p><strong>Available Seats:</strong> <?php echo $event['total_seats']; ?></p>

<hr>

<h3>Register for this Event</h3>

<?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST">

    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone" required><br><br>

    <label>College:</label><br>
    <input type="text" name="college" required><br><br>

    <label>Branch:</label><br>
    <input type="text" name="branch" required><br><br>

    <label>Year:</label><br>
    <input type="text" name="year" required><br><br>

    <button type="submit">Register</button>

</form>

</body>
</html>