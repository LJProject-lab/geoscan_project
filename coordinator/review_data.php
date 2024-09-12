<?php

require_once '../config.php'; // Include your database connection

// Your TomTom API key
$apiKey = 'ZWUFEvqrALs4WtVDTw4yjUGGjkFPTGGE';

// Function to convert coordinates to address using TomTom API
function convertCoordinates($latitude, $longitude) {
    global $apiKey; // Use global API key

    // URL for TomTom Reverse Geocoding API
    $url = "https://api.tomtom.com/search/2/reverseGeocode/{$latitude},{$longitude}.json?key={$apiKey}";

    // Make the HTTP request
    $response = @file_get_contents($url);

    // Check if the response is valid
    if ($response === FALSE) {
        return "Error retrieving address";
    }

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check if we have results
    if (isset($data['addresses'][0]['address']['freeformAddress'])) {
        return $data['addresses'][0]['address']['freeformAddress'];
    } else {
        return "Address not found";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form input
    $intern_id = $_POST['student_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Ensure inputs are not empty
    if (empty($intern_id) || empty($start_date) || empty($end_date)) {
        die('Invalid input');
    }

    try {
        // Fetch intern details
        $stmt = $pdo->prepare("
            SELECT u.firstname, u.lastname, p.program_name
            FROM tbl_users u
            JOIN tbl_programs p ON u.program_id = p.program_id
            WHERE u.student_id = :student_id
        ");
        $stmt->bindParam(':student_id', $intern_id);
        $stmt->execute();
        $intern = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$intern) {
            die('Intern not found.');
        }

        $intern_name = $intern['lastname'] . ', ' . $intern['firstname'];
        $intern_program = $intern['program_name'];

        // Fetch time logs data
        $stmt = $pdo->prepare("
            SELECT type, timestamp, latitude, longitude
            FROM tbl_timelogs
            WHERE student_id = :student_id
            AND timestamp BETWEEN :start_date AND :end_date
        ");
        $stmt->bindParam(':student_id', $intern_id);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            die('No data found for the given criteria.');
        }

        // Prepare data for review
        $rows = [];
        foreach ($data as $record) {
            // Format timestamp
            $dateTime = new DateTime($record['timestamp']);
            $formattedDate = $dateTime->format('F j, Y');
            $formattedTime = $dateTime->format('g:iA');
            
            $address = convertCoordinates($record['latitude'], $record['longitude']);

            $rows[] = [
                'Type' => $record['type'],
                'Date' => $formattedDate,
                'Time' => $formattedTime,
                'Address' => $address
            ];
        }
    } catch (Exception $e) {
        error_log('Error retrieving data: ' . $e->getMessage());
        die('Error retrieving data.');
    }
} else {
    die('Invalid request method.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Time Logs</title>
    <!-- Include Bootstrap CSS or your preferred CSS framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Review Time Logs</h1>
    <p><strong>Intern Name:</strong> <?php echo htmlspecialchars($intern_name); ?></p>
    <p><strong>Program:</strong> <?php echo htmlspecialchars($intern_program); ?></p>
    
    <?php if ($rows): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Type</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['Type']); ?></td>
                <td><?php echo htmlspecialchars($row['Date']); ?></td>
                <td><?php echo htmlspecialchars($row['Time']); ?></td>
                <td><?php echo htmlspecialchars($row['Address']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <form action="export_excel.php" method="post">
        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($intern_id); ?>">
        <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
        <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
        <input type="hidden" name="intern_name" value="<?php echo htmlspecialchars($intern_name); ?>">
        <input type="hidden" name="intern_program" value="<?php echo htmlspecialchars($intern_program); ?>">
        <button type="submit" class="btn btn-primary">Export to Excel</button>
    </form>
    <?php else: ?>
    <p>No records found for the given criteria.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
