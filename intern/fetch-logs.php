<?php

header('Content-Type: application/json');

require_once '../config.php';

$logs = [];

try {
    if (!isset($_SESSION['student_id'])) {
        throw new Exception('Coordinator ID not set in session');
    }

    $student_id = $_SESSION['student_id'];
    $sql = "SELECT * FROM tbl_timelogs WHERE student_id = :student_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($logs);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
