<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token'], $_POST['password'])) {
    // Get token and new password
    $token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the new password

    // Check if token is valid and not expired
    $stmt = $pdo->prepare('SELECT * FROM tbl_admin WHERE reset_token = :token AND token_expiry > NOW()');
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Update password
        $stmt = $pdo->prepare('UPDATE tbl_admin SET password = :password, reset_token = NULL, token_expiry = NULL WHERE reset_token = :token');
        $stmt->execute([
            'password' => $new_password,
            'token' => $token
        ]);

        $msg = 'Your password has been reset successfully.';
    } else {
        $msg = 'Invalid or expired token.';
    }
    header("Location: reset_password.php?token=" . urlencode($token) . "&msg=" . urlencode($msg));
    exit;
} elseif (isset($_GET['token'])) {
    $token = $_GET['token'];

?>
<!DOCTYPE html>
<html lang="en">

<?php
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

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
        color: green;
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
                        <h5>Reset Pin</h5>
                    </div>
                    <form action="admin_reset_password.php" method="POST">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <label for="pin">New Pin:</label>
                        <input type="password" name="password" required>
                            <div class="d-grid gap-2 mt-3">
                                <button class="btn-main" style="border-radius: 5px;" type="submit">Reset Password</button>
                            </div><br>
                            <?php if (!empty($msg)): ?>
                                <div class="msg">
                                    <?php echo htmlspecialchars($msg); ?>
                                </div>
                            <?php endif; ?>
                            <br>
                            <div class="text-center">
                                <a class="backbtn" href="login.php">Back to Login</a>
                            </div>
                    </form>
                    </div>
            </div>
        </div>
    </body>
</html>
<?php
}
?>
