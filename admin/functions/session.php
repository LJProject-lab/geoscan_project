<?php 

// Redirect to login if username session is not active
if (!isset($_SESSION['admin_id'])) {
    header('Location: ./');
    exit;
} 