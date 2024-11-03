<?php
include "nav.php";
include "crypt_helper.php";
?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">
<!-- ======= Sidebar ======= -->

<style>
  h5 {
    font-weight: 600;
  }
</style>

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
      <a class="nav-link collapsed" href="generate_report.php">
        <i class="ri-folder-download-line"></i>
        <span>Generate Intern Report</span>
      </a>
    </li>

    <li class="nav-heading">Pages</li>

    <li class="nav-item">
      <a class="nav-link " href="intern.php">
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
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="intern.php">List of Intern</a></li>
        <li class="breadcrumb-item active">Interns Info</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <?php
  if (isset($_GET['student_id'])) {
    $student_id = decryptData($_GET['student_id']);

    // Fetch the user's details using the decrypted $student_id
    $stmt = $pdo->prepare("
      SELECT u.student_id, u.firstname, u.lastname, u.email, u.phone, u.address, u.coordinator_id, u.credential_id, u.profile_pic,
          c.program_id, c.program_name, 
          co.firstname AS coordinator_firstname, co.lastname AS coordinator_lastname
      FROM tbl_users u
      JOIN tbl_programs c ON u.program_id = c.program_id
      LEFT JOIN tbl_coordinators co ON u.coordinator_id = co.coordinator_id
      WHERE u.student_id = :student_id
  ");
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists
    if ($user) {
      // Display the form with the user's data
      ?>


      <section class="section profile">
        <div class="row">
          <div class="col-xl-4">

            <div class="card">
              <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="../intern/uploads/profile_pics/<?php echo $user['profile_pic'] ? htmlspecialchars($user['profile_pic'], ENT_QUOTES, 'UTF-8') : 'profile.png'; ?>" width="100" height="100">

                <h2><?php echo htmlspecialchars($user['firstname'] . " " . $user['lastname']); ?></h2>
                <h3>Intern</h3>
              </div>
            </div>

          </div>

          <div class="col-xl-8">

            <div class="card">
              <div class="card-body pt-3">

                <div class="tab-content pt-2">

                  <div class="tab-pane fade show active profile-overview" id="profile-overview">

                    <h5>Intern Details</h5><br>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label ">Student ID</div>
                      <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user['student_id']); ?></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label ">Firstname</div>
                      <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user['firstname']); ?></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label ">Lastname</div>
                      <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user['lastname']); ?></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">program</div>
                      <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user['program_name']); ?></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Email</div>
                      <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Phone</div>
                      <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user['phone']); ?></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Address</div>
                      <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user['address']); ?></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Coordinator</div>
                      <div class="col-lg-9 col-md-8">
                        <?php echo htmlspecialchars($user['coordinator_firstname'] . ' ' . $user['coordinator_lastname']); ?>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Fingerprint Registered?</div>
                      <div class="col-lg-9 col-md-8"><?php echo $user['credential_id'] ? "YES" : "NO"; ?></div>
                    </div>

                  </div>


                </div>
              </div>

            </div>
          </div>
      </section>

      <?php
    } else {
      echo "User not found.";
    }
  } else {
    echo "No student ID provided.";
  }
  ?>



</main><!-- End #main -->

<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
  crossorigin="anonymous"></script>

<?php include "footer.php"; ?>