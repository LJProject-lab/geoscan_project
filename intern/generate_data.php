<?php
include "nav.php";

$apiKey = 'ZWUFEvqrALs4WtVDTw4yjUGGjkFPTGGE';

// Function to convert coordinates to address using TomTom API
function convertCoordinates($latitude, $longitude) {
    global $apiKey; // Use global API key

    // URL for TomTom Reverse Geocoding API
    $url = "https://api.tomtom.com/search/2/reverseGeocode/{$latitude},{$longitude}.json?key={$apiKey}";

    // Make the HTTP request
    $response = @file_get_contents($url);

    // Check if the response is valid
    if ($response === FALSE) {
        return "Error retrieving address";
    }

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check if we have results
    if (isset($data['addresses'][0]['address']['freeformAddress'])) {
        return $data['addresses'][0]['address']['freeformAddress'];
    } else {
        return "Address not found";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form input
    $intern_id = $_POST['student_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Ensure inputs are not empty
    if (empty($intern_id) || empty($start_date) || empty($end_date)) {
        die('Invalid input');
    }

    try {
        // Fetch intern details
        $stmt = $pdo->prepare("
            SELECT u.firstname, u.lastname, p.program_name
            FROM tbl_users u
            JOIN tbl_programs p ON u.program_id = p.program_id
            WHERE u.student_id = :student_id
        ");
        $stmt->bindParam(':student_id', $intern_id);
        $stmt->execute();
        $intern = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$intern) {
            die('Intern not found.');
        }

        $intern_name = $intern['lastname'] . ', ' . $intern['firstname'];
        $intern_program = $intern['program_name'];

        // Fetch time logs data
        $stmt = $pdo->prepare("
            SELECT type, timestamp, latitude, longitude
            FROM tbl_timelogs
            WHERE student_id = :student_id
            AND timestamp BETWEEN :start_date AND :end_date
        ");
        $stmt->bindParam(':student_id', $intern_id);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            // No records found - set a JavaScript variable to trigger SweetAlert
            $noRecordsFound = true;
        } else {
            // Prepare data for review
            $rows = [];
            foreach ($data as $record) {
                // Format timestamp
                $dateTime = new DateTime($record['timestamp']);
                $formattedDate = $dateTime->format('F j, Y');
                $formattedTime = $dateTime->format('g:iA');
                
                $address = convertCoordinates($record['latitude'], $record['longitude']);

                $rows[] = [
                    'Type' => $record['type'],
                    'Date' => $formattedDate,
                    'Time' => $formattedTime,
                    'Address' => $address
                ];
            }
        }
    } catch (Exception $e) {
        error_log('Error retrieving data: ' . $e->getMessage());
        die('Error retrieving data.');
    }
} else {
    die('Invalid request method.');
}
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

</aside>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Generate Intern Report</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="generate_report.php">Generate Intern Report</a></li>
        <li class="breadcrumb-item active">Download Report</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Report Generated</h5>
        <p><strong>Intern Name:</strong> <?php echo htmlspecialchars($intern_name); ?></p>
        <p><strong>Program:</strong> <?php echo htmlspecialchars($intern_program); ?></p>
        <p><strong>From:</strong> <?php echo $start_date; ?>&nbsp;&nbsp;&nbsp;
        <strong>To:</strong> <?php echo $end_date; ?></p>
        <?php if (!empty($rows)): ?>
          <table class="table table-bordered">
              <thead>
                  <tr>
                      <th>Type</th>
                      <th>Date</th>
                      <th>Time</th>
                      <th>Location</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($rows as $row): ?>
                  <tr>
                      <td><?php 
                      $typeLabel = $row['Type'] === 'time_in' ? 'Time In' : 'Time Out';
                      echo $typeLabel;
                      ?></td>
                      <td><?php echo htmlspecialchars($row['Date']); ?></td>
                      <td><?php echo htmlspecialchars($row['Time']); ?></td>
                      <td><?php echo htmlspecialchars($row['Address']); ?></td>
                  </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
          <form action="export_excel.php" method="post">
              <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($intern_id); ?>">
              <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
              <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
              <input type="hidden" name="intern_name" value="<?php echo htmlspecialchars($intern_name); ?>">
              <input type="hidden" name="intern_program" value="<?php echo htmlspecialchars($intern_program); ?>">
              <button type="submit" class="btn btn-success"><i class="ri-file-excel-2-line"> </i>&nbsp;Export to Excel</button>
          </form>
        <?php else: ?>
          <!-- Trigger SweetAlert if no records are found -->
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              Swal.fire({
                title: 'No Records Found',
                text: 'No records found for the given criteria.',
                icon: 'warning',
                confirmButtonText: 'OK'
              }).then(function() {
                window.location.href = 'generate_report.php';
              });
            });
          </script>
        <?php  endif; 
        ?>
      </div>
    </div>
  </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
  crossorigin="anonymous"></script>

<?php include "footer.php"; ?>