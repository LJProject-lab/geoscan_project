<?php
include "nav.php";
?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">
<!-- ======= Sidebar ======= -->

<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link collapsed  " href="dashboard.php">
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
      <a class="nav-link " href="requirement_checklist.php">
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
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Requirement Checklist</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

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
            <th>File Uploaded</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php
        // Function to count the number of documents submitted by a student
        function countSubmittedDocuments($pdo, $student_id) {
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM tbl_requirements WHERE student_id = :student_id");
            $stmt->bindParam(':student_id', $student_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total']; // Return the count
        }

        // Loop through each user and display their information
        foreach ($users as $user): 
            $totalDocuments = countSubmittedDocuments($pdo, $user['student_id']); // Get the number of uploaded forms
        ?>
            <tr>
                <td><?php echo htmlspecialchars($user['student_id']); ?></td>
                <td><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></td>
                <td><?php echo htmlspecialchars($user['course_name']); ?></td>
                <td><?php echo $totalDocuments; ?></td>
                <td>
                    <a href="view_intern_requirement.php?student_id=<?php echo $user['student_id']; ?>" class="btn btn-success btn-sm">
                        <i class="bi bi-eye"></i> View Requirements
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

            </div>
        </div>



</main><!-- End #main -->

<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
  crossorigin="anonymous"></script>

<?php include "footer.php"; ?>