<?php
session_start();
require 'config.php';

// Redirect to login if student_id session is not active
if (isset($_SESSION['student_id'])) {
    header('Location: home.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'includes/top_include.php'; ?>
    <link rel="stylesheet" href="assets/css/styles.css">

</head>

<body>
    <div class="login-container">
        <div class="login-container-header">
            <img src="assets/img/pnc-logo.png" alt="University of Cabuyao Logo">
            <span>&nbsp;Internship Management System</span>
        </div>
        <h1>INTERN</h1>
        <form id="loginForm">
            <form id="loginForm">
                <label for="student_id">Student ID:</label>
                <input type="text" id="student_id" name="student_id" required><br>

                <label for="pin">Pin:</label>
                <input type="password" id="pin" name="pin" required><br>
                <button type="submit">Login</button>
                <br>
                <br>
                <a href="./">Back</a>
            </form>
            <div id="message" class="message"></div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (event) => {
            event.preventDefault();
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = '';

            const student_id = document.getElementById('student_id').value;
            const pin = document.getElementById('pin').value;

            try {
                const response = await fetch('login_process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ student_id, pin })
                });

                const result = await response.json();
                if (result.success) {
                    window.location.href = 'home.php';
                } else {
                    messageDiv.textContent = result.message;
                }
            } catch (error) {
                console.error('Error:', error);
                messageDiv.textContent = 'Login failed. Please try again.';
            }
        });
    </script>
</body>

</html>