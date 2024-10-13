<?php

require '../config.php';
require 'functions/session.php';
include_once 'functions/fetch-action-logs.php';
include 'includes/top_include.php';

// Include action IDs for logging
require_once 'functions/action-ids.php';

// Initialize the message variable
$message = '';

// Handling backup and import requests
if (isset($_POST['backup'])) {
    $message = backupDatabase($pdo);

    $_SESSION['message'] = $message; // Store the message in session
    header('Location: backup-restore.php'); // Redirect to the same page
    exit; // Ensure no further processing occurs
} elseif (isset($_POST['import']) && isset($_FILES['backup_file'])) {
    $filename = $_FILES['backup_file']['tmp_name']; // Get the uploaded file
    if (is_uploaded_file($filename)) {
        importDatabase($pdo, $filename); // Import without individual Toastify messages
        $message = "Successfully Restored a database"; // Set success message
        // Log the action
        logAction($pdo, ACTION_SET_RESTORE_DATABASE, "Database restored from file: " . $_FILES['backup_file']['name']);
        $_SESSION['message'] = $message; // Store the message in session
        header('Location: backup-restore.php'); // Redirect to the same page
        exit; // Ensure no further processing occurs
    } else {
        $message = "No valid file uploaded.";
    }
}

// Check for any message to display after redirect
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after displaying
}

// Backup logic
function backupDatabase($pdo)
{
    // Log the action after successful backup
    logAction($pdo, ACTION_SET_BACKUP_DATABASE, "Database backup created to file in date of: " . date("Y-m-d_H-i-s"));

    // Fetch the list of tables
    $tables = [];
    $result = $pdo->query("SHOW TABLES");
    while ($row = $result->fetchColumn()) {
        $tables[] = $row;
    }

    $return = '';
    foreach ($tables as $table) {
        // Fetch the CREATE TABLE statement
        $row2 = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
        $return .= "DROP TABLE IF EXISTS `$table`;\n";
        $return .= $row2['Create Table'] . ";\n\n";

        // Fetch the data from each table
        $data = $pdo->query("SELECT * FROM `$table`");
        foreach ($data as $row) {
            $return .= "INSERT INTO `$table` VALUES (";
            $rowValues = [];
            foreach ($row as $value) {
                $rowValues[] = isset($value) ? '"' . addslashes($value) . '"' : 'NULL';
            }
            $return .= implode(',', $rowValues);
            $return .= ");\n";
        }
        $return .= "\n\n";
    }

    // Create backup directory if it doesn't exist
    $backupDir = 'backups'; // Path to the backup directory
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true); // Create the directory with permissions
    }

    // Save backup to file in the backup directory
    $backupFile = $backupDir . "/backup_" . date("Y-m-d_H-i-s") . ".sql"; // Add timestamp to the filename
    $handle = fopen($backupFile, "w+");
    fwrite($handle, $return);
    fclose($handle);

    // Send the backup file for download
    header('Content-Description: File Transfer');
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($backupFile));
    flush(); // Flush system output buffer
    readfile($backupFile); // Read the file
    
    exit; // Exit to prevent further processing

}


// Import logic
function importDatabase($pdo, $filename)
{
    // Open the backup file
    $handle = fopen($filename, "r");
    if (!$handle) {
        return; // Return if the file can't be opened
    }

    $contents = fread($handle, filesize($filename));
    fclose($handle);

    $sql = explode(';', $contents);
    foreach ($sql as $query) {
        $query = trim($query); // Trim whitespace
        if (!empty($query)) { // Skip empty queries
            try {
                $pdo->exec($query);
                // Removed individual Toastify messages for each query
            } catch (PDOException $e) {
                // Optionally handle exceptions here, but no Toastify message
            }
        }
    }
}

// Function to log actions
function logAction($pdo, $action_id, $action_desc)
{
    $sql_log = 'INSERT INTO tbl_actionlogs (user_id, action_id, action_desc) VALUES (:user_id, :action_id, :action_desc)';
    $stmt_log = $pdo->prepare($sql_log);
    $user_id = $_SESSION['admin_id'];
    $stmt_log->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_log->bindParam(':action_id', $action_id, PDO::PARAM_INT);
    $stmt_log->bindParam(':action_desc', $action_desc, PDO::PARAM_STR);
    $stmt_log->execute();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="assets/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Toastify -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.5.0/toastify.js"
        integrity="sha512-0M1OKuNQKhBhA/vqxH7OaS1LZlDwSrSbL3QzcmrlNbkWV0U4ewn8SWfVuRS5nLGV9IXsuNnkdqzyXOYXc0Eo9w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.5.0/toastify.css"
        integrity="sha512-1xBbDQd2ydreJtocowqI+QS+xYVYdv96rB4xu/Peb5uD3SEtCJkGjnMCV3m8oH7XW35KsjqcTR6AytS5H8h8NA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.5.0/toastify.min.css"
        integrity="sha512-RJJdSTKOf+e0vTbZvyS5JTWtIBNC44IDUbkH8IF3MkJUP+YfLcK3K2nlxLS8V98m407CUgBdQrbcyRihb9e5gQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.5.0/toastify.min.js"
        integrity="sha512-DxteqSgafF6N5gacKCDX24qeqrYjuzdxQ5pNdmDLd1eJ6gibN7tlo7UD2+9qv1+8+Tu/uiYMdCuvHXlwTwZ+Ew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://www.dukelearntoprogram.com/course1/common/js/image/SimpleImage.js"></script>

</head>

<body class="sb-nav-fixed">
    <?php require_once 'includes/top_nav.php'; ?>
    <div id="layoutSidenav">
        <?php require_once 'includes/left_nav.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <br>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php" class="link-ref">Dashboard</a></li>
                        <li class="breadcrumb-item active">Backup and Restore</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="form-group">
                                <i class="fa-solid fa-user"></i>&nbsp;
                                <b>Backup and Restore</b>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="e-profile">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="tab-content pt-3">
                                        <div class="tab-pane active">
                                            <div class="col-12 col-sm-6 mb-3">
                                                <button type="submit" name="backup" class="btn btn-success">
                                                    Create Backup
                                                </button>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <label for="backup_file">Select SQL File to Import:</label>
                                                <input type="file" name="backup_file" accept=".sql">
                                                <br>
                                                <br>
                                                <div class="col-12 col-sm-6 mb-3">
                                                    <button type="submit" name="import" class="btn btn-success">
                                                        Restore Backup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </main>
            <?php require_once 'includes/footer.php'; ?>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>

    <!-- Modal CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


    <script src="assets/js/datatables-simple-demo.js"></script>
    <script>
        // Check if there is a message to display
        <?php if ($message): ?>
            const message = "<?php echo addslashes($message); ?>";
            const isSuccess = message.includes("Successfully"); // Determine success status

            Toastify({
                text: message,
                duration: 3000, // 3 seconds
                gravity: "top", // `top` or `bottom`
                position: 'right', // `left`, `center` or `right`
                backgroundColor: isSuccess ? "green" : "#dc3545", // Set background color based on success
                stopOnFocus: true, // Prevents dismissing of toast on hover
                onClick: function () { } // Callback after click
            }).showToast();
        <?php endif; ?>
    </script>

</body>

</html>