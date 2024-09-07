<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?>!</h1>
    <p>Your Student ID: <?php echo htmlspecialchars($_SESSION['student_id']); ?></p>
    <p>Your Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    <p>Your Phone: <?php echo htmlspecialchars($_SESSION['phone']); ?></p>
    <p>Your Address: <?php echo htmlspecialchars($_SESSION['address']); ?></p>
    <a href="logout.php">Logout</a><br>
    <a href="register_v2.php">Add Fingerprint Auth</a>
</body>
</html>
