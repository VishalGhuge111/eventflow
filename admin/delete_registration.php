<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require '../config/db.php';

$registration_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$registration_id) {
    header('Location: registrations.php');
    exit();
}

$stmt = $pdo->prepare('DELETE FROM registrations WHERE id = ?');
$stmt->execute([$registration_id]);

header('Location: registrations.php?deleted=1');
exit();
