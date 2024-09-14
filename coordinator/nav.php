<?php
require_once "../config.php";

if (!isset($_SESSION['username'])) {
  header("Location: ../");
  exit();
}

// Get the coordinator's ID from the session
$coordinator_id = $_SESSION['coordinator_id'];

// SQL to get the count of pending adjustment requests
$countSql = "
    SELECT COUNT(*) as count
    FROM tbl_adjustments a
    LEFT JOIN tbl_users u ON a.student_id = u.student_id
    WHERE a.status = 'Pending'
    AND u.coordinator_id = :coordinator_id
";

$countStmt = $pdo->prepare($countSql);
$countStmt->execute(['coordinator_id' => $coordinator_id]);
$countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
$pendingCount = $countResult['count'];

// SQL to get the details of pending adjustment requests
$detailsSql = "
    SELECT a.*, u.firstname, u.lastname
    FROM tbl_adjustments a
    LEFT JOIN tbl_users u ON a.student_id = u.student_id
    WHERE a.status = 'Pending'
    AND u.coordinator_id = :coordinator_id
    ORDER BY a.createdAt DESC
    LIMIT 5
";

$detailsStmt = $pdo->prepare($detailsSql);
$detailsStmt->execute(['coordinator_id' => $coordinator_id]);
$adjustments = $detailsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Internship Management</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/pnc-logo.png" rel="icon">
  <link href="assets/img/pnc-logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="./" class="logo d-flex align-items-center"
        style="color: #198754; font-family: Century; font-weight:bold;">
        <img src="assets/img/pnc-logo.png" alt="Logo" style="height: 45px;">
        &nbsp;IMS
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-success badge-number"><?php echo $pendingCount; ?></span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have <?php echo $pendingCount; ?> new notifications
              <a href="intern_adjustments.php"><span class="badge rounded-pill p-2 ms-2" style="background-color:#198754;">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <?php foreach ($adjustments as $adjustment): ?>
              <li class="notification-item">
                <i class="bi bi-exclamation-circle text-warning"></i>
                <div>
                  <h4><?php echo htmlspecialchars($adjustment['firstname'] . ' ' . $adjustment['lastname']); ?></h4>
                  <p>Requested adjustment for the following dates: <?php echo htmlspecialchars($adjustment['records']); ?>
                  </p>
                  <p><?php echo htmlspecialchars($adjustment['createdAt']); ?></p>
                </div>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
            <?php endforeach; ?>



            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="intern_adjustments.php">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->
        <li class="nav-item dropdown pe-3">


          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/user.png" alt="Profile" class="rounded-circle">
            <span
              class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($_SESSION['firstname']); ?>&nbsp;</span>
          </a><!-- End Profile Iamge Icon -->


          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?></h6>
              <span>Coordinator</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="profile.php">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->