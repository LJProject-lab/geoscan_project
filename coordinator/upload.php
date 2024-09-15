<?php
require_once '../config.php';
require 'phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file']) && isset($_POST['program']) && isset($_POST['coordinator_id']) && isset($_POST['status'])) {
    $file = $_FILES['excel_file']['tmp_name'];
    $programId = $_POST['program'];
    $coordinatorId = $_POST['coordinator_id'];
    $status = $_POST['status'];

    if ($file) {
        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            array_shift($data);

            session_start();
            $_SESSION['import_data'] = [
                'program_id' => $programId,
                'coordinator_id' => $coordinatorId,
                'status' => $status,
                'data' => $data
            ];

            header('Location: review_import.php');
            exit();
        } catch (Exception $e) {
            echo "Error loading file: " . $e->getMessage();
        }
    } else {
        echo "No file uploaded.";
    }
} else {
    echo "Invalid request.";
}
?>
