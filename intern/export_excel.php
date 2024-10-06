<?php

require '../coordinator/phpspreadsheet/vendor/autoload.php'; // Path to PhpSpreadsheet autoload

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

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
    $intern_name = $_POST['intern_name'];
    $intern_program = $_POST['intern_program'];

    // Ensure inputs are not empty
    if (empty($intern_id) || empty($start_date) || empty($end_date)) {
        die('Invalid input');
    }

    // Fetch data from the database
    try {
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

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Time Logs');

        // Set header row
        $sheet->setCellValue('A1', 'Intern Name: ' . $intern_name);
        $sheet->setCellValue('A2', 'Program: ' . $intern_program);
        $sheet->setCellValue('A3', 'Type');
        $sheet->setCellValue('B3', 'Date');
        $sheet->setCellValue('C3', 'Time');
        $sheet->setCellValue('D3', 'Address');

        // Populate the spreadsheet with data
        $row = 4;
        foreach ($data as $record) {
            // Format timestamp
            $dateTime = new DateTime($record['timestamp']);
            $formattedDate = $dateTime->format('F j, Y');
            $formattedTime = $dateTime->format('g:iA');
            $typeLabel = $record['type'] === 'time_in' ? 'Time In' : 'Time Out';

            $address = convertCoordinates($record['latitude'], $record['longitude']);
            
            $sheet->setCellValueExplicit('A' . $row, $typeLabel, DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $row, $formattedDate);
            $sheet->setCellValue('C' . $row, $formattedTime);
            $sheet->setCellValueExplicit('D' . $row, $address, DataType::TYPE_STRING);

            $row++;
        }

        // Send the file to the browser
        $writer = new Xlsx($spreadsheet);
        $filename = 'TimeLogs_' . date('YmdHis') . '.xlsx';

        // Clear the output buffer to avoid any additional output
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Set headers for file download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Write the file to the output
        $writer->save('php://output');
        exit();
    } catch (Exception $e) {
        // Handle exceptions
        error_log('Error generating the Excel file: ' . $e->getMessage());
        die('Error generating the Excel file.');
    }
} else {
    die('Invalid request method.');
}
?>
