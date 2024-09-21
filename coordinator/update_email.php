<?php
session_start();
require_once 'config.php'; // Ensure this file contains your PDO setup

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $coordinator_id = trim($_POST['coordinator_id']);

    try {
        $sql = "UPDATE tbl_coordinators SET email = :email WHERE coordinator_id = :coordinator_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':coordinator_id', $coordinator_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['email'] = $email; // Update session variable
            $response['success'] = true;
            $response['message'] = 'Email updated successfully';
        } else {
            $response['message'] = 'Failed to update email';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database Error: ' . $e->getMessage();
    } catch (Exception $e) {
        $response['message'] = 'General Error: ' . $e->getMessage();
    }

    echo json_encode($response);
}