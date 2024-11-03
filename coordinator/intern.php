<?php
include "nav.php";
include "crypt_helper.php";
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
    <h1>List of Intern</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">List of Intern</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <?php if (isset($_SESSION['alert_type']) && isset($_SESSION['alert_message'])): ?>
    <script>
      Swal.fire({
        icon: '<?php echo $_SESSION['alert_type']; ?>',
        title: '<?php echo $_SESSION['alert_message']; ?>',
        showConfirmButton: false,
        timer: 1500
      });
    </script>
    <?php
    // Clear the session data after showing the alert
    unset($_SESSION['alert_type']);
    unset($_SESSION['alert_message']);
    ?>
  <?php endif; ?>



  <div class="col-xl-12">

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      <button class="btn btn-success me-md-2" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><i
          class="bi bi-person-plus-fill"></i>&nbsp;Register Intern</button>
    </div><br>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Register Intern </h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <!-- Multi Columns Form -->
            <form class="row g-3" action="register_intern_conn.php" method="post">

              <div class="col-md-12">
                <div class="form-floating mb-3">
                  <select class="form-select" name="program_id" id="floatingSelect" aria-label="State">
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
                  <label for="floatingSelect">Program</label>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-floating">
                  <input type="number" class="form-control" name="student_id" pattern="\d{4}" id="floatingName"
                    placeholder="" required>
                  <label for="floatingName">Student ID</label>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-floating">
                  <input type="text" class="form-control" name="firstname" id="floatingName" placeholder="" required>
                  <label for="floatingName">Firstname</label>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-floating">
                  <input type="text" class="form-control" name="lastname" id="floatingName" placeholder="" required>
                  <label for="floatingName">Lastname</label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-floating">
                  <input type="text" class="form-control" name="email" id="floatingName" placeholder="" required>
                  <label for="floatingName">Email</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control" name="phone" id="floatingName" placeholder="" required>
                  <label for="floatingName">Phone</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control" name="address" id="floatingName" placeholder="" required>
                  <label for="floatingName">Address</label>
                </div>
              </div>
              <!---------    COORDINATOR         ---------------->
              <input type="text" class="form-control"
                value="<?php echo htmlspecialchars($_SESSION['coordinator_id']); ?>" name="coordinator_id"
                id="floatingName" placeholder="" hidden>
              <!---------    STATUS         ---------------->
                <input type="text" class="form-control"
                value="Active" name="status"
                id="floatingName" placeholder="" hidden>

              <div class="text-center">
                <button type="submit" class="btn btn-success"><i class="ri-send-plane-fill"></i>&nbsp;Submit</button>
              </div>
            </form><!-- End Multi Columns Form -->

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i
                class="ri-close-circle-line"></i>&nbsp;Close</button>
          </div>
        </div>
      </div>
    </div>

    <?php
    $stmt = $pdo->prepare("
            SELECT u.student_id, u.firstname, u.lastname, u.profile_pic, c.program_name , c.program_hour
            FROM tbl_users u
            JOIN tbl_programs c ON u.program_id = c.program_id
            WHERE u.coordinator_id = " . $_SESSION['coordinator_id'] . "
        ");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="card">
      <div class="card-body">
        <table id="datatablesSimple" class="table">
          <thead>
            <tr>
              <th>Profile</th>
              <th>Student ID</th>
              <th>Student Name</th>
              <th>Program</th>
              <th>Hours to render</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                <td><img src="../intern/uploads/profile_pics/<?php echo $user['profile_pic'] ? htmlspecialchars($user['profile_pic'], ENT_QUOTES, 'UTF-8') : 'profile.png'; ?>" 
                  alt="Profile Picture" 
                  width="40" 
                  height="40">
                </td>
                <td><?php echo htmlspecialchars($user['student_id']); ?></td>
                <td><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></td>
                <td><?php echo htmlspecialchars($user['program_name']); ?></td>
                <td><?php echo htmlspecialchars($user['program_hour']); ?></td>
                <td>
                  <a href="view_intern.php?student_id=<?php echo urlencode(encryptData($user['student_id'])); ?>"
                    class="btn btn-success btn-sm"><i class="bi bi-eye"></i> View</a>
                  <a href="javascript:void(0);" onclick="confirmDelete('<?php echo $user['student_id']; ?>')"
                    class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Remove</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>


  </div>



</main><!-- End #main -->

<script>
  function confirmDelete(studentId) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'remove_intern.php?student_id=' + studentId;
      }
    });
  }
</script>


<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
  crossorigin="anonymous"></script>

<?php include "footer.php"; ?>