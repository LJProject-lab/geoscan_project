<?php 

require_once '../config.php';

function checkMissingTimeOut_Intern($pdo, $coordinator_id) {
    $today = date('Y-m-d');

    // SQL query to check for missing time_out entries for the given student
    $sql = "
        SELECT DATE(t1.timestamp) as missing_date, t3.firstname, t3.lastname, t3.coordinator_id
        FROM tbl_timelogs t1
        LEFT JOIN tbl_users t3 ON t1.student_id = t3.student_id
        LEFT JOIN tbl_timelogs t2 
        ON t1.student_id = t2.student_id 
        AND DATE(t1.timestamp) = DATE(t2.timestamp) 
        AND t1.type = 'time_in' 
        AND t2.type = 'time_out'
        WHERE t1.type = 'time_in' 
        AND t2.id IS NULL
        AND DATE(t1.timestamp) < :today
        AND t3.coordinator_id = :coordinator_id; 
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['today' => $today, 'coordinator_id' => $coordinator_id]);
    
    // Fetch the result
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$coordinator_id = $_SESSION['coordinator_id']; 
$missingTimeOuts = checkMissingTimeOut_Intern($pdo, $coordinator_id);
?>
