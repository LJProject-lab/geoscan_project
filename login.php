<?php
require 'config.php';
// Redirect to login if student_id session is not active
if (isset($_SESSION['student_id'])) {
    header('Location: intern/');
    exit;
} else if (isset($_SESSION['admin_id'])) {
    header('Location: admin/');
    exit;
} else if (isset($_SESSION['username'])) {
    header('Location: coordinator/');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css"
        integrity="sha512-pVCM5+SN2+qwj36KonHToF2p1oIvoU3bsqxphdOIWMYmgr4ZqD3t5DjKvvetKhXGc/ZG5REYTT6ltKfExEei/Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<style>
    body {
        background-color: #f6f9ff;
    }
</style>

<body>
    <!-- Intern Login -->
    <div id="internLoginCard" class="col-lg-3 card-container">
        <div class="card">
            <div class="card-body">
                <div class="login-container-header">
                    <img src="assets/img/pnc-logo.png" alt="University of Cabuyao Logo">
                </div><br>
                <div class="card-title">
                    <h5>Login as Intern</h5>
                </div>
                <!-- Vertical Form -->
                <form id="loginForm_Intern" class="row g-3">
                    <div class="col-12">
                        <label for="inputNanme4" class="form-label">Student Id</label>
                        <input type="text" class="form-control" id="student_id" name="student_id">
                    </div>
                    <div class="col-12">
                        <label for="inputEmail4" class="form-label">Pin</label>
                        <input type="password" class="form-control" id="pin" name="pin">
                    </div><br><br>
                    <div class="d-grid gap-2 mt-3">
                        <button class="btn-main" style="border-radius: 5px;" type="submit">Login</button>
                    </div>
                    <div class="text-center">
                        <a class="backbtn" href="./">Back</a>
                    </div>
                </form>
                <center>
                    <div id="message" class="message"></div>
                </center>
            </div>
        </div>

        <!-- Toggle to show Officer login -->
        <button class="toggle-button" onclick="toggleLoginForms()">
            <i class='bx bxs-chevrons-right'></i>
        </button>

        <div id="preloader">
            <div class="loader"></div>
        </div>
    </div>

    <!-- Officer Login -->
    <div id="officerLoginCard" class="col-lg-3 card-container">
        <div class="card">
            <div class="card-body">
                <div class="login-container-header">
                    <img src="assets/img/pnc-logo.png" alt="University of Cabuyao Logo">
                </div><br>
                <div class="card-title">
                    <h5>Login as Admin | Coordinator</h5>
                </div>
                <!-- Vertical Form -->
                <form id="loginForm_officer" class="row g-3">
                    <div class="col-12">
                        <label for="inputNanme4" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>
                    <div class="col-12">
                        <label for="inputEmail4" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="col-12">
                        <label for="roleSelect" class="form-label">Select Role</label>
                        <select class="form-control" id="role" name="role">
                            <option value="admin">Admin</option>
                            <option value="coordinator">Coordinator</option>
                        </select>
                    </div><br><br>
                    <div class="d-grid gap-2 mt-3">
                        <button class="btn-main" style="border-radius: 5px;" type="submit"> Login</button>
                    </div>
                    <div class="text-center">
                        <a class="backbtn" href="./">Back</a>
                    </div>
                </form>
                <center>
                    <div id="officer_message" class="officer_message"></div>
                </center>
            </div>
        </div>

        <!-- Toggle to show Intern login -->
        <button class="toggle-button" onclick="toggleLoginForms()">
            <i class='bx bxs-chevrons-left'></i>
        </button>
    </div>

    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        // Toggle function to switch between Intern and Officer login
        function toggleLoginForms() {
            const internLogin = document.getElementById('internLoginCard');
            const officerLogin = document.getElementById('officerLoginCard');

            if (internLogin.style.display === 'none') {
                internLogin.style.display = 'block';
                officerLogin.style.display = 'none';
            } else {
                internLogin.style.display = 'none';
                officerLogin.style.display = 'block';
            }
        }

        // Intern login form submission
        document.getElementById('loginForm_Intern').addEventListener('submit', async (event) => {
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
                    window.location.href = 'intern/';
                } else {
                    messageDiv.textContent = result.message;
                }
            } catch (error) {
                console.error('Error:', error);
                messageDiv.textContent = 'Login failed. Please try again.';
            }
        });

        // Admin/Coordinator login form submission
        document.getElementById('loginForm_officer').addEventListener('submit', async (event) => {
            event.preventDefault();
            const messageDiv = document.getElementById('officer_message');
            messageDiv.textContent = '';

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const role = document.getElementById('role').value;

            let url = '';
            if (role === 'admin') {
                url = 'login_process_admin.php';
            } else if (role === 'coordinator') {
                url = 'login_process_coordinator.php';
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });

                const result = await response.json();
                if (result.success) {
                    if (role === 'admin') {
                        window.location.href = 'admin/';
                    } else if (role === 'coordinator') {
                        window.location.href = 'coordinator/';
                    }
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