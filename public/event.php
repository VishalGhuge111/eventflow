<?php
require '../config/db.php';

// Step 1: Validate ID
if (!isset($_GET['id'])) {
    echo "Invalid request";
    exit();
}

$event_id = $_GET['id'];

// Step 2: Fetch event
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    echo "Event not found";
    exit();
}

// Step 3: Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $college = $_POST['college'];
    $branch = $_POST['branch'];
    $year = $_POST['year'];

    try {
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

        $success = "Registered successfully!";

    } catch (PDOException $e) {

        // Duplicate error (UNIQUE constraint)
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
<p><strong>Total Seats:</strong> <?php echo $event['total_seats']; ?></p>

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