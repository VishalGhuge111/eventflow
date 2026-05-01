<?php

$host = "localhost";
$dbname = "eventflow";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // set error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "DB Connected";

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>