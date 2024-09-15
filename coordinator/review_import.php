<?php
if (isset($_POST['action']) && $_POST['action'] === 'clear') {
  // Clear session data related to import
  unset($_SESSION['import_data']);
  // Redirect to the same page to refresh and clear the table
  header('Location: ' . $_SERVER['PHP_SELF']);
  exit();
}

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
    <h1>List of Intern to be Registered</h1>
    <nav>
      <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="add_intern.php">Add Intern</a></li>
        <li class="breadcrumb-item active">List of Intern to be Registered</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
 
    <?php
    if (isset($_SESSION['import_data'])) {
        $importData = $_SESSION['import_data'];
        $programId = $importData['program_id'];
        $coordinatorId = $importData['coordinator_id'];
        $status = $importData['status'];
        $data = $importData['data'];

        $sql = "SELECT program_name FROM tbl_programs WHERE program_id = :program_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':program_id' => $programId]);
        $program = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>

    <div class="card">
      <div class="card-body">
        <form action="save_import.php" method="post">
            
        <input type="hidden" name="program_id" value="<?php echo htmlspecialchars($programId); ?>">
        <input type="hidden" name="coordinator_id" value="<?php echo htmlspecialchars($coordinatorId); ?>">
        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
        <table class="table">
            <h5 class="card-title">Program : <?php echo htmlspecialchars($program['program_name']); ?></h5>
          <thead>
            <tr>
                <th>#</th>
                <th>Student ID</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
            </tr>
          </thead>

          <tbody>
              <?php
              $count = 1;
              foreach ($data as $row):
              ?>
              <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo htmlspecialchars($row[0]); ?></td>
                <td><?php echo htmlspecialchars($row[1]); ?></td>
                <td><?php echo htmlspecialchars($row[2]); ?></td>
                <td><?php echo htmlspecialchars($row[3]); ?></td>
                <td><?php echo htmlspecialchars($row[4]); ?></td>
                <td><?php echo htmlspecialchars($row[5]); ?></td>
              </tr>
              <?php
              $count++; 
              endforeach;
              ?>
          </tbody>
        </table>

          <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <button type="submit" name="save" class="btn btn-success">Save Data</button>
          </div>
        </form>

      </div>
    </div>

    <?php
    } // End check for session['import_data']
    ?>

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