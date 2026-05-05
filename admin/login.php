<?php
session_start();

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email == 'admin@gmail.com' && $password == '1234') {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            margin: 0;
            background: #f5f7fb;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 32px auto;
            padding: 0 16px;
        }
        .card {
            max-width: 460px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #d8e0ea;
            border-radius: 10px;
            padding: 22px;
        }
        h1 {
            margin: 0 0 16px;
            font-size: 28px;
        }
        .status {
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 12px;
            font-size: 14px;
            background: #fdecec;
            color: #a32a2a;
            border: 1px solid #f5c2c2;
        }
        .form-row {
            margin-bottom: 14px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }
        input {
            width: 100%;
            box-sizing: border-box;
            padding: 10px 12px;
            border: 1px solid #c8d2df;
            border-radius: 8px;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 10px 14px;
            border: none;
            border-radius: 8px;
            background: #0f5fd7;
            color: #ffffff;
            cursor: pointer;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h1>Admin Login</h1>

        <?php if (!empty($error)) { ?>
            <div class="status"><?php echo e($error); ?></div>
        <?php } ?>

        <form method="POST">
            <div class="form-row">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-row">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button class="btn" type="submit">Login</button>
        </form>
    </div>
</div>

</body>
</html>