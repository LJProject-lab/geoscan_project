<?php
session_start();
require 'config.php';
// Redirect to login if student_id session is not active
if (isset($_SESSION['student_id'])) {
    header('Location: intern/dasboard.php');
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
    body {
        background-color: #f6f9ff;
    }
</style>

<body>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="login-container-header">
                    <img src="assets/img/pnc-logo.png" alt="University of Cabuyao Logo">
                    <span style="font-family: Century !important;">&nbsp;Internship Management System</span>
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
                    </div><br><br>
                    <div class="d-grid gap-2 mt-3">
                        <button class="btn-main" style="border-radius: 5px;" type="submit"><i
                                class="bx bx-door-open"></i>
                            Login</button>
                    </div>
                    <div class="text-center">
                        <a class="backbtn" href="./">Back</a>
                    </div>
                </form><!-- Vertical Form -->
                <center>
                    <div id="message" class="message"></div>
                </center>
            </div>
        </div>

        <div id="preloader">
            <div class="loader"></div>
        </div>
        <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
        <script src="assets/js/main.js"></script>
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