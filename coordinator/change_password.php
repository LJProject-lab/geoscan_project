<?php
require 'config.php'; // Include your database connection file

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $currentPassword = $_POST['password'];
    $newPassword = $_POST['newpassword'];
    $renewPassword = $_POST['renewpassword'];

    // Validate the new passwords
    if ($newPassword !== $renewPassword) {
        echo 'New passwords do not match.';
        exit;
    }

    // Get the current hashed password from the database
    try {
        $stmt = $pdo->prepare("SELECT password FROM tbl_coordinators WHERE coordinator_id = :coordinator_id");
        $stmt->execute(['coordinator_id' => $_SESSION['coordinator_id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $hashedPassword = $row['password'];

            // Verify the current password
            if (password_verify($currentPassword, $hashedPassword)) {
                // Hash the new password
                $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                // Update the password in the database
                $updateStmt = $pdo->prepare("UPDATE tbl_coordinators SET password = :new_password WHERE coordinator_id = :coordinator_id");
                $updateStmt->execute(['new_password' => $newHashedPassword, 'coordinator_id' => $_SESSION['coordinator_id']]);

                echo 'success';
            } else {
                echo 'Current password is incorrect.';
            }
        } else {
            echo 'Coordinator not found.';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>