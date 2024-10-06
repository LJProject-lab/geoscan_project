<?php
include "nav.php";

?>

<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      <a class="nav-link " href="progress_report.php">
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
    <h1>Generate Report</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="progress_report.php">Progress Report</a></li>
        <li class="breadcrumb-item active">Generate Report</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    
  <div class="card">
            <div class="card-body">
              <h5 class="card-title">Generate Report</h5>

              <!-- Vertical Form -->
              <form class="row g-3" action="generate_data.php" method="post">
                <div class="col-12">
                  <label for="" class="form-label">Intern</label>
                  <input type="text" class="form-control" id="student_id" name="student_id" value="<?php echo htmlspecialchars($_SESSION['student_id']); ?>" required>
                </div>
                <div class="col-6">
                  <label for="" class="form-label">Start Date</label>
                  <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="col-6">
                  <label for="" class="form-label">End Date</label>
                  <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                  <button type="submit" class="btn btn-warning"> <i class="ri-speed-mini-fill"></i> Process</button>
                </div>
              </form><!-- Vertical Form -->

            </div>
          </div>


  </section>


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