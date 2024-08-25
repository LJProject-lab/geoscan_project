<?php
include "config.php";
session_start();

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Prepare and execute the deletion query
    $stmt = $pdo->prepare("DELETE FROM tbl_users WHERE student_id = :student_id");
    $stmt->bindParam(':student_id', $student_id);

    if ($stmt->execute()) {
        $_SESSION['alert_type'] = "success";
        $_SESSION['alert_message'] = "Intern deleted successfully.";
    } else {
        $_SESSION['alert_type'] = "error";
        $_SESSION['alert_message'] = "Failed to delete intern.";
    }
}

header("Location: register_intern.php");
exit;
?>
