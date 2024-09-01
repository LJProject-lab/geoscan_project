<?php



$sql = "SELECT COUNT(*) AS user_count FROM tbl_users";

$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01'); // First day of the current month
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');   // Last day of the current month

// Prepare the statement
$stmt = $pdo->prepare($sql);
// Execute the query
$stmt->execute();
// Fetch the result
$row = $stmt->fetch(PDO::FETCH_ASSOC);
// Get the user count
$user_count = $row['user_count'];

// ======

$sql2 = "SELECT COUNT(*) AS coordinator_count FROM tbl_coordinators";

// Prepare the statement
$stmt2 = $pdo->prepare($sql2);
// Execute the query
$stmt2->execute();
// Fetch the result
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
// Get the user count
$coordinator_count = $row2['coordinator_count'];

// ======

$sql2 = "SELECT COUNT(*) AS program_count FROM tbl_programs";

// Prepare the statement
$stmt2 = $pdo->prepare($sql2);
// Execute the query
$stmt2->execute();
// Fetch the result
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
// Get the user count
$program_count = $row2['program_count'];

// ======

$sql2 = "SELECT COUNT(*) AS company_count FROM tbl_companies";

// Prepare the statement
$stmt2 = $pdo->prepare($sql2);
// Execute the query
$stmt2->execute();
// Fetch the result
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
// Get the user count
$company_count = $row2['company_count'];