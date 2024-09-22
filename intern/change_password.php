<?php
require '../config.php'; // Include your database connection file

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $currentPin = $_POST['pin'];
    $newPin = $_POST['newpin'];
    $renewPin = $_POST['renewpin'];

    // Validate the new passwords
    if ($newPin !== $renewPin) {
        echo 'New pins do not match.';
        exit;
    }

    // Get the current hashed password from the database
    try {
        $stmt = $pdo->prepare("SELECT pin FROM tbl_users WHERE student_id = :student_id");
        $stmt->execute(['student_id' => $_SESSION['student_id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $hashedPin = $row['pin'];

            // Verify the current password
            if (password_verify($currentPin, $hashedPin)) {
                // Hash the new password
                $newHashedPin = password_hash($newPin, PASSWORD_BCRYPT);

                // Update the password in the database
                $updateStmt = $pdo->prepare("UPDATE tbl_users SET pin = :new_pin WHERE student_id = :student_id");
                $updateStmt->execute(['new_pin' => $newHashedPin, 'student_id' => $_SESSION['student_id']]);

                echo 'success';
            } else {
                echo 'Current pin is incorrect.';
            }
        } else {
            echo 'Intern not found.';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

?>