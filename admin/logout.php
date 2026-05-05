<?php
session_start();

// remove all session data
session_unset();

// destroy session
session_destroy();

// redirect to login
header("Location: login.php");
exit();