<?php
include "../config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $student_id = $_POST['student_id'];
    $status = $_POST['status'];
    $form_type = $_POST['form_type'];

    // File upload handling
    $targetDir = "requirements/";
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    
    // Check if file is a valid type
    $allowedTypes = array("pdf", "doc", "docx", "jpg", "png");
    if (!in_array($fileType, $allowedTypes)) {
        $_SESSION['alert_type'] = "error";
        $_SESSION['alert_message'] = "Sorry, only PDF, DOC, DOCX, JPG, and PNG files are allowed.";
        header("Location: requirement_checklist.php");
        exit;
    }

    // Attempt to upload the file
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
        // Insert file info into the database
        $stmt = $pdo->prepare("
            INSERT INTO tbl_requirements (student_id, form_type, file_name, file_path, status, uploaded_at) 
            VALUES (:student_id, :form_type, :file_name, :file_path, :status, CURRENT_TIMESTAMP)
        ");
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':form_type', $form_type);
        $stmt->bindParam(':file_name', $fileName);
        $stmt->bindParam(':file_path', $targetFilePath);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            $_SESSION['alert_type'] = "success";
            $_SESSION['alert_message'] = "File uploaded and information saved successfully.";
            $_SESSION['uploadedFilePath'] = $targetFilePath;
        } else {
            $_SESSION['alert_type'] = "error";
            $_SESSION['alert_message'] = "Failed to save file information to the database.";
        }
    } else {
        $_SESSION['alert_type'] = "error";
        $_SESSION['alert_message'] = "Sorry, there was an error uploading your file.";
    }

    // Redirect back to the form page
    header("Location: requirement_checklist.php");
    exit;
}
?>
