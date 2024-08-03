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
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        // Prepare an insert statement
        $sql = "INSERT INTO students (student_id, pin, first_name, last_name, email, phone, address) VALUES (:student_id, :pin, :first_name, :last_name, :email, :phone, :address)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':pin', $pin);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
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
