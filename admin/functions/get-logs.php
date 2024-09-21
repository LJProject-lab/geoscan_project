<?php
require '../../config.php';

header('Content-Type: application/json');

if (isset($_POST['student_id'])) {
    $studentId = $_POST['student_id'];

    // Function to check missing time-outs
    function checkMissingTimeOut_Intern($pdo, $studentId) {
        $today = date('Y-m-d');
        $threeDaysAgo = date('Y-m-d', strtotime('-3 days', strtotime($today)));

        $sql = "
            SELECT DATE(t1.timestamp) as missing_date, t1.longitude, t1.latitude
            FROM tbl_timelogs t1
            LEFT JOIN tbl_timelogs t2 
            ON t1.student_id = t2.student_id 
            AND DATE(t1.timestamp) = DATE(t2.timestamp) 
            AND t1.type = 'time_in' 
            AND t2.type = 'time_out'
            WHERE t1.type = 'time_in' 
            AND t2.id IS NULL
            AND DATE(t1.timestamp) >= :threedays
            OR  DATE(t1.timestamp) <= :today
            AND t1.student_id = :student_id
            ORDER BY t1.timestamp ASC
            LIMIT 1;
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['today' => $today, 'student_id' => $studentId, 'threedays' => $threeDaysAgo]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    try {
        // Fetch missed time-outs
        $missingTimeOuts = checkMissingTimeOut_Intern($pdo, $studentId);

        // Return the results as JSON
        echo json_encode(['success' => true, 'data' => $missingTimeOuts]);
    } catch (PDOException $e) {
        // Return error if any
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Student ID not provided']);
}
?>
