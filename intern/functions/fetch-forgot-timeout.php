<?php 

require_once '../config.php';

function checkMissingTimeOut($pdo, $student_id) {
    $today = date('Y-m-d');
    $threeDaysAgo = date('Y-m-d', strtotime('-3 days', strtotime($today)));
    // SQL query to check for missing time_out entries for the given student
    $sql = "
        SELECT DATE(t1.timestamp) as missing_date
        FROM tbl_timelogs t1
        LEFT JOIN tbl_timelogs t2 
        ON t1.student_id = t2.student_id 
        AND DATE(t1.timestamp) = DATE(t2.timestamp) 
        AND t1.type = 'time_in' 
        AND t2.type = 'time_out'
        WHERE t1.student_id = :student_id 
        AND t1.type = 'time_in' 
        AND t2.id IS NULL
        AND DATE(t1.timestamp) >= :threedays
        AND  DATE(t1.timestamp) <= :today
        ORDER BY t1.timestamp DESC
        LIMIT 1;  -- Only check for past dates
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['student_id' => $student_id, 'today' => $today , 'threedays' => $threeDaysAgo]);
    
    // Fetch the result
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$student_id = $_SESSION['student_id']; // Use the student_id from session
$missingTimeOuts = checkMissingTimeOut($pdo, $student_id);
?>
