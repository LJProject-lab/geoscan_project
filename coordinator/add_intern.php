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

    <li class="nav-heading">Configuration</li>

    <li class="nav-item">
      <a class="nav-link " href="add_intern.php">
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
    <h1>Add Intern</h1>
    <nav>
      <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Add Intern</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="card">
        <div class="card-body">
        <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="row mb-3">
            <label for="formFile" class="col-sm-2 col-form-label">Upload Excel File</label>
            <div class="col-sm-10">
                <input class="form-control" type="file" id="formFile" name="excel_file" required>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Program</label>
            <div class="col-sm-10">
                <select class="form-select" name="program" aria-label="Default select example" required>
                    <option selected disabled>Select Program</option>
                    <?php
                      // Ensure the department_id is set in the session
                      if (isset($_SESSION['department_id'])) {
                          // Fetching programs from the database using a prepared statement
                          $query = "SELECT program_id, program_name FROM tbl_programs WHERE department_id = :department_id";
                          $stmt = $pdo->prepare($query);
                          $stmt->bindParam(':department_id', $_SESSION['department_id'], PDO::PARAM_INT);
                          $stmt->execute();
                          
                          // Fetch results and populate the options
                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                              echo '<option value="' . htmlspecialchars($row['program_id']) . '">' . htmlspecialchars($row['program_name']) . '</option>';
                          }
                      } else {
                          // Handle the case where department_id is not set
                          echo '<option value="" disabled>No Programs Available</option>';
                      }
                      ?>

                </select>
            </div>
        </div>
        <input type="hidden" name="coordinator_id" value="<?php echo htmlspecialchars($coordinator_id); ?>">
        <input type="hidden" name="status" value="Active">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" name="process" class="btn btn-warning"><i class="ri-speed-mini-fill"> </i>Process</button>
        </div>
    </form>

        </div>
      </div>
    </div>

  </section>

</main><!-- End #main -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const duplicates = urlParams.get('duplicates');

    if (status === 'success') {
        Swal.fire({
            title: 'Success!',
            text: 'Interns successfully registered.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    } else if (status === 'error') {
        Swal.fire({
            title: 'Error!',
            text: 'Some student IDs already exist in the database: ' + decodeURIComponent(duplicates),
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
});
</script>




<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
  crossorigin="anonymous"></script>

<?php include "footer.php"; ?>