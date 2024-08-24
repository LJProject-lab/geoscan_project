<?php
// generate_challenge.php
require '../config.php';

// Create a challenge for the registration
$challenge = base64_encode(random_bytes(32));

try {
    // Fetch all stored credentials
    $stmt = $pdo->query("SELECT credential_id FROM tbl_users");
    $credentials = $stmt->fetchAll(PDO::FETCH_COLUMN);

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'challenge' => $challenge, 'credentials' => $credentials]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>