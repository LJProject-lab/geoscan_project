<?php
include "nav.php";

$interns = $pdo->query("SELECT student_id, firstname, lastname FROM tbl_users")->fetchAll(PDO::FETCH_ASSOC);
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

    <li class="nav-heading">Configuration</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="add_intern.php">
        <i class="bi bi-person-plus-fill"></i>
        <span>Add Intern</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link " href="generate_report.php">
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
    <h1>Generate Intern Report</h1>
    <nav>
      <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Generate Intern Report</li>
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
                  <select id="student_id" class="form-select" id="student_id" name="student_id" required>
                    <option value="" disabled selected>Select Intern</option>
                        <?php foreach ($interns as $intern): ?>
                            <option value="<?php echo htmlspecialchars($intern['student_id']); ?>">
                                <?php echo htmlspecialchars($intern['lastname'] . ", " . $intern['firstname']); ?>
                            </option>
                        <?php endforeach; ?>
                  </select>
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




<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
  crossorigin="anonymous"></script>

<?php include "footer.php"; ?>