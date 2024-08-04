<?php
// Database configuration
$dsn = 'mysql:host=localhost;dbname=pnc';
$username = 'root'; // replace with your database username
$password = ''; // replace with your database password

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $student_id = $_POST['student_id'];
        $pin = $_POST['pin'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        // Prepare an insert statement
        $sql = "INSERT INTO tbl_users (student_id, pin, firstname, lastname, email, phone, address) VALUES (:student_id, :pin, :firstname, :lastname, :email, :phone, :address)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':pin', $pin);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Student registered successfully.";
        } else {
            echo "Error: Could not register student.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection
$pdo = null;
?>
