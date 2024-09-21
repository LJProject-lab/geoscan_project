<?php
include "nav.php";
include_once 'functions/fetch-records.php';

// Call the function to calculate the intern's progress
$progress = getInternProgress($student_id, $program_id, $pdo);
?>

<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link collapsed" href="index.php">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-heading">Pages</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="my_attendance.php">
        <i class="ri-fingerprint-line"></i>
        <span>My Attendance</span>
      </a>
    </li>

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
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Progress Report</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button class="btn btn-success me-md-2" type="button">
      <a href="detailed_report.php" style="color:white;">View Detail</a>
    </button>
  </div><br>
  <div class="col-xl-12">

    <div class="card">
      <style>

      </style>

      <div class="card-body">
        <canvas id="progressChart" class="small-chart"></canvas> <!-- Apply the CSS class -->
      </div>

    </div>
  </div>

</main><!-- End #main -->

<div id="preloader">
  <div class="loader"></div>
</div>
<script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
  crossorigin="anonymous"></script>

<script>
  // Fetch progress data from PHP
  const progressData = <?php echo json_encode($progress); ?>;

  // Render the horizontal bar chart using Chart.js
  const ctx = document.getElementById('progressChart').getContext('2d');
  const progressChart = new Chart(ctx, {
    type: 'bar', // Bar chart type
    data: {
      labels: ['Progress'],
      datasets: [
        {
          label: 'Hours Rendered',
          data: [progressData.total_hours],
          backgroundColor: '#198754',
        },
        {
          label: 'Hours Remaining',
          data: [progressData.hours_remaining],
          backgroundColor: '#f68c09',
        }
      ]
    },
    options: {
      indexAxis: 'y', // Makes the bar horizontal
      responsive: true,
      maintainAspectRatio: false, // Allows resizing
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Internship Progress'
        }
      },
      scales: {
        x: {
          stacked: true, // Stacks the bars on the x-axis
          beginAtZero: true,
          max: progressData.required_hours // Set the max value to required hours
        },
        y: {
          stacked: true // Stacks the bars on the y-axis
        }
      }
    }
  });
</script>
<?php include "footer.php"; ?>