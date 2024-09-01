<?php
$uploadDir = 'uploads/';
$response = ['success' => false, 'fileName' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $file = $_FILES['photo'];
    $fileName = time() . '-' . basename($file['name']);
    $uploadFile = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        $response['success'] = true;
        $response['fileName'] = $fileName;
    } else {
        $response['error'] = 'Failed to upload file.';
    }
}

header('Content-Type: application/json');
echo json_encode($response);

// var_dump($_FILES);

?>
