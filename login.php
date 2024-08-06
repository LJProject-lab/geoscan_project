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
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
</head>

<style>
    body{
        background-color: #f6f9ff;
    }
</style>

<body>
        <div class="col-lg-3">
          <div class="card">
            <div class="card-body">
            <div class="login-container-header">
                <img src="assets/img/pnc-logo.png" alt="University of Cabuyao Logo">
                <span>&nbsp;Internship Management System</span>
            </div><br>
            <div class="card-title">
              <h5>INTERN</h5>
            </div>
              <!-- Vertical Form -->
              <form id="loginForm" class="row g-3">
                <div class="col-12">
                  <label for="inputNanme4" class="form-label">Student Id</label>
                  <input type="text" class="form-control" id="student_id" name="student_id">
                </div>
                <div class="col-12">
                  <label for="inputEmail4" class="form-label">Pin</label>
                  <input type="password" class="form-control" id="pin" name="pin">
                </div><br><br><br><br>
                <div class="d-grid gap-2 mt-3">
                    <button class="btn btn-success btn-lg" type="submit"><i class="bx bx-door-open"></i> Login</button>
                </div>
                <div class="text-center">
                  <a class="backbtn" href="./">Back</a>
                </div>
              </form><!-- Vertical Form -->
              <div id="message" class="message"></div>
            </div>
          </div>





    <!-------- For edit
    <div class="login-container">
        <div class="login-container-header">
            <img src="assets/img/pnc-logo.png" alt="University of Cabuyao Logo">
            <span>&nbsp;Internship Management System</span>
        </div>
        <h1>INTERN</h1>
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
    -------->
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
                    window.location.href = 'intern/dashboard.php';
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