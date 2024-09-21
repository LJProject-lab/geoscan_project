<?php
// Include database connection
require_once '../../config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $admin_id = isset($_POST['admin_id']) ? $_POST['admin_id'] : null;
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $newPassword = isset($_POST['newpass']) ? $_POST['newpass'] : null;

    // Validate user ID
    if (!$admin_id) {
        $msg = 'Admin ID is required.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Fetch existing data from the database
    $sql_fetch_user = "SELECT * FROM tbl_admin WHERE admin_id = :admin_id";
    $stmt_fetch_user = $pdo->prepare($sql_fetch_user);
    $stmt_fetch_user->bindParam(':admin_id', $admin_id);
    $stmt_fetch_user->execute();
    $existingUserData = $stmt_fetch_user->fetch(PDO::FETCH_ASSOC);

    // Compare form data with existing data
    if (
        $username == $existingUserData['username'] &&
        empty(trim($newPassword))
    ) {
        $msg = 'No changes were made.';
        echo json_encode(['msg' => $msg]);
        exit();
    }


    // Validate username
    if (empty(trim($username))) {
        $msg = 'Please enter a username.';
        echo json_encode(['msg' => $msg]);
        exit();
    }

    // Validate password
    if (!empty(trim($newPassword))) {
        if (strlen($newPassword) < 8) {
            $msg = 'New Password must be at least 8 characters long.';
            echo json_encode(['msg' => $msg]);
            exit();
        }
    }

    // Retrieve old password from the database
    $sql_select_password = "SELECT password FROM tbl_admin WHERE admin_id = :admin_id";
    $stmt_select_password = $pdo->prepare($sql_select_password);
    $stmt_select_password->bindParam(':admin_id', $admin_id);
    $stmt_select_password->execute();
    $row = $stmt_select_password->fetch(PDO::FETCH_ASSOC);
    $oldPassword = $row['password'];

    // Compare old password with new password
    if (!empty(trim($newPassword))) {
        if (password_verify($newPassword, $oldPassword)) {
            $msg = 'New password must be different from the old password.';
            echo json_encode(['msg' => $msg]);
            exit();
        } else {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            // Update data in the database
            $sql = "UPDATE tbl_admin SET password = :password, username = :username WHERE admin_id = :admin_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':admin_id', $admin_id);

            try {
                // Execute the prepared statement
                if ($stmt->execute()) {
                    $success = 'Profile successfully updated!';
                    echo json_encode(['success' => $success]);
                    exit();
                } else {
                    $msg = 'Oops! Something went wrong. Please try again later.';
                    echo json_encode(['msg' => $msg]);
                    exit();
                }
            } catch (PDOException $e) {
                $msg = 'Error: ' . $e->getMessage();
                echo json_encode(['msg' => $msg]);
                exit();
            }
        }
    } else {

        // Update data in the database
        $sql = "UPDATE tbl_admin SET username = :username WHERE admin_id = :admin_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':admin_id', $admin_id);

        try {
            // Execute the prepared statement
            if ($stmt->execute()) {
                $success = 'Profile successfully updated!';
                echo json_encode(['success' => $success]);
                exit();
            } else {
                $msg = 'Oops! Something went wrong. Please try again later.';
                echo json_encode(['msg' => $msg]);
                exit();
            }
        } catch (PDOException $e) {
            $msg = 'Error: ' . $e->getMessage();
            echo json_encode(['msg' => $msg]);
            exit();
        }

    }

} else {
    $msg = 'Invalid request method.';
    echo json_encode(['msg' => $msg]);
    exit();
}
?>