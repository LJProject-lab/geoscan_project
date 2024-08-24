<?php
// Include database connection
require_once '../config.php';

function getAdmin($admin_id)
{
    global $pdo;

    // Prepare and execute SQL query to fetch client data based on admin_id
    $sql = 'SELECT * FROM tbl_admin WHERE admin_id = :admin_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch client data from the result
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return client data
    return $user;
}

if (isset($_SESSION['admin_id'])) {
    // Retrieve the user_id from the URL parameter
    $admin_id = $_SESSION['admin_id'];

    // Fetch admin_id data based on username
    $admin = getAdmin($admin_id);


    if ($admin) {
        // Access client data
        $admin_username = $admin['username'];
        $admin_password = $admin['password'];
        $admin_joined = $admin['createdAt'];



    } else {
        // Client not found
        echo "User not found";
    }
}

?>