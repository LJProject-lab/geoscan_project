<?php
include "nav.php";
include "config.php";
?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link collapsed" href="dashboard.php">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-heading">Pages</li>

    <li class="nav-item">
      <a class="nav-link " href="register_intern.php">
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
      <a class="nav-link collapsed" href="progress_report.php">
        <i class="ri-line-chart-fill"></i>
        <span>Progress Report</span>
      </a>
    </li>

  </ul>

</aside><!-- End Sidebar-->

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Register Intern</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Register Intern</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->



  <div class="col-xl-12">

  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button class="btn btn-success me-md-2" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-person-plus-fill"></i>&nbsp;Register Intern</button>
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
                        <select class="form-select" name="course" id="floatingSelect" aria-label="State">
                            <option selected disabled>Select Course</option>
                            <?php
                            // Fetching courses from the database
                            $stmt = $pdo->query("SELECT course_id, course_name FROM tbl_courses");

                            // Looping through the result set and generating option elements
                            while ($row = $stmt->fetch()) {
                                echo '<option value="' . htmlspecialchars($row['course_id']) . '">' . htmlspecialchars($row['course_name']) . '</option>';
                            }
                            ?>
                        </select>
                        <label for="floatingSelect">Course</label>
                    </div>
                </div>
                <div class="col-md-2">
                  <div class="form-floating">
                    <input type="number" class="form-control" name="student_id" pattern="\d{4}"  id="floatingName" placeholder="">
                    <label for="floatingName">Student ID</label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="firstname" id="floatingName" placeholder="">
                    <label for="floatingName">Firstname</label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="lastname" id="floatingName" placeholder="">
                    <label for="floatingName">Lastname</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="email" id="floatingName" placeholder="">
                    <label for="floatingName">Email</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="phone" id="floatingName" placeholder="">
                    <label for="floatingName">Phone</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="address" id="floatingName" placeholder="">
                    <label for="floatingName">Address</label>
                  </div>
                </div>
                <!---------    COORDINATOR         ---------------->
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION['id']); ?>" name="coordinator" id="floatingName" placeholder="" hidden>

                <div class="text-center">
                  <button type="submit" class="btn btn-primary"><i class="ri-send-plane-fill"></i>&nbsp;Submit</button>
                </div>
              </form><!-- End Multi Columns Form -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ri-close-circle-line"></i>&nbsp;Close</button>
            </div>
            </div>
        </div>
        </div>

        <?php
        $stmt = $pdo->prepare("
            SELECT u.student_id, u.firstname, u.lastname, c.course_name 
            FROM tbl_users u
            JOIN tbl_courses c ON u.course = c.course_id
        ");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="card">
            <div class="card-body">
                <table id="datatablesSimple" class="table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Course</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($user['course_name']); ?></td>
                                <td>
                                    <a href="view_intern.php?student_id=<?php echo $user['student_id']; ?>" class="btn btn-success btn-sm"><i class="bi bi-eye"></i> View</a>
                                    <a href="#.php?student_id=<?php echo $user['student_id']; ?>" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Remove</a>
                                </td>
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