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

// // ======

// $sql2 = "SELECT COUNT(*) AS client_count FROM tbl_clients";

// // Prepare the statement
// $stmt2 = $pdo->prepare($sql2);
// // Execute the query
// $stmt2->execute();
// // Fetch the result
// $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
// // Get the user count
// $client_count = $row2['client_count'];