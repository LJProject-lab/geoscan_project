<?php
include "nav.php";
include_once 'functions/fetch-records.php';

$student_id = $_SESSION['student_id'];

// Fetch user data to check if the fingerprint is already registered
$sql = "SELECT credential_id, attestation_object, client_data_json FROM tbl_users WHERE student_id = :student_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':student_id' => $student_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if fingerprint data is already registered
$fingerprint_registered = $user && ($user['credential_id'] || $user['attestation_object'] || $user['client_data_json']);
?>

<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link collapsed" href="index.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Profile Page Nav -->

        <li class="nav-heading">Pages</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="my_attendance.php">
                <i class="ri-fingerprint-line"></i>
                <span>My Attendance</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="requirement_checklist.php">
                <i class="bi bi-file-earmark-check-fill"></i>
                <span>Requirements Checklist</span>
            </a>
        </li><!-- End Login Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="progress_report.php">
                <i class="ri-line-chart-fill"></i>
                <span>Progress Report</span>
            </a>
        </li><!-- End Blank Page Nav -->


        <li class="nav-item">
            <a class="nav-link " href="settings.php">
                <i class='bx bxs-cog'></i>
                <span>Settings</span>
            </a>
        </li><!-- End Blank Page Nav -->

    </ul>

</aside><!-- End Sidebar-->

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Settings</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->


    <?php if (!$fingerprint_registered): ?>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body fingerprint">
                    <div class="left-fingerprint">
                        <img src="assets/img/FINGERPRINT SETTINGS.png" alt="FINGERPRINT ICON">
                    </div>
                    <div class="right-fingerprint">
                        <h1>Enable Fingerprint</h1>
                        <p class="description">Secure your account with fingerprint recognition. It's fast and easy to set
                            up.</p>
                        <a href="register_scan.php" class="add-fingerprint-btn">
                            <button class="btn-main">Add Fingerprint</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    Empty as of now.
                </div>
            </div>
        </div>
    <?php endif; ?>



</main><!-- End #main -->
<div id="preloader">
  <div class="loader"></div>
</div>
<script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>

<?php include "footer.php"; ?>