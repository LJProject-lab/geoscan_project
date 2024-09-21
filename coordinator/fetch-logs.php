<?php

header('Content-Type: application/json');

require_once 'config.php';

$logs = [];

try {
    if (!isset($_SESSION['coordinator_id'])) {
        throw new Exception('Coordinator ID not set in session');
    }

    $coordinator_id = $_SESSION['coordinator_id'];
    $sql = "
        SELECT 
            tl.*, 
            u.firstname, 
            u.lastname, 
            CONCAT(u.firstname, ' ', u.lastname) AS fullname
        FROM tbl_timelogs AS tl
        JOIN tbl_users AS u
        ON tl.student_id = u.student_id
        WHERE u.coordinator_id = :coordinator_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':coordinator_id', $coordinator_id, PDO::PARAM_INT);
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Adjust photo paths to be accessible from the web
    foreach ($logs as &$log) {
        if (isset($log['photo']) && !empty($log['photo'])) {
            $log['photo'] = '../uploads/' . htmlspecialchars($log['photo']);
        } else {
            $log['photo'] = 'N/A';
        }
    }

    echo json_encode($logs);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>