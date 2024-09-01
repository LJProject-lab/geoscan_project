<?php
// Include the database connection file
require_once '../config.php';

// Check if the student_id is set in the session
if (isset($_SESSION['student_id'])) {
    // Prepare the SQL statement
    $sql = "SELECT * FROM tbl_timelogs WHERE student_id = :student_id";

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        // Bind parameters
        $stmt->bindParam(':student_id', $_SESSION['student_id'], PDO::PARAM_INT);
        // Execute the query
        $stmt->execute();
        // Fetch all rows
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }


    // Fetch student ID from session or another source
    $student_id = $_SESSION['student_id']; // Ensure this session variable is set properly

    // Fetch the program_id and program_hour for the specific student
    $programQuery = "
    SELECT A.program_hour, A.program_id 
    FROM tbl_programs AS A 
    LEFT JOIN tbl_users AS B ON A.program_id = B.program_id 
    WHERE B.student_id = :student_id
";
    $programStmt = $pdo->prepare($programQuery);
    $programStmt->execute(['student_id' => $student_id]);
    $program = $programStmt->fetch();

    // Check if the program data was found
    if ($program) {
        $program_hour = (int) $program['program_hour'];
        $program_id = $program['program_id'];
    } else {
        // Handle the case where no program data is found
        $program_hour = 500; // Default or handle as needed
        $program_id = null; // Handle as needed, maybe redirect or show an error
    }


    function getInternProgress($student_id, $program_id, $pdo)
    {
        // Fetch required hours from tbl_programs using program_id
        $programQuery = "SELECT `program_hour` FROM `tbl_programs` WHERE `program_id` = :program_id";
        $programStmt = $pdo->prepare($programQuery);
        $programStmt->execute(['program_id' => $program_id]);
        $program = $programStmt->fetch();

        // Check if the program was found and extract the required hours
        if ($program) {
            $required_hours = (int) $program['program_hour']; // Ensure it's an integer
        } else {
            // Handle the case where no program data is found
            $required_hours = 500; // Default value or error handling logic
        }

        // Fetch time in and out logs for the student
        $query = "SELECT `type`, `timestamp` FROM `tbl_timelogs` WHERE `student_id` = :student_id ORDER BY `timestamp` ASC";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['student_id' => $student_id]);

        $logs = $stmt->fetchAll();
        $total_hours = 0;

        // Calculate total hours rendered
        $time_in = null;
        foreach ($logs as $log) {
            if ($log['type'] == 'time_in') {
                $time_in = new DateTime($log['timestamp']);
            } elseif ($log['type'] == 'time_out' && $time_in) {
                $time_out = new DateTime($log['timestamp']);
                $hours = ($time_out->getTimestamp() - $time_in->getTimestamp()) / 3600;
                $total_hours += $hours;
                $time_in = null; // Reset time in
            }
        }

        // Calculate hours remaining
        $hours_remaining = $required_hours - $total_hours;

        return [
            'total_hours' => round($total_hours), // Round total hours down to remove decimals
            'hours_remaining' => round($hours_remaining), // Round hours remaining down to remove decimals
            'required_hours' => $required_hours
        ];
    }




}
?>