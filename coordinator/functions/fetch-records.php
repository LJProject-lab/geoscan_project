<?php



// Initialize $logs as an empty array
$logs = [];



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

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':coordinator_id', $coordinator_id, PDO::PARAM_INT);
        $stmt->execute();
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
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



?>
