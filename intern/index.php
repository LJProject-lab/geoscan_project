<?php
include "nav.php";
include "functions/fetch-forgot-timeout.php";
echo $currentDate = date('Y-m-d');


include_once 'functions/fetch-records.php';
$progress = getInternProgress($student_id, $program_id, $pdo);
?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

  <section class="section dashboard">

  <div class="row">
    <div class="col-xl-6">
      <div class="card">
        <div class="card-body">
          <?php
          $student_id = $_SESSION['student_id'];
          $stmt = $pdo->prepare("SELECT status, reject_reason FROM tbl_adjustments WHERE student_id = :student_id ORDER BY id DESC LIMIT 1");
          $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
          $stmt->execute();
          $adjustment = $stmt->fetch(PDO::FETCH_ASSOC);

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
                    <p>It looks like you forgot to log your time out on the following date:</p>
                    <ul>";
                foreach ($dates as $date) {
                  echo "<li><strong>{$date}</strong></li>";
                }
                echo "
                    </ul>
                    <p>Please review your attendance records and enter the correct time out for these day. This ensures your attendance is accurately tracked.</p>
                    <ul>
                        <li>If you remember your time out, please contact your coordinator for assistance.</li>
                    </ul>";

                // Check the status and display either the button or the badge
                if ($adjustment && $adjustment['status'] == 'Pending') {
                  // If the status is Pending, show the badge
                  echo "<div class='badge badge-warning' style='float:right; color:black; font-size:1rem; background-color:orange;'>Pending</div>";
                } else if ($adjustment && $adjustment['status'] == 'Rejected') {
                  // If the status is Pending, show the badge
                  echo "<div class='badge badge-warning' style='float:right; color:white; font-size:1rem; background-color:red;'>Rejected</div>";
                  echo "</br>";
                  echo "<b>Remarks: </b>" . $adjustment['reject_reason'];
                } else if ($adjustment && $adjustment['status'] == 'Approved') {
                  // If the status is Pending, show the badge
                  echo "<div class='badge badge-warning' style='float:right; color:white; font-size:1rem; background-color:blue;'>Approved</div>";
                } else {
                  // If not Pending, show the button
                  echo "
                    <div class='button-wrapper' style='float:right;'>
                        <a href='#'>
                            <button class='btn-main' data-toggle='modal' data-target='#ReqModal'>Request for Adjustment</button>
                        </a>
                    </div>";
                }

                echo "</div>";
              }
            }
          } else {
            echo "<p>All time out records are up to date.</p>";
          }
          ?>
        </div>

      </div>
    </div>
    <div class="col-xl-3">
    <div class="card info-card revenue-card">
        <div class="card-body">
          <h5 class="card-title">Logs Rendered</h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-clock-history" style="color:green !important;"></i>
            </div>
            <div class="ps-3">
              <h5>0</h5>
              <span class="text-muted small pt-2 ps-1">
                <a href="requirements_to_review.php">
                  <i class="bi bi-arrow-right"></i> &nbsp;View All
                </a>
              </span>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-xl-3">
      <div class="card info-card customers-card">
        <div class="card-body">
          <h5 class="card-title">Requirements Uploaded</h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-clipboard-check" style="color:orange !important;"></i>
            </div>
            <div class="ps-3">
              <h5>0</h5>
              <span class="text-muted small pt-2 ps-1">
                <a href="requirements_to_review.php">
                  <i class="bi bi-arrow-right"></i> &nbsp;View All
                </a>
              </span>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="modal fade" id="ReqModal" tabindex="-1" role="dialog" aria-labelledby="ReqModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ReqModalLabel" style="color:#198754;font-weight:bold;">Request for Adjustments
            </h5>
            <i class="fa-solid fa-xmark" style="font-size:20px; cursor:pointer;" data-dismiss="modal"
              aria-label="Close"></i>
          </div>
          <div class="modal-body">
            <div id="message"></div>
            <h4>Dates with No Time-Out Entries</h4>
            <br>
            <?php
            if (isset($dates) && is_array($dates)) {
              foreach ($dates as $date) {
                echo "<li>{$date}</li>";
              }
            } else {
              echo "<p>No missing time-out entries found.</p>";
            }
            ?>
            <br>
            <input type="hidden" id="dates" value="<?php echo isset($dates) ? implode(',', $dates) : ''; ?>">
            <textarea name="reason" class="form-control" id="reason" placeholder="Enter Reason"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-main" style="background-color:#DC3545;"
              data-dismiss="modal">Cancel</button>
            <button type="button" class="btn-del" id="confirmAdjustment">Submit</button>
          </div>
        </div>
      </div>
    </div>
    </div>


  </div>




</main><!-- End #main -->
<div id="preloader">
  <div class="loader"></div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
  crossorigin="anonymous"></script>
<script src="functions/js/send-adjustments.js"></script>
<script>
  var studentId = "<?php echo $_SESSION['student_id']; ?>";
</script>

<?php include "footer.php"; ?>