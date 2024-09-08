<?php
require 'phpspreadsheet/vendor/autoload.php'; // Path to PhpSpreadsheet autoload

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

require_once '../config.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form input
    $intern_id = $_POST['student_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Ensure inputs are not empty
    if (empty($intern_id) || empty($start_date) || empty($end_date)) {
        die('Invalid input');
    }

    // Convert coordinates to address (dummy implementation)
    function convertCoordinates($latitude, $longitude) {
        // Ideally, use a geocoding API to get a real address
        return "Address for ($latitude, $longitude)";
    }

    // Fetch data from the database
    try {
        $stmt = $pdo->prepare("SELECT type, timestamp, latitude, longitude, photo 
                                FROM tbl_timelogs 
                                WHERE student_id = :student_id 
                                AND timestamp BETWEEN :start_date AND :end_date");
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
        $sheet->setCellValue('A1', 'Type');
        $sheet->setCellValue('B1', 'Timestamp');
        $sheet->setCellValue('C1', 'Address');


        // Populate the spreadsheet with data
        $row = 2;
        foreach ($data as $record) {
            $address = convertCoordinates($record['latitude'], $record['longitude']);
            $photo = $record['photo']; // Assume photo is a URL or file path

            $sheet->setCellValueExplicit('A' . $row, $record['type'], DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $row, $record['timestamp']);
            $sheet->setCellValueExplicit('C' . $row, $address, DataType::TYPE_STRING);

            $row++;
        }

        // Write the file to the output
        $writer = new Xlsx($spreadsheet);
        $filename = 'TimeLogs_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    } catch (Exception $e) {
        // Handle exceptions
        error_log($e->getMessage());
        die('Error generating the Excel file.');
    }
} else {
    die('Invalid request method.');
}
?>
