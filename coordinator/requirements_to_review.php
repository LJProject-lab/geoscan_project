<?php
include "nav.php";
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
      <a class="nav-link collapsed" href="register_intern.php">
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

  </ul>

</aside><!-- End Sidebar-->

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Requirements to Review</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Requirements to Review</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <table id="datatablesSimple" class="table">
          <thead>
            <tr>
              <th>Student ID</th>
              <th>Student Name</th>
              <th>Form</th>
              <th>Date Submitted</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt = $pdo->prepare("
                    SELECT r.student_id, 
                           CONCAT(u.firstname, ' ', u.lastname) AS student_name, 
                           r.form_type, 
                           r.uploaded_at 
                    FROM tbl_requirements r
                    JOIN tbl_users u ON r.student_id = u.student_id
                    WHERE u.coordinator_id = :coordinator_id
                ");
            $stmt->execute(['coordinator_id' => $_SESSION['coordinator_id']]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Generate HTML table rows
            foreach ($results as $row):

            ?>
            <tr>
              <td><?php echo  htmlspecialchars($row['student_id']); ?></td>
              <td><?php echo htmlspecialchars($row['student_name']); ?></td>
              <td><?php echo htmlspecialchars($row['form_type']); ?></td>
              <td><?php echo htmlspecialchars($row['uploaded_at']); ?></td>
              <td></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>


  </div>



</main><!-- End #main -->


<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
  crossorigin="anonymous"></script>

<?php include "footer.php"; ?>