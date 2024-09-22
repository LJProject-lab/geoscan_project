<?php
include "nav.php";
include_once 'functions/fetch-records.php';
include_once '../includes/getAddress.php';
?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../assets/css/table.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
      <a class="nav-link " href="my_attendance.php">
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
      <a class="nav-link collapsed" href="progress_report.php">
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
    <h1>My Attendance</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">My Attendance</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section attendance">

  <div class="col-xl-12">
  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-end mb-3">
        <button id="refreshTable" class="btn btn-success me-md-2"><i class='bx bx-loader' style="color:#fff;"></i> &nbsp;Reload</button>
      </div>
      <div class="table-responsive">
        <table id="datatablesSimple" class="table table-striped table-hover table-bordered">
          <thead>
            <tr>
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

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Photo View</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <img id="modalImage" src="" alt="Photo" class="img-fluid">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  </section>



</main><!-- End #main -->

<style>
  /* Ensure the modal image fits well */
  .modal-body {
    text-align: center; /* Center the image in the modal */
  }

  #modalImage {
    max-width: 100%; /* Ensure the image doesn't overflow the modal */
    max-height: 80vh; /* Set a max height to avoid excessive height */
    object-fit: contain; /* Maintain aspect ratio while fitting inside the modal */
  }
</style>

<div id="preloader">
  <div class="loader"></div>
</div>
<script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="../assets/js/main.js"></script>
<!-- Include DataTables library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.5/js/dataTables.min.js" crossorigin="anonymous"></script>
<link href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css" rel="stylesheet" />
<script>
  document.addEventListener('DOMContentLoaded', function () {
    function refreshCurrentPageAddresses() {
        // Get all elements with the class 'address-container' that still display "Loading..."
        const addressContainers = document.querySelectorAll('.address-container');

        addressContainers.forEach(function(container) {
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
                        <td>${log.type === 'time_in' ? 'Time In' : 'Time Out'}</td>
                        <td>${log.timestamp}</td>
                        <td>
                          ${log.photo !== 'N/A' ? `
                            <img src="${log.photo}" alt="Photo" width="50" height="50" style="object-fit: cover; cursor: pointer;" onclick="openModal('${log.photo}')">
                          ` : 'N/A'}
                        </td>
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

function openModal(photoSrc) {
    const modalImage = document.getElementById('modalImage');
    modalImage.src = photoSrc; // Set the source of the image in the modal
    const exampleModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    exampleModal.show(); // Show the modal using Bootstrap
  }
</script>

<?php include "footer.php"; ?>