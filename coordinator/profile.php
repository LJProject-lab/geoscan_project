<?php
include "nav.php";
?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">
<!-- ======= Sidebar ======= -->

<aside id="sidebar" class="sidebar">

<style>
    /* profile.css */

.profile-card {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  padding: 20px;
  margin: 20px;
}

.profile-header {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.profile-picture {
  flex: 0 0 150px;
  margin-right: 20px;
}

.profile-picture img {
  border-radius: 50%;
  width: 150px;
  height: 150px;
  object-fit: cover;
}

.profile-details {
  flex: 1;
}

.profile-details h2 {
  margin: 0;
  color: #198754;
  font-size: 24px;
}

.profile-details p {
  margin: 5px 0;
  font-size: 16px;
}

.profile-body {
  margin-top: 20px;
}

.profile-body h3 {
  margin: 0;
  font-size: 20px;
}

.profile-body p {
  font-size: 16px;
  line-height: 1.6;
}

</style>

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
    <h1>My Profile</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">My Profile</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="profile-card">
          <div class="profile-header">
            <div class="profile-picture">
              <img src="assets/img/coordinator.jpg" alt="Profile Picture" />
            </div>
            <div class="profile-details">
              <h2><?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?></h2>
              <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
              <p><strong>Phone:</strong> +1 234 567 890</p>
              <p><strong>Position:</strong> Intern Coordinator</p>
              <p><strong>Department:</strong> HR</p>
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