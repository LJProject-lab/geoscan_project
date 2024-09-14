<?php
include "nav.php";
include "functions/fetch-forgot-timeout.php";
$currentDate = date('Y-m-d');
?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link " href="dashboard.php">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-heading">Pages</li>

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
      <a class="nav-link collapsed" href="settings.php">
        <i class='bx bxs-cog'></i>
        <span>Settings</span>
      </a>
    </li><!-- End Blank Page Nav -->

  </ul>

</aside><!-- End Sidebar-->

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <div class="row">
    <div class="col-xl-6">
      <div class="card">
        <div class="card-body">
          <!-- Display here the reminder-->
          <?php
          if ($missingTimeOuts) {
            $dates = array_column($missingTimeOuts, 'missing_date');
            $today = date('Y-m-d');

            if ($today) {
              // Check if the current date is in the missing dates
              if (in_array($today, $dates)) {
                // Display message for the current day
                echo "
                      <div class='reminder-alert'>
                          <span style='color:#198754;'><h3><b>Pending Time Out</b></h3></span>
                          <p>You have not logged your time out for today (<strong>{$today}</strong>).</p>
                      </div>";
              } else {
                // Display message for past missed time outs
                echo "
                      <div class='reminder-alert'>
                          <span style='color:#198754;'><h3><b>Time Out Reminder</b></h3></span>
                          <p>It looks like you forgot to log your time out on the following dates:</p>
                          <ul>";
                foreach ($dates as $date) {
                  echo "<li><strong>{$date}</strong></li>";
                }
                echo "
                          </ul>
                          <p>Please review your attendance records and enter the correct time out for these days. This ensures your attendance is accurately tracked.</p>
                          <ul>
                              <li>If you remember your time out, please contact your coordinator for assistance.</li>
                          </ul>
                          <div class='button-wrapper' style='float:right;'>(In-development)
                              <a href='#'>
                                  <button class='btn-main'>Request for Adjustment</button>
                              </a>
                          </div>
                      </div>";
              }
            }
          } else {
            echo "<p>No time out records are missing.</p>";
          }
          ?>
        </div>
      </div>
    </div>


    <div class="col-xl-6">
      <div class="card">
        <div class="card-body">
          Empty as of now.
        </div>
      </div>
    </div>

  </div>




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