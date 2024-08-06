<?php
include "nav.php";
?>

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
        <a class="nav-link " href="requirement_checklist.php">
          <i class="bi bi-file-earmark-check-fill"></i>
          <span>Requirements Checklist</span>
        </a>
      </li><!-- End Login Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="progress_report.php">
          <i class="ri-line-chart-fill"></i>
          <span>Progress Report</span>
        </a>
      </li><!-- End Blank Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Requirements Checklist</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item active">Requirements Checklist</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <!-- Code starts here -->

  </main><!-- End #main -->



  <?php include "footer.php"; ?>