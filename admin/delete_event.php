<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require '../config/db.php';

$event_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$event_id) {
    header('Location: dashboard.php');
    exit();
}

$stmt = $pdo->prepare('DELETE FROM registrations WHERE event_id = ?');
$stmt->execute([$event_id]);

$stmt = $pdo->prepare('DELETE FROM events WHERE id = ?');
$stmt->execute([$event_id]);

header('Location: dashboard.php?event_deleted=1');
exit();
