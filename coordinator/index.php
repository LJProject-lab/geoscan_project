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
      <a class="nav-link " href="index.php">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-heading">Configuration</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="add_intern.php">
        <i class="bi bi-person-plus-fill"></i>
        <span>Add Intern</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="generate_report.php">
        <i class="ri-folder-download-line"></i>
        <span>Generate Intern Report</span>
      </a>
    </li>

    <li class="nav-heading">Pages</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="intern.php">
        <i class="bi bi-people-fill"></i>
        <span>List of Intern</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="requirement_checklist.php">
        <i class="bi bi-file-earmark-check-fill"></i>
        <span>Requirements Checklist</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="interns_attendance.php">
        <i class="bx bxs-user-detail"></i>
        <span>Interns Attendance</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="interns_progress_report.php">
        <i class="ri-line-chart-fill"></i>
        <span>Progress Report</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="intern_adjustments.php">
        <i class='bx bxs-cog'></i>
        <span>Intern Adjustments</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="action_logs.php">
        <i class='bx bx-history'></i>
        <span>Audit Trail</span>
      </a>
    </li>

  </ul>

</aside><!-- End Sidebar-->

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <!-- Change from 'container' to 'container-fluid' -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-12">
        <div class="row">

          <!-- Sales Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card sales-card">

              <div class="card-body">
                <h5 class="card-title">Registered Interns</h5>

                <?php
                // Prepare the SQL query to count rows with the specific coordinator_id
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE coordinator_id = :coordinator_id");
                $stmt->bindParam(':coordinator_id', $_SESSION['coordinator_id'], PDO::PARAM_INT);

                // Execute the query
                $stmt->execute();

                // Fetch the count
                $count = $stmt->fetchColumn();
                ?>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-person-check"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?php echo $count; ?></h6>
                    <span class="text-muted small pt-2 ps-1">
                      <a href="intern.php">
                        <i class="bi bi-arrow-right"></i> &nbsp;View All
                      </a>
                    </span>
                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Sales Card -->

          <!-- Revenue Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card revenue-card">

              <div class="card-body">
                <h5 class="card-title">Attendance <span>| Today</span></h5>

                <?php
                $today = date('Y-m-d');

                $stmt = $pdo->prepare("
                   SELECT student_id
                   FROM tbl_users
                   WHERE coordinator_id = :coordinator_id
               ");
                $stmt->bindParam(':coordinator_id', $_SESSION['coordinator_id'], PDO::PARAM_INT);
                $stmt->execute();

                // Fetch all student_id values
                $student_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

                if (empty($student_ids)) {
                  $count = 0; // No students, so count is zero
                } else {
                  // Step 2: Prepare a query to count unique student_id for today
                  $placeholders = implode(',', array_fill(0, count($student_ids), '?'));
                  $stmt = $pdo->prepare("
                       SELECT COUNT(DISTINCT student_id)
                       FROM tbl_timelogs
                       WHERE student_id IN ($placeholders) AND DATE(timestamp) = ?
                   ");
                  $params = array_merge($student_ids, [$today]);
                  $stmt->execute($params);

                  // Fetch the count
                  $count = $stmt->fetchColumn();
                }
                ?>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-clock-history"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?php echo $count; ?></h6>
                    <span class="text-muted small pt-2 ps-1">
                      <a href="#">
                        <i class="bi bi-arrow-right"></i> &nbsp;View All
                      </a>
                    </span>
                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Revenue Card -->

          <!-- Customers Card -->
          <div class="col-xxl-4 col-xl-12">

            <div class="card info-card customers-card">

              <div class="card-body">
                <h5 class="card-title">Requirements to Review</h5>

                <?php
                // Fetch student IDs for the given coordinator_id
                $stmt = $pdo->prepare("
          SELECT student_id 
          FROM tbl_users 
          WHERE coordinator_id = :coordinator_id
      ");
                $stmt->execute(['coordinator_id' => $_SESSION['coordinator_id']]);
                $student_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

                // Initialize the count variable
                $pendingCount = 0;

                if (!empty($student_ids)) {
                  // Create a placeholder string for the SQL query
                  $placeholders = implode(',', array_fill(0, count($student_ids), '?'));

                  // Count records in tbl_requirements with status 'pending' for the fetched student_ids
                  $countStmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM tbl_requirements 
        WHERE student_id IN ($placeholders) AND status = 'pending'
    ");
                  $countStmt->execute($student_ids);

                  // Fetch the count
                  $pendingCount = $countStmt->fetchColumn();
                }
                ?>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-clipboard-check"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?php echo htmlspecialchars($pendingCount); ?></h6>
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


          <div class="col-sm-12 col-xl-8">
            <div class="card">

              <div class="card-body">
                <h5 class="card-title">Reports <span>/Todays</span></h5>

                <?php
                $today = date('Y-m-d');

                $sql = "
                      SELECT u.student_id, u.firstname, u.lastname, 
                            MAX(CASE WHEN t.type = 'time_in' THEN t.timestamp ELSE NULL END) AS time_in,
                            MAX(CASE WHEN t.type = 'time_out' THEN t.timestamp ELSE NULL END) AS time_out
                      FROM tbl_users u
                      LEFT JOIN tbl_timelogs t ON u.student_id = t.student_id 
                      WHERE u.coordinator_id = :coordinator_id
                      AND DATE(t.timestamp) = :today
                      GROUP BY u.student_id, u.firstname, u.lastname
                  ";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':coordinator_id', $_SESSION['coordinator_id'], PDO::PARAM_INT);
                $stmt->bindParam(':today', $today);
                $stmt->execute();

                $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <table id="datatablesSimple" class="table">
                  <thead>
                    <tr>
                      <th>Student ID</th>
                      <th>Student Name</th>
                      <th>Time In</th>
                      <th>Time Out</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($records as $record): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($record['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($record['firstname'] . ' ' . $record['lastname']); ?></td>
                        <td><?php echo $record['time_in'] ? date('H:i:s', strtotime($record['time_in'])) : '-'; ?></td>
                        <td><?php echo $record['time_out'] ? date('H:i:s', strtotime($record['time_out'])) : '-'; ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>


              </div>

            </div>
          </div>


          <div class="col-lg-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Interns <span>with No Time-Out Entries</span></h5>
                <?php
                if ($missingTimeOuts) {
                  $interns = [];

                  // Aggregate missing dates by intern
                  foreach ($missingTimeOuts as $entry) {
                    $key = $entry['firstname'] . ' ' . $entry['lastname'];
                    if (!isset($interns[$key])) {
                      $interns[$key] = [
                        'dates' => [],
                        'status' => $entry['status']
                      ];
                    }
                    $interns[$key]['dates'][] = $entry['missing_date'];
                  }

                  // Get today's date
                  $today = date('Y-m-d');

                  // Display today's missing time-out message
                  if (in_array($today, array_merge(...array_column($interns, 'dates')))) {
                    echo "
                    <div class='reminder-alert'>
                        <span style='color:#198754;'><h3><b>Pending Time Out</b></h3></span>
                        <p>You have not logged your time out for today (<strong>{$today}</strong>).</p>
                    </div>";
                  }

                  // Display past missed time-outs
                  echo "
                <div class='out-container'>
                    <div class='reminder-alert'>
                        <p>Some of your interns have not logged their time out on the following dates:</p>
                        <ul>";

                  foreach ($interns as $intern => $details) {
                    $dates = implode(', ', $details['dates']);

                    // Status badge logic
                    if ($details['status'] == 'Approved') {
                      $statusBadge = "<span class='badge badge-warning' style='color:black;background-color:orange;'>Under Review</span>";
                    } elseif ($details['status'] == 'Pending') {
                      $statusBadge = "<span class='badge badge-warning' style='color:black;background-color:orange;'>Pending</span>";
                    } elseif ($details['status'] == 'Rejected') {
                      $statusBadge = "<span class='badge badge-warning' style='color:white;background-color:red;'>Rejected</span>";
                    } else {
                      $statusBadge = ''; // For any other status, no badge will be displayed
                    }

                    // Output each intern's details
                    echo "<li><strong>{$intern}:</strong> {$dates} {$statusBadge}</li>";
                  }


                  echo "</ul>
                    </div>
                </div>";
                } else {
                  echo "<p>All time out records are up to date.</p>";
                }
                ?>
              </div>
            </div>
          </div>





        </div>
      </div>

    </div>
  </section>

</main><!-- End #main -->

<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
  crossorigin="anonymous"></script>

<?php include "footer.php"; ?>