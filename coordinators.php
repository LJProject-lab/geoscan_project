<?php
session_start();
require 'config.php';

// if (!isset($_SESSION['coordinator'])) {
//     header("Location: login.html");
//     exit();
// }

function getAddress($latitude, $longitude) {
    $apiKey = OPENCAGE_API_KEY;
    $url = "https://api.opencagedata.com/geocode/v1/json?q=$latitude+$longitude&key=$apiKey";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if ($data && isset($data['results'][0])) {
        return $data['results'][0]['formatted'];
    }
    return 'Address not found';
}

$dateFilter = $_GET['date'] ?? date('Y-m-d');

try {
    $sql = "SELECT * FROM tbl_timelogs WHERE DATE(time_in) = :date";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':date' => $dateFilter]);
    $timelogs = $stmt->fetchAll();
} catch (Exception $e) {
    die('Error fetching timelogs: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinator - View Timelogs</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Student Timelogs</h1>
    <form method="get" action="coordinators.php">
        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($dateFilter); ?>">
        <button type="submit">Filter</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Address</th>
                <th>Time In</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($timelogs as $log): ?>
                <tr>
                    <td><?php echo htmlspecialchars($log['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($log['longitude']); ?></td>
                    <td><?php echo htmlspecialchars($log['latitude']); ?></td>
                    <td><?php echo htmlspecialchars(getAddress($log['latitude'], $log['longitude'])); ?></td>
                    <td><?php echo htmlspecialchars($log['time_in']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
