<?php
session_start();
require 'config.php';

if (isset($_SESSION['username'])) {
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
    body {
        background-color: #f6f9ff;
    }
    button{
        border-radius: 5px;
    }
    button i{
        color: #fff;
    }
</style>

<body>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="login-container-header">
                    <img src="assets/img/pnc-logo.png" alt="University of Cabuyao Logo">
                </div><br>
                <div class="card-title">
                    <h5>COORDINATOR</h5>
                </div>
                <!-- Vertical Form -->
                <form id="loginForm" class="row g-3">
                    <div class="col-12">
                        <label for="inputNanme4" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>
                    <div class="col-12">
                        <label for="inputEmail4" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div><br><br><br><br>
                    <div class="d-grid gap-2 mt-3">
                        <button class="btn-main" type="submit"><i class="bx bx-door-open"></i>
                            Login</button>
                    </div>
                </form><!-- Vertical Form -->
                <center>
                    <div id="message" class="message"></div>
                </center>
            </div>
        </div>

        <script>
            document.getElementById('loginForm').addEventListener('submit', async (event) => {
                event.preventDefault();
                const messageDiv = document.getElementById('message');
                messageDiv.textContent = '';

                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;

                try {
                    const response = await fetch('login_process.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ username, password })
                    });

                    const result = await response.json();
                    if (result.success) {
                        window.location.href = 'dashboard.php';
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