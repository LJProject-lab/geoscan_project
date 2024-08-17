<?php
include "nav.php";
include_once 'functions/fetch-records.php';
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
    <h1>Progress Report</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active">Progress Report</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <div class="col-xl-12">

    <div class="card">
      <div class="card-body">
        <table id="datatablesSimple" class="table">
          <thead>
            <tr>
              <th>Type</th>
              <th>Timestamp</th>
              <th>Longitude</th>
              <th>Latitude</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($logs as $log): ?>
              <tr>
                <td>
                  <?php if ($log['type'] == "time_in") { ?>
                    Time In
                  <?php } else { ?>
                    Time Out
                  <?php } ?>
                </td>
                <td>
                  <?php echo $log['timestamp']; ?>
                </td>
                <td>
                  <?php echo $log['longitude']; ?>
                </td>
                <td>
                  <?php echo $log['latitude']; ?>
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