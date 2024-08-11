<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinator Registration</title>
</head>
<body>
    <h2>Coordinator Registration Form</h2>
    <form action="register_conn.php" method="post">
        <label for="student_id">Username</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="pin">Password</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="first_name">First Name:</label>
        <input type="text" id="firstname" name="firstname" required><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required><br><br>

        <label for="email">Position</label>
        <input type="text" id="position" name="position" required><br><br>

        <label for="phone">Department</label>
        <input type="text" id="department" name="department" required><br><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>
