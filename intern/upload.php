<?php
require_once '../config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_image']['tmp_name'];
        $fileName = $_FILES['profile_image']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = 'uploads/profile_pics/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                try {
                    // Save the new filename to the database
                    $sql = "UPDATE tbl_users SET profile_pic = :profile_pic WHERE student_id = :student_id";
                    $stmt = $pdo->prepare($sql);
                    $student_id = $_SESSION['student_id']; // Ensure student_id is stored in the session
                    $stmt->bindParam(':profile_pic', $newFileName);
                    $stmt->bindParam(':student_id', $student_id);
                    $stmt->execute();

                    echo "File successfully uploaded and saved.";
                    header("Location: profile.php");
                } catch (PDOException $e) {
                    echo "Database error: " . $e->getMessage();
                }
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Invalid file type. Only jpg, png, gif, jpeg are allowed.";
        }
    } else {
        echo "No file uploaded or there was an error.";
    }
}

?>
