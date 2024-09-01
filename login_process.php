<?php
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

$student_id = $data['student_id'] ?? '';
$pin = $data['pin'] ?? '';

if (empty($student_id) || empty($pin)) {
    echo json_encode(['success' => false, 'message' => 'Student ID and Pin are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $user = $stmt->fetch();

    if ($user) {
        // Compare plain text pin
        if (password_verify($pin, $user['pin'])) {
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['phone'] = $user['phone'];
            $_SESSION['address'] = $user['address'];

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid Pin.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Student ID not found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Login failed: ' . $e->getMessage()]);
}
?>
