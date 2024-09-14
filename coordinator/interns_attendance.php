<!-- interns_attendance.php -->
<?php
include "nav.php";
include_once 'functions/fetch-records.php';
include_once '../includes/getAddress.php';
?>
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
      <a class="nav-link " href="interns_attendance.php">
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
    <h1>Intern Attendance</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Intern Attendance</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-end mb-3">
        <button id="refreshTable" class="btn btn-success me-md-2"><i class='bx bx-loader'></i> &nbsp;Reload</button>
      </div>
      <div class="table-responsive">
        <table id="datatablesSimple" class="table table-striped table-hover table-bordered">
          <thead>
            <tr>
              <th>Student Name</th>
              <th>Type</th>
              <th>Timestamp</th>
              <th>Photo</th>
              <th>Location</th>
            </tr>
          </thead>
          <tbody id="logsTableBody">

          </tbody>
        </table>
      </div>
    </div>
  </div>



</main><!-- End #main -->
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

<!-- Include DataTables library -->
<script src="https://cdn.datatables.net/2.1.5/js/dataTables.min.js" crossorigin="anonymous"></script>
<link href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css" rel="stylesheet" />


<script>
  document.addEventListener('DOMContentLoaded', function () {
    function refreshCurrentPageAddresses() {
      // Get all elements with the class 'address-container' that still display "Loading..."
      const addressContainers = document.querySelectorAll('.address-container');

      addressContainers.forEach(function (container) {
        if (container.textContent.trim() === 'Converting...') {
          const addressSpan = container.closest('.addresss');
          const lat = addressSpan.getAttribute('data-lat');
          const lng = addressSpan.getAttribute('data-lng');

          // Fetch address using AJAX
          fetch(`fetch-address.php?latitude=${lat}&longitude=${lng}`)
            .then(response => response.json())
            .then(data => {
              if (data && data.address) {
                container.textContent = data.address;
              } else {
                container.textContent = 'Address not found';
              }
            })
            .catch(error => {
              console.error('Error fetching address:', error);
              container.textContent = 'Error fetching address';
            });
        }
      });
    }

    // Load the data initially and run address conversion automatically
    loadTableData();

    // Add event listener for refresh button
    document.getElementById('refreshTable').addEventListener('click', function () {
      refreshCurrentPageAddresses();
    });

    // Load table data function (initial load)
    function loadTableData() {
      const tbodys = document.getElementById('logsTableBody');
      if (tbodys) {
        tbodys.innerHTML = ''; // Clear existing content

        fetch('fetch-logs.php')
          .then(response => {
            if (!response.ok) {
              throw new Error(`Network response was not ok: ${response.statusText}`);
            }
            return response.text(); // Get raw text first
          })
          .then(text => {
            try {
              return JSON.parse(text); // Parse JSON manually
            } catch (e) {
              throw new Error('Error parsing JSON: ' + e.message);
            }
          })
          .then(data => {
            data.forEach(log => {
              const row = document.createElement('tr');
              row.innerHTML = `
                        <td>${log.firstname} ${log.lastname}</td>
                        <td>${log.type === 'time_in' ? 'Time In' : 'Time Out'}</td>
                        <td>${log.timestamp}</td>
                        <td>${log.photo ? `<img src="${log.photo}" alt="Photo" width="100" height="100">` : 'N/A'}</td>
                        <td>
                            <span class="addresss" data-lat="${log.latitude}" data-lng="${log.longitude}">
                                <div class="address-container">Converting...</div>
                            </span>
                        </td>
                    `;
              tbodys.appendChild(row);
            });

            // Reinitialize DataTables after adding rows
            if ($.fn.DataTable.isDataTable('#datatablesSimple')) {
              $('#datatablesSimple').DataTable().destroy();
            }
            $('#datatablesSimple').DataTable();

            // Automatically convert addresses after the table is loaded
            refreshCurrentPageAddresses();
          })
          .catch(error => {
            console.error('Error fetching logs:', error);
          });
      } else {
        console.error('Element with id "logsTableBody" not found');
      }
    }
  });

</script>



<?php include "footer.php"; ?>