<?php
require 'config.php';

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
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
    .msg{
        color: red;
        font-weight: bold;
        text-align: center;
    }
</style>

<body>

<div id="internLoginCard" class="col-lg-3 card-container">
        <div class="card">
            <div class="card-body">
                <div class="login-container-header">
                    <img src="assets/img/pnc-logo.png" alt="University of Cabuyao Logo">
                </div><br>
                <div class="card-title">
                    <h5>Coordinator : Forgot Password?</h5>
                </div>
                <!-- Vertical Form -->
                <form action="coor_forgot_password_conn.php" class="row g-3" method="post">
                    <div class="col-12">
                        <label for="inputNanme4" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <br>
                    <?php if (!empty($msg)): ?>
                        <div class="msg">
                            <?php echo htmlspecialchars($msg); ?>
                        </div>
                    <?php endif; ?>
                    <br>
                    <div class="d-grid gap-2 mt-3">
                        <button class="btn-main" style="border-radius: 5px;" type="submit">Request Reset Pin</button>
                    </div>
                </form>
                <br>
                <div class="text-center">
                    <button type="button" class="btn btn-light rounded-pill"><a class="backbtn" href="login.php">Back</a></button>
                </div>
            </div>
        </div>
</div>
    
</body>

</html>