<?php
$uploadDir = 'uploads/';
$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $fileName = $data['fileName'];

    $filePath = $uploadDir . $fileName;
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            $response['success'] = true;
        } else {
            $response['error'] = 'Failed to delete file.';
        }
    } else {
        $response['error'] = 'File not found.';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
